<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use Illuminate\Validation\ValidationException;

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
        try {
            $request->validate([
                'name'     => 'required|string|max:100',
                'email'    => 'required|email|max:150|unique:users,email',
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
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            $user = User::withTrashed()->where('email', $request->email)->first();

            if (!$user) {
                return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
            }

            if ($user->trashed()) {
                return back()->withErrors(['email' => 'This account has been deactivated.'])->withInput();
            }

            if (Hash::check($request->password, $user->password)) {
                Auth::login($user);

                $roleName = strtolower($user->role->name ?? 'user');

                switch ($roleName) {
                    case 'admin':
                        return redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!');
                    case 'project manager':
                        return redirect()->route('projectmanager.dashboard')->with('success', 'Welcome Project Manager!');
                    case 'project member':
                        return redirect()->route('projectmember.dashboard')->with('success', 'Welcome Project Member!');
                    default:
                        return redirect()->route('user.dashboard')->with('success', 'Welcome User!');
                }
            }

            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            throw $e;
        }
    }


    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
