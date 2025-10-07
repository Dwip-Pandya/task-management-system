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

            // Step 1: Check if user exists by Google ID (including soft deleted)
            $userByGoogleId = User::withTrashed()
                ->where('google_id', $googleUser->getId())
                ->first();

            if ($userByGoogleId) {
                // If user was soft deleted, restore
                if ($userByGoogleId->trashed()) {
                    $userByGoogleId->restore();
                }

                Auth::login($userByGoogleId);
                session(['user' => $userByGoogleId]);

                return $userByGoogleId->role_id === 1
                    ? redirect()->route('admin.dashboard')
                    : redirect()->route('user.dashboard');
            }

            // Step 2: Check if user exists by email
            $userByEmail = User::withTrashed()
                ->where('email', $googleUser->getEmail())
                ->first();

            if ($userByEmail) {
                if ($userByEmail->trashed()) {
                    $userByEmail->restore();
                }

                // Assign Google ID to existing user
                $userByEmail->google_id = $googleUser->getId();
                $userByEmail->save();

                Auth::login($userByEmail);
                session(['user' => $userByEmail]);

                return $userByEmail->role_id === 1
                    ? redirect()->route('admin.dashboard')
                    : redirect()->route('user.dashboard');
            }

            // Step 3: Create new user
            $newUser = User::create([
                'name'      => $googleUser->getName(),
                'email'     => $googleUser->getEmail(),
                'password'  => bcrypt('password@123'),
                'google_id' => $googleUser->getId(),
                'role_id'   => 2, // default user
            ]);

            Auth::login($newUser);
            session(['user' => $newUser]);
            return redirect()->route('user.dashboard');
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }
}
