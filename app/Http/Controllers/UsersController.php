<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Jaslab;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Department;
use App\Jobs\ProcessBulkUsersJob;

class UsersController extends Controller
{
    public function usersView(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $nim = $request->input('nim');
        $nip = $request->input('nip');
        $department_id = $request->input('department_id');
        $faculty = $request->input('faculty');
        $keyword = $request->input('keyword');
        
        $roleSlug = $request->input('jabatan');

        $query = User::query();

        if ($keyword) {
            $query->where(function ($sub) use ($keyword) {
                $sub->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('email', 'LIKE', "%{$keyword}%")
                    ->orWhere('nim', 'LIKE', "%{$keyword}%")
                    ->orWhere('nip', 'LIKE', "%{$keyword}%");
            });
        }

        if ($name) $query->where('name', 'LIKE', "%{$name}%");
        if ($email) $query->where('email', 'LIKE', "%{$email}%");
        if ($nim) $query->where('nim', 'LIKE', "%{$nim}%");
        if ($nip) $query->where('nip', 'LIKE', "%{$nip}%");
        
        if ($department_id) {
            $query->where('department_id', $department_id);
        } elseif ($faculty) {
            $query->whereHas('department', function ($q) use ($faculty) {
                $q->where('faculty', $faculty);
            });
        }

        $query->when($roleSlug, function ($q) use ($roleSlug) {
            $mappedSlug = match (strtolower(trim($roleSlug))) {
                'admin lab', 'admin_lab' => 'admin_lab',
                'dosen', 'lecturer' => 'lecturer',
                'asisten dosen', 'assistant' => 'assistant',
                'mahasiswa', 'student' => 'student',
                default => $roleSlug
            };

            $q->whereHas('roles', function ($qRole) use ($mappedSlug) {
                $qRole->where('slug', $mappedSlug);
            });
        });

        // Fast Pagination (Deferred Join)
        // 1. Ambil paginasi hanya pada kolom ID untuk memangkas memori & overhead
        $perPage = $request->input('per_page', 10); // Dynamic per_page, default 10
        $paginator = $query->select('users.id')->latest()->paginate($perPage)->withQueryString();

        // 2. Ambil model utuh (dengan eager loading) hanya untuk ID yang tampil di halaman ini
        if ($paginator->isNotEmpty()) {
            $userIds = $paginator->pluck('id')->toArray();
            
            $usersData = User::with(['roles', 'department'])
                ->whereIn('id', $userIds)
                ->get()
                ->keyBy('id');
            
            // Mengembalikan urutan sesuai dengan paginator
            $sortedUsers = collect($userIds)->map(fn($id) => $usersData[$id]);
            $paginator->setCollection($sortedUsers);
        }

        $users = $paginator;
        $roles = Role::all();
        $allDepartments = Department::all(['id', 'name', 'faculty']);
        $faculties = Department::select('faculty')->whereNotNull('faculty')->distinct()->pluck('faculty');

        if ($request->ajax()) {
            return view('users.partials.table', compact('users', 'roles', 'allDepartments', 'faculties'))->render();
        }

        return view('users.index', compact('users', 'roles', 'allDepartments', 'faculties'));
    }

    public function importView()
    {
        return view('users.import');
    }

    private function validateSingleRow($type, $row)
    {
        $errors = [];
        // Basic fields
        if (empty($row['name'])) $errors['name'] = 'Nama wajib diisi.';
        
        if (empty($row['email'])) {
            $errors['email'] = 'Email wajib diisi.';
        } elseif (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email tidak valid.';
        } elseif (User::where('email', $row['email'])->exists()) {
            $errors['email'] = 'Email sudah terdaftar.';
        }

        if (empty($row['department_code'])) {
            $errors['department_code'] = 'Jurusan wajib diisi.';
        } elseif (!Department::where('id', $row['department_code'])->exists()) {
            $errors['department_code'] = 'ID Jurusan tidak ditemukan.';
        }

        if (empty($row['role'])) {
            $errors['role'] = 'Peran wajib diisi.';
        } elseif (!Role::where('slug', $row['role'])->exists()) {
            $errors['role'] = 'Slug peran tidak valid.';
        }

        if ($type === 'staf') {
            if (empty($row['nip'])) {
                $errors['nip'] = 'NIP wajib diisi.';
            } elseif (User::where('nip', $row['nip'])->exists()) {
                $errors['nip'] = 'NIP sudah terdaftar.';
            }
        } else {
            if (empty($row['nim'])) {
                $errors['nim'] = 'NIM wajib diisi.';
            } elseif (User::where('nim', $row['nim'])->exists()) {
                $errors['nim'] = 'NIM sudah terdaftar.';
            }
        }

        return $errors;
    }

    public function validateBulk(Request $request)
    {
        $type = $request->input('type');
        $rows = $request->input('rows', []);
        
        foreach ($rows as &$row) {
            $errors = $this->validateSingleRow($type, $row);
            $row['errors'] = (object) $errors;
            $row['isValid'] = empty($errors);
        }

        return response()->json(['success' => true, 'data' => $rows]);
    }

    public function validateRow(Request $request)
    {
        $type = $request->input('type');
        $row = $request->input('row');
        
        $errors = $this->validateSingleRow($type, $row);
        $row['errors'] = (object) $errors;
        $row['isValid'] = empty($errors);

        return response()->json(['success' => true, 'data' => $row]);
    }

    public function processBulk(Request $request)
    {
        $type = $request->input('type');
        $rows = $request->input('rows', []);
        
        $validRows = [];
        foreach ($rows as $row) {
            $errors = $this->validateSingleRow($type, $row);
            if (empty($errors)) {
                $validRows[] = $row;
            }
        }

        if (count($validRows) === 0) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data valid untuk diproses.']);
        }

