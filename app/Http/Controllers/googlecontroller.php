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

            // Check if user already exists by Google ID
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Update Google ID if missing
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                    $user->save();
                }

                Auth::login($user);
                session(['user' => $user]); // optional

                return $user->role === 'admin'
                    ? redirect()->route('admin.dashboard')
                    : redirect()->route('user.dashboard');
            } else {
                // New user
                $newUser = User::create([
                    'name'      => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'password'  => encrypt('password@123'), // default password
                    'google_id' => $googleUser->getId(),
                    'role'      => 'user' // default role
                ]);

                Auth::login($newUser);
                session(['user' => $newUser]); // optional

                return redirect()->route('user.dashboard');
            }
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }
}
