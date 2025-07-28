<?php

namespace App\Http\Controllers;

use App\Models\GameAccount;
use App\Http\Requests\StoreGameAccountRequest;
use App\Http\Requests\UpdateGameAccountRequest;
use Illuminate\Support\Facades\Gate;

class GameAccountController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGameAccountRequest $request)
    {
        $gameAccount = new GameAccount($request->validated());
        Gate::authorize('create', $gameAccount);
        $gameAccount->save();
        return $gameAccount;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGameAccountRequest $request, GameAccount $gameAccount)
    {
        Gate::authorize('update', $gameAccount);
        $gameAccount->update($request->validated());
        return $gameAccount;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GameAccount $gameAccount)
    {
        Gate::authorize('delete', $gameAccount);
        $gameAccount->delete();
        return response()->noContent();
    }

    public function restore(string $id)
    {
        $gameAccount = GameAccount::withTrashed()->findOrFail($id);
        Gate::authorize('restore', $gameAccount);
        $gameAccount->restore();
        return $gameAccount;
    }

    public function forceDelete(string $id)
    {
        $gameAccount = GameAccount::withTrashed()->findOrFail($id);
        Gate::authorize('forceDelete', $gameAccount);
        $gameAccount->forceDelete();
        return response()->noContent();
    }
}
