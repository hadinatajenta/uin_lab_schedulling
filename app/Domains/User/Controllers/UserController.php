<?php

namespace App\Domains\User\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\User\Models\User;
use App\Domains\User\Repositories\UserRepositoryInterface;
use App\Domains\User\Services\UserService;
use App\Domains\Role\Models\Role;
use App\Domains\Department\Models\Department;
use App\Domains\ActivityLog\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    protected UserRepositoryInterface $userRepository;
    protected UserService $userService;

    public function __construct(UserRepositoryInterface $userRepository, UserService $userService)
    {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['name', 'email', 'nim', 'nip', 'department_id', 'faculty', 'keyword']);
        $filters['roleSlug'] = $request->input('jabatan');
        
        $perPage = $request->input('per_page', 10);
        $users = $this->userRepository->getPaginatedUsers($filters, $perPage);
        
        $roles = Role::where('slug', '!=', 'super_admin')->get();
        $allDepartments = Department::all(['id', 'name', 'faculty']);
        $faculties = Department::select('faculty')->whereNotNull('faculty')->distinct()->pluck('faculty');

        if ($request->ajax()) {
            return view('users.partials.table', compact('users', 'roles', 'allDepartments', 'faculties'))->render();
        }

        return view('users.index', compact('users', 'roles', 'allDepartments', 'faculties'));
    }

    public function show(int $id)
    {
        $user = $this->userRepository->findById($id, ['roles', 'department']);
        
        if ($user->roles->contains('slug', 'super_admin')) {
            abort(403, 'Unauthorized access to Super Admin data.');
        }

        $roles = Role::where('slug', '!=', 'super_admin')->get();
        $allDepartments = Department::all(['id', 'name', 'faculty']);
        
        $activities = ActivityLog::where('user_id', $id)
                        ->orWhere(function($query) use ($id) {
                            $query->where('subject_type', User::class)
                                  ->where('subject_id', $id);
                        })
                        ->latest()
                        ->paginate(15);
                        
        return view('users.show', compact('user', 'roles', 'allDepartments', 'activities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['email', 'required', 'unique:users,email'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,slug'],
        ]);

        // Specific rules
        if (in_array('student', $request->input('roles', []))) {
            $request->validate([
                'nim' => ['required', 'string', 'unique:users,nim'],
                'department_id' => ['required', 'exists:departments,id'],
                'entry_year' => ['required', 'integer'],
            ]);
        }
        if (in_array('lecturer', $request->input('roles', []))) {
            $request->validate([
                'nip' => ['required', 'string', 'unique:users,nip'],
                'department_id' => ['required', 'exists:departments,id'],
            ]);
        }

        try {
            $this->userService->createUser($request->all(), Auth::id());
            return redirect()->back()->with('success', 'Berhasil tambah pengguna!');
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal Menambah Pengguna! ' . $e->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        $user = $this->userRepository->findById($id, ['roles']);
        
        if ($user->roles->contains('slug', 'super_admin')) {
            abort(403, 'Cannot modify Super Admin data.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,slug'],
        ]);

        try {
            $this->userService->updateUser($user, $request->all(), Auth::id());
            return redirect()->back()->with('success', 'Berhasil perbarui data pengguna!');
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal Memperbarui Pengguna! ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        $user = $this->userRepository->findById($id, ['roles']);
        
        if ($user->roles->contains('slug', 'super_admin')) {
            abort(403, 'Cannot delete Super Admin.');
        }

        try {
            $this->userService->deleteUser($user, Auth::id());
            return redirect()->route('users.index')->with('success', 'Berhasil hapus pengguna!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Menghapus Pengguna! ' . $e->getMessage());
        }
    }
}
