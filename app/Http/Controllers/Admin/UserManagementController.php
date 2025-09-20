<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');

        $users = User::query();

        if ($search) {
            $users->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $users->get();

        return view('admin.users.index', compact('users', 'user', 'search'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        return view('admin.users.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|max:100',
            'email'    => 'required|email|unique:tbl_user,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,user',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        $editUser = User::findOrFail($id);
        return view('admin.users.edit', compact('editUser', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $editUser = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|max:100',
            'email' => 'required|email|unique:tbl_user,email,' . $editUser->user_id . ',user_id',
            'role'  => 'required|in:admin,user',
        ]);

        $editUser->name  = $request->name;
        $editUser->email = $request->email;
        $editUser->role  = $request->role;

        if ($request->filled('password')) {
            $editUser->password = Hash::make($request->password);
        }

        $editUser->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $editUser = User::findOrFail($id);
        $editUser->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