        // Log awal
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'import_users',
            'description' => "Memulai proses import " . count($validRows) . " data pengguna ($type)."
        ]);

        ProcessBulkUsersJob::dispatch($validRows, $type, Auth::id());

        session()->flash('success', '🚀 Import Sedang Diproses! Sebanyak ' . count($validRows) . ' data pengguna sedang ditambahkan ke sistem di latar belakang. Silakan cek riwayat di Activity Logs.');

        return response()->json(['success' => true]);
    }

    public function jaslabView()
    {
        $jaslab = Jaslab::all();
        return view('lab-suits.index', compact('jaslab'));
    }

    public function addUser(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['email', 'required', 'unique:users,email'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,slug'],
        ]);

        $roles = collect($request->input('roles'));

        // Enforce role dependency rules
        if ($roles->contains('admin_lab') && !$roles->contains('lecturer')) {
            $roles->push('lecturer');
        }
        if ($roles->contains('assistant') && !$roles->contains('student')) {
            $roles->push('student');
        }

        // Enforce mutual exclusion between Dosen/Admin and Mahasiswa/Asisten
        $hasStaff = $roles->contains('lecturer') || $roles->contains('admin_lab');
        $hasStudent = $roles->contains('student') || $roles->contains('assistant');

        if ($hasStaff && $hasStudent) {
            return redirect()->back()->withInput()->withErrors(['roles' => 'Pengguna tidak dapat menjadi Dosen/Admin dan Mahasiswa/Asisten secara bersamaan.']);
        }

        // Validate specific fields based on roles
        if ($roles->contains('student')) {
            $request->validate([
                'nim' => ['required', 'string', 'unique:users,nim'],
                'department_id' => ['required', 'exists:departments,id'],
                'entry_year' => ['required', 'integer'],
            ]);
        }

        if ($roles->contains('lecturer')) {
            $request->validate([
                'nip' => ['required', 'string', 'unique:users,nip'],
                'department_id' => ['required', 'exists:departments,id'],
            ]);
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone_number = $request->input('phone_number');
        $user->password = Hash::make('LabUIN@2026'); // Default password
        $user->must_change_password = true;
        $user->created_by = Auth::id();

        if ($roles->contains('student')) {
            $user->nim = $request->input('nim');
            $user->entry_year = $request->input('entry_year');
            $user->supervisor_id = $request->input('supervisor_id');
        }
        
        if ($roles->contains('lecturer')) {
            $user->nip = $request->input('nip');
        }

        if ($request->filled('department_id')) {
            $user->department_id = $request->input('department_id');
        }

        if ($user->save()) {
            // Assign Roles
            $roleIds = Role::whereIn('slug', $roles)->pluck('id');
            $user->roles()->sync($roleIds);

            // Create Activity Log
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'create_user',
                'subject_type' => User::class,
                'subject_id' => $user->id,
                'description' => "Created new user: {$user->email}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->back()->with('success', 'Berhasil tambah pengguna!');
        }

        return redirect()->back()->with('error', 'Gagal Menambah Pengguna!');
    }

    public function usersUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,slug'],
        ]);

        // Self-protection: Admin cannot modify their own roles if they try to remove Admin Lab
        if (Auth::id() === $user->id && !in_array('admin_lab', $request->input('roles'))) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akses Admin Lab Anda sendiri.');
        }

        $roles = collect($request->input('roles'));

        // Enforce role dependency rules
        if ($roles->contains('admin_lab') && !$roles->contains('lecturer')) {
            $roles->push('lecturer');
        }
        if ($roles->contains('assistant') && !$roles->contains('student')) {
            $roles->push('student');
        }

        // Enforce mutual exclusion between Dosen/Admin and Mahasiswa/Asisten
        $hasStaff = $roles->contains('lecturer') || $roles->contains('admin_lab');
        $hasStudent = $roles->contains('student') || $roles->contains('assistant');

        if ($hasStaff && $hasStudent) {
            return redirect()->back()->withInput()->withErrors(['roles' => 'Pengguna tidak dapat menjadi Dosen/Admin dan Mahasiswa/Asisten secara bersamaan.']);
        }

        $user->name = $request->input('name');
        $user->phone_number = $request->input('phone_number');
        $user->updated_by = Auth::id();

        // Update specific fields safely
        if ($roles->contains('student')) {
            $user->nim = $request->input('nim', $user->nim);
            $user->entry_year = $request->input('entry_year', $user->entry_year);
            $user->supervisor_id = $request->input('supervisor_id', $user->supervisor_id);
        }
        
        if ($roles->contains('lecturer')) {
            $user->nip = $request->input('nip', $user->nip);
        }

        if ($request->filled('department_id')) {
            $user->department_id = $request->input('department_id');
        }

        $user->save();

        // Sync Roles
        $roleIds = Role::whereIn('slug', $roles)->pluck('id');
        $user->roles()->sync($roleIds);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update_user',
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'description' => "Updated user profile: {$user->email}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Berhasil perbarui data pengguna!');
    }

    public function deleteUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Enforce Policy strictly or manually here as backup
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $email = $user->email;
        $user->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete_user',
            'subject_type' => User::class,
            'subject_id' => $id,
            'description' => "Deleted user: {$email}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Berhasil hapus pengguna!');
    }

    public function ubahJaslab(Request $request, $id)
    {
        $jaslab = Jaslab::findOrFail($id);
        $jaslab->warna = $request->input('warna');
        $jaslab->save();
        return redirect()->back()->with('success', 'Berhasil perbarui warna jaslab!');
    }
}
