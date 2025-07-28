<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUserEmailRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserSummaryResource;
use App\Models\EmailChange;
use App\Models\User;
use App\Notifications\ConfirmEmailChange;
use App\Notifications\PasswordRestored;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;

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

    public function showGameAccounts(User $user)
    {
        Gate::authorize('view', $user);
        return $user->gameAccounts;
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

    public function resetPassword(User $user)
    {
        Gate::authorize('restorePassword', $user);

        $prefixes = ['8=D~', '8==D~', '8===D~', 'B=D~', 'B==D~', 'B===D~', '8=>~', '8==>~', '8===>~', 'B=>~', 'B==>~', 'B===>~', ];
        $prefix = $prefixes[array_rand($prefixes)];
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomPart = '';
        for ($i = 0; $i < 5; $i++) {
            $randomPart .= $characters[random_int(0, strlen($characters) - 1)];
        }
        $newPassword = $prefix . $randomPart;

        $user->password = Hash::make($newPassword);
        $user->save();

        $user->notify(new PasswordRestored($newPassword));

        return response()->json([
            'message' => 'Password restored and email sent successfully.',
        ]);
    }

    public function updateEmail(UpdateUserEmailRequest $request, User $user)
    {
        Gate::authorize('updateEmail', $user);

        if (!Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'message' => 'The provided password is incorrect.'
            ], 403);
        }

        $newEmail = $request->input('email');

        // Generar token único
        $token = Uuid::uuid4()->toString();

        EmailChange::updateOrCreate(
            ['user_id' => $user->id],
            [
                'new_email' => $newEmail,
                'token' => $token,
                'expires_at' => now()->addHours(1),
            ]
        );

        // Crear link firmado
        $url = URL::temporarySignedRoute(
            'email.confirm-change',
            now()->addHours(1),
            [
                'user' => $user->id,
                'token' => $token,
            ]
        );

        // cambio el email pero no lo guardo para enviar la notificacion
        $user->email = $newEmail;
        $user->notify(new ConfirmEmailChange($url));

        return response()->json(['message' => 'Confirmation email sent to the new address.']);
    }

    // Route::get('/confirm-email-change/{user}/{token}', [UserController::class, 'confirmEmailChange'])
    // ->name('email.confirm-change')
    // ->middleware('signed');
    public function confirmEmailChange(User $user, string $token)
    {
       $record = EmailChange::where('user_id', $user->id)
            ->where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            abort(403, 'Invalid or expired token.');
        }

        $user->email = $record->new_email;
        $user->email_verified_at = now();
        $user->save();

        $record->delete();

        // respuesta en http para que se vea en el explorador. me da flogera hacer una vista
        return response('<h1>Email updated successfully</h1><p>Your email address has been changed successfully.</p>', 200)
            ->header('Content-Type', 'text/html');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $usuario = $request->input('usuario');
        $password = $request->input('password');

        // Determinar si es un email o un nombre de usuario
        $field = filter_var($usuario, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (!Auth::attempt([$field => $usuario, 'password' => $password])) {
            throw ValidationException::withMessages([
                'usuario' => __('auth.failed'),
            ]);
        }

        $user = Auth::user();

        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            throw ValidationException::withMessages([
                'usuario' => 'You must verify your email address before logging in.',
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);
        $user->delete();
        return response()->nocontent();
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
        return response()->nocontent();
    }
}
