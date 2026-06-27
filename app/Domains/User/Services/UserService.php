<?php

namespace App\Domains\User\Services;

use App\Domains\User\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function createUser(array $data, int $creatorId): User
    {
        $roles = collect($data['roles'] ?? []);
        $this->enforceRoleRules($roles);

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone_number = $data['phone_number'] ?? null;
        $user->password = Hash::make('LabUIN@2026'); // Default password
        $user->must_change_password = true;
        $user->created_by = $creatorId;

        $this->mapSpecificRoleFields($user, $data, $roles);

        $user->save();
        $this->syncRoles($user, $roles);
        
        $this->logActivity('create_user', $user->id, "Created new user: {$user->email}", $creatorId);

        return $user;
    }

    public function updateUser(User $user, array $data, int $updaterId): User
    {
        $roles = collect($data['roles'] ?? []);
        
        // Self-protection: Admin cannot modify their own roles if they try to remove Admin Lab
        if ($updaterId === $user->id && !$roles->contains('admin_lab')) {
            throw ValidationException::withMessages([
                'roles' => 'Anda tidak dapat menghapus akses Admin Lab Anda sendiri.'
            ]);
        }

        $this->enforceRoleRules($roles);

        $user->name = $data['name'];
        $user->phone_number = $data['phone_number'] ?? null;
        $user->updated_by = $updaterId;

        $this->mapSpecificRoleFields($user, $data, $roles, true);

        $user->save();
        $this->syncRoles($user, $roles);

        $this->logActivity('update_user', $user->id, "Updated user profile: {$user->email}", $updaterId);

        return $user;
    }

    public function deleteUser(User $user, int $deleterId): void
    {
        if ($deleterId === $user->id) {
            throw ValidationException::withMessages([
                'delete' => 'Anda tidak dapat menghapus akun Anda sendiri.'
            ]);
        }

        $email = $user->email;
        $id = $user->id;
        $user->delete();

        $this->logActivity('delete_user', $id, "Deleted user: {$email}", $deleterId);
    }

    private function enforceRoleRules(Collection $roles): void
    {
        // Enforce role dependency rules
        if ($roles->contains('admin_lab') && !$roles->contains('lecturer')) {
            $roles->push('lecturer');
        }
        if ($roles->contains('assistant') && !$roles->contains('student')) {
            $roles->push('student');
        }

        // Enforce mutual exclusion
        $hasStaff = $roles->contains('lecturer') || $roles->contains('admin_lab');
        $hasStudent = $roles->contains('student') || $roles->contains('assistant');

        if ($hasStaff && $hasStudent) {
            throw ValidationException::withMessages([
                'roles' => 'Pengguna tidak dapat menjadi Dosen/Admin dan Mahasiswa/Asisten secara bersamaan.'
            ]);
        }
    }

    private function mapSpecificRoleFields(User $user, array $data, Collection $roles, bool $isUpdate = false): void
    {
        if ($roles->contains('student')) {
            $user->nim = $data['nim'] ?? ($isUpdate ? $user->nim : null);
            $user->entry_year = $data['entry_year'] ?? ($isUpdate ? $user->entry_year : null);
            $user->supervisor_id = $data['supervisor_id'] ?? ($isUpdate ? $user->supervisor_id : null);
        }
        
        if ($roles->contains('lecturer')) {
            $user->nip = $data['nip'] ?? ($isUpdate ? $user->nip : null);
        }

        if (!empty($data['department_id'])) {
            $user->department_id = $data['department_id'];
        }
    }

    private function syncRoles(User $user, Collection $roles): void
    {
        $roleIds = Role::whereIn('slug', $roles)->pluck('id');
        $user->roles()->sync($roleIds);
    }

    private function logActivity(string $action, int $subjectId, string $description, int $userId): void
    {
        ActivityLog::create([
            'user_id' => $userId,
            'action' => $action,
            'subject_type' => User::class,
            'subject_id' => $subjectId,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
