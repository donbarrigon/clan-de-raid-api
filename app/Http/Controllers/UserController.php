<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserSummaryResource;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Profile::class);
        return UserSummaryResource::collection(
            User::with('profile')->cursorPaginate()
        );
    }

    public function singup(StoreUserRequest $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        $profile = new Profile();
        $profile->fill($request->validated());
        $profile->user_id = $user->id;
        $profile->save();

        $user->sendEmailVerificationNotification();

        $user->load(['profile']);

        return UserResource::make($user);
    }

    public function show(User $user)
    {
        Gate::authorize('view', $user);
        return UserResource::make($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        Gate::authorize('update', $user);
        $user->update($request->validated());
        $profile = $user->profile ?? new Profile();
        $profile->fill(array_merge(
            $request->validated(),
            ['user_id' => $user->id]
        ));
        $profile->save();
        $user->load(['profile']);
        return UserResource::make($user);
    }

    public function updatePassword(UpdatePasswordRequest $request, User $user)
    {
        Gate::authorize('updatePassword', $user);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json([
                'message' => 'The current password is incorrect.',
            ], 422);
        }

        // $user->update([
        //     'password' => Hash::make($request->input('password')),
        // ]);
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json([
            'message' => 'Password updated successfully.',
        ]);
    }

    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully.']);
    }

    public function restore(string $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        Gate::authorize('restore', $user);
        $user->restore();
        return UserResource::make($user);
    }

    public function forceDelete(string $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        Gate::authorize('forceDelete', $user);
        $user->forceDelete();
        return response()->json(['message' => 'User permanently deleted.']);
    }
}
