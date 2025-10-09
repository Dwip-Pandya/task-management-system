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
        $user = Auth::user();
        $search = $request->input('search');

        $users = User::with('role')
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%"))
            ->get();

        $deletedUsers = User::onlyTrashed()->with('role')->get();

        return view('admin.users.index', compact('users', 'user', 'search', 'deletedUsers'));
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
        $user = Auth::user();
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

    // Bulk delete
    public function bulkDelete(Request $request)
    {
        $user = Auth::user();
        $ids = $request->input('user_ids', []);

        if (in_array($user->id, $ids)) {
            return redirect()->route('users.index')->with('error', 'You cannot delete yourself.');
        }

        User::whereIn('id', $ids)->delete(); // Soft delete
        return redirect()->route('users.index')->with('success', 'Selected users deleted successfully.');
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
}
