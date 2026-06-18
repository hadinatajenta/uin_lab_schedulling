<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can manage another user's roles.
     */
    public function manageRoles(User $authUser, User $targetUser): bool
    {
        // Users cannot modify their own roles.
        // Admin Lab can modify others' roles.
        return $authUser->id !== $targetUser->id && $authUser->hasRole('admin_lab');
    }

    /**
     * Determine whether the user can disable another user.
     */
    public function disable(User $authUser, User $targetUser): bool
    {
        return $authUser->id !== $targetUser->id && $authUser->hasRole('admin_lab');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $authUser, User $targetUser): bool
    {
        return $authUser->id !== $targetUser->id && $authUser->hasRole('admin_lab');
    }
}
