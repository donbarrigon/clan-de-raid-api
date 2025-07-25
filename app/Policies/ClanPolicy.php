<?php

namespace App\Policies;

use App\Models\Clan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view clan');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Clan $clan): bool
    {
        return $user->hasPermissionTo('view clan') || $clan->leader->id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create clan');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Clan $clan): bool
    {
        return $user->hasPermissionTo('update clan') || $clan->leader->id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Clan $clan): bool
    {
        return $user->hasPermissionTo('delete clan');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Clan $clan): bool
    {
        return $user->hasPermissionTo('delete clan');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Clan $clan): bool
    {
        return $user->hasPermissionTo('force-delete clan');
    }
}
