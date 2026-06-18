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

class UsersController extends Controller
{
    public function usersView(Request $request)
    {
        $keyword = $request->input('keyword');
        $roleSlug = $request->input('jabatan'); // Kept variable name for backwards compatibility in UI

        $query = User::with('roles', 'department');

        $query->when($keyword, function ($q) use ($keyword) {
            $q->where(function ($sub) use ($keyword) {
                $sub->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('email', 'LIKE', "%{$keyword}%")
                    ->orWhere('nim', 'LIKE', "%{$keyword}%")
                    ->orWhere('nip', 'LIKE', "%{$keyword}%");
            });
        });

        $query->when($roleSlug, function ($q) use ($roleSlug) {
            $q->whereHas('roles', function ($qRole) use ($roleSlug) {
                $qRole->where('slug', $roleSlug);
            });
        });

        $users = $query->latest()->paginate(10)->withQueryString();
        $roles = Role::all();

        return view('admin.users', compact('users', 'roles'));
    }

    public function jaslabView()
    {
        $jaslab = Jaslab::all();
        return view('admin.jaslab', compact('jaslab'));
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
