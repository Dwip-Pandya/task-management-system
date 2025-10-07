<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;

class AuthController extends Controller
{
    // Show register form
    public function showRegister()
    {
        $roles = Role::all();
        return view('auth.register', compact('roles'));
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:150|unique:tbl_user,email',
            'password' => 'required|min:6|confirmed',
            'role_id'  => 'required|exists:roles,id',
        ]);

        $user = new User();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->password = Hash::make($request->password);
        $user->role_id  = $request->role_id;
        $user->save();

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Find user by email, including only non-deleted users
        $user = User::withTrashed()->where('email', $request->email)->first();

        if (!$user) {
            // No such user
            return back()->withErrors(['email' => 'Invalid credentials.']);
        }

        if ($user->trashed()) {
            // User is soft-deleted
            return back()->withErrors(['email' => 'This account has been deactivated.']);
        }

        // Check password only if user is not soft-deleted
        if (Hash::check($request->password, $user->password)) {
            Auth::login($user);

            if ($user->role->name === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!');
            } else {
                return redirect()->route('user.dashboard')->with('success', 'Welcome User!');
            }
        }

        // Wrong password
        return back()->withErrors(['email' => 'Invalid credentials.']);
    }


    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
