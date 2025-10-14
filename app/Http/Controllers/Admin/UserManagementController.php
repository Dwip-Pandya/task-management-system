<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserManagementController extends Controller
{
    // Display all users
    public function index(Request $request)
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();

        $search = $request->input('search');

        $users = User::withTrashed()
            ->with('role')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                });
            })
            ->orderBy('deleted_at') // active first
            ->get();

        return view('admin.users.index', compact('users', 'user', 'search'));
    }

    // Show create form
    public function create()
    {
        $user = Auth::user();
        $roles = Role::all();
        return view('admin.users.create', compact('user', 'roles'));
    }

    // Store new user
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'     => 'required|max:100',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'role_id'  => 'required|exists:roles,id',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $request->role_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();
        $editUser = User::findOrFail($id);
        $roles = Role::all();

        return view('admin.users.edit', compact('editUser', 'user', 'roles'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        $editUser = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|min:3|max:100',
            'email'    => 'required|email',
            'role_id'  => 'required|exists:roles,id',
        ]);

        $editUser->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'role_id'  => $request->role_id,
            'password' => $request->filled('password') ? Hash::make($request->password) : $editUser->password,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // Soft delete user
    public function destroy($id)
    {
        $user = Auth::user();
        $deleteUser = User::findOrFail($id);

        if ($deleteUser->id == $user->id) {
            return redirect()->route('users.index')->with('error', 'You cannot delete yourself.');
        }

        $deleteUser->delete(); // Soft delete
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    // Restore soft deleted user
    public function restore($id)
    {
        $deletedUser = User::onlyTrashed()->findOrFail($id);
        $deletedUser->restore();

        return redirect()->route('users.index')->with('success', 'User restored successfully.');
    }
    // Force update password for default admin
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::findOrFail(Auth::id());
        $user->password = Hash::make($request->password);
        $user->save();

        session()->forget('force_password_change');

        return redirect()->back()->with('success', 'Password updated successfully!');
    }
    public function bulkDelete(Request $request)
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();

        $ids = $request->input('user_ids', []);

        if (empty($ids)) {
            return redirect()->route('users.index')->with('error', 'No users selected for deletion.');
        }

        // Prevent deleting yourself
        if (in_array($user->id, $ids)) {
            return redirect()->route('users.index')->with('error', 'You cannot delete yourself.');
        }

        // Soft delete selected users
        User::whereIn('id', $ids)->delete();

        return redirect()->route('users.index')->with('success', 'Selected users deleted successfully.');
    }
    // Switch to user account
    public function switchToUser($id)
    {
        $targetUser = User::findOrFail($id);

        // current admin id to switch back
        if (!session()->has('admin_id')) {
            session(['admin_id' => Auth::id()]);
        }

        Auth::login($targetUser);

        // Regenerate session to avoid issues
        request()->session()->regenerate();

        // Redirect based on role
        $roleName = strtolower($targetUser->role->name ?? 'user');

        switch ($roleName) {
            case 'admin':
                return redirect()->route('admin.dashboard')->with('success', 'Switched to user account.');
            case 'project manager':
                return redirect()->route('projectmanager.dashboard')->with('success', 'Switched to user account.');
            case 'project member':
                return redirect()->route('projectmember.dashboard')->with('success', 'Switched to user account.');
            default:
                return redirect()->route('user.dashboard')->with('success', 'Switched to user account.');
        }
    }

    // Switch back to original admin
    public function switchBack()
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('users.index')->with('error', 'No admin session found.');
        }

        $adminId = session('admin_id');
        $admin = User::findOrFail($adminId);

        Auth::login($admin);

        // Clear the admin session
        session()->forget('admin_id');

        // Regenerate session
        request()->session()->regenerate();

        return redirect()->route('users.index')->with('success', 'Returned to your admin account.');
    }
}
