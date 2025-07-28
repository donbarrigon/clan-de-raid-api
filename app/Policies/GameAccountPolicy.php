<?php

namespace App\Policies;

use App\Models\GameAccount;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GameAccountPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view game-account');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GameAccount $gameAccount): bool
    {
        return $user->hasPermissionTo('view game-account');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, GameAccount $gameAccount): bool
    {
        return $user->hasPermissionTo('create game-account') || $gameAccount->user_id === $user->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GameAccount $gameAccount): bool
    {
        return $user->hasPermissionTo('update game-account') || $gameAccount->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GameAccount $gameAccount): bool
    {
        return $user->hasPermissionTo('delete game-account') || $gameAccount->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GameAccount $gameAccount): bool
    {
        return $user->hasPermissionTo('delete game-account');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GameAccount $gameAccount): bool
    {
        return $user->hasPermissionTo('delete game-account');
    }
}
