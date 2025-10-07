<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    // Redirect to Google
    public function googlelogin()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle Google callback
    public function googleauthentication()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Step 1: Check if BOTH google_id and email exist together
            $user = User::withTrashed()
                ->where('google_id', $googleUser->getId())
                ->where('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Restore if soft deleted
                if ($user->trashed()) {
                    $user->restore();
                }

                // Login and set session
                Auth::login($user);
                session(['user' => $user]);

                return $user->role_id === 1
                    ? redirect()->route('admin.dashboard')
                    : redirect()->route('user.dashboard');
            }

            // Step 2: Create new user
            $newUser = User::create([
                'name'      => $googleUser->getName(),
                'email'     => $googleUser->getEmail(),
                'password'  => bcrypt('password@123'),
                'google_id' => $googleUser->getId(),
                'role_id'   => 2,
            ]);

            Auth::login($newUser);
            session(['user' => $newUser]);
            return redirect()->route('user.dashboard');
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }
}
