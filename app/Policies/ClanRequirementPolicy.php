<?php

namespace App\Policies;

use App\Models\ClanRequirement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClanRequirementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClanRequirement $clanRequirement): bool
    {
        return true;
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
    public function update(User $user, ClanRequirement $clanRequirement): bool
    {
        $clan = optional($clanRequirement->clan);
        return $user->hasPermissionTo('update clan') || $clan->leader->id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClanRequirement $clanRequirement): bool
    {
        return $user->hasPermissionTo('delete clan');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ClanRequirement $clanRequirement): bool
    {
        return $user->hasPermissionTo('delete clan');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ClanRequirement $clanRequirement): bool
    {
        return $user->hasPermissionTo('delete clan');
    }
}
