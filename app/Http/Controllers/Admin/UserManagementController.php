<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{

    /**
     * Utility: Check if the logged-in user has permission for the User module.
     */
    private function hasPermission($action)
    {
        $user = User::current();

        if (!$user) return false;


        $permission = DB::table('role_permissions')
            ->where('user_id', $user->id)
            ->where('module_name', 'user management')
            ->first();

        if (!$permission) {
            $permission = DB::table('role_permissions')
                ->where('role_id', $user->role_id)
                ->whereNull('user_id')
                ->where('module_name', 'user management')
                ->first();
        }

        if (!$permission) return false;

        $field = 'can_' . $action;
        return $permission->$field == 1;
    }

    /**
     * Utility: Fetch all permissions for current user.
     */
    private function getAllPermissions()
    {
        $user = User::current();

        if (!$user) {
            return [
                'can_view' => false,
                'can_add' => false,
                'can_edit' => false,
                'can_delete' => false,
            ];
        }

        $perm = DB::table('role_permissions')
            ->where('user_id', $user->id)
            ->first();

        // Fallback to role defaults
        if (!$perm) {
            $perm = DB::table('role_permissions')
                ->where('role_id', $user->role_id)
                ->whereNull('user_id')
                ->first();
        }

        return [
            'can_view' => $perm->can_view ?? false,
            'can_add' => $perm->can_add ?? false,
            'can_edit' => $perm->can_edit ?? false,
            'can_delete' => $perm->can_delete ?? false,
        ];
    }


    // Display all users
    public function index(Request $request)
    {
        $user = User::current();

        $search = $request->input('search');

        $users = User::withTrashed()
            ->with('role')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                });
            })
            ->orderBy('deleted_at')
            ->get();

        // Modules
        $modules = [
            'user management',
            'project management',
            'task management',
            'report generation',
            'calendar viewing',
            'view chart analytics',
        ];

        // Fetch role permissions for all users
        $rolePermissions = DB::table('role_permissions')
            ->get()
            ->groupBy(function ($item) {
                return $item->user_id ?? 'role_' . $item->role_id;
            });


        return view('admin.users.index', compact('users', 'user', 'search', 'modules', 'rolePermissions'));
    }

    // Show create form
    public function create()
    {
        $user = User::current();

        $roles = Role::all();

        $permissions = $this->getAllPermissions();

        if (!$permissions['can_add']) {
            return redirect()->route('users.index')
                ->with('error', 'You do not have permission to create a user.');
        }

        return view('admin.users.create', compact('user', 'roles', 'permissions'));
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
        $user = User::current();

        $editUser = User::findOrFail($id);
        $roles = Role::all();

        $permissions = $this->getAllPermissions();

        if (!$permissions['can_edit']) {
            return redirect()->route('users.index')
                ->with('error', 'You do not have permission to edit a user.');
        }

        return view('admin.users.edit', compact('editUser', 'user', 'roles', 'permissions'));
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
        $user = User::current();

        $deleteUser = User::findOrFail($id);

        if (!$this->hasPermission('delete')) {
            return redirect()->route('users.index')
                ->with('error', 'You do not have permission to delete a user.');
        }

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
    // In UserManagementController.php

    public function bulkAction(Request $request)
    {
        $currentUser = User::withTrashed()->findOrFail(Auth::id());
        $ids = $request->input('user_ids', []);

        if (empty($ids)) {
            return redirect()->route('users.index')->with('error', 'No users selected for action.');
        }

        // Prevent self-action
        if (in_array($currentUser->id, $ids)) {
            return redirect()->route('users.index')->with('error', 'You cannot perform bulk action on yourself.');
        }

        // Separate active and deleted users
        $activeUsers = User::whereIn('id', $ids)->get();
        $deletedUsers = User::onlyTrashed()->whereIn('id', $ids)->get();

        $activeCount = $activeUsers->count();
        $deletedCount = $deletedUsers->count();

        // Case 1: Mixed selection → delete active and restore deleted
        if ($activeCount > 0 && $deletedCount > 0) {
            foreach ($activeUsers as $user) {
                $user->delete();
            }
            foreach ($deletedUsers as $user) {
                $user->restore();
            }
            $message = 'Bulk action completed successfully.';
        }
        // Case 2: Only deleted users selected → restore them
        elseif ($deletedCount > 0 && $activeCount === 0) {
            foreach ($deletedUsers as $user) {
                $user->restore();
            }
            $message = 'Selected deleted users restored successfully.';
        }
        // Case 3: Only active users selected → soft delete them
        elseif ($activeCount > 0 && $deletedCount === 0) {
            foreach ($activeUsers as $user) {
                $user->delete();
            }
            $message = 'Selected users deleted successfully.';
        }
        // Case 4: None found (shouldn’t happen normally)
        else {
            $message = 'No valid users found for action.';
        }

        return redirect()->route('users.index')->with('success', $message);
    }
    // Switch to user account
    public function switchToUser($id)
    {
        $targetUser = User::withTrashed()->findOrFail($id);

        // current admin id to switch back
        if (!session()->has('admin_id')) {
            session(['admin_id' => Auth::id()]);
        }

        Auth::login($targetUser);

        // Store user id in session for middleware
        session(['user_id' => $targetUser->id]);

        // Check if user is soft-deleted
        $isDeactivated = $targetUser->trashed();
        session(['is_deactivated' => $isDeactivated]);

        // Regenerate session to avoid issues
        request()->session()->regenerate();

        // Redirect based on role
        $roleName = strtolower($targetUser->role->name ?? 'user');

        switch ($roleName) {
            case 'admin':
                $route = 'admin.dashboard';
                break;
            case 'project manager':
                $route = 'projectmanager.dashboard';
                break;
            case 'project member':
                $route = 'projectmember.dashboard';
                break;
            default:
                $route = 'user.dashboard';
                break;
        }
        return redirect()->route($route)
            ->with('is_deactivated', $isDeactivated);
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

        // Clear all temporary session flags
        session()->forget(['admin_id', 'user_id', 'is_deactivated']);

        // Regenerate session 
        request()->session()->regenerate();

        return redirect()->route('admin.dashboard')->with('success', 'Returned to your admin account.');
    }

    public function rolePermissions(Request $request, $id)
    {
        $user = User::with('role')->findOrFail($id);

        // Submitted permissions from form
        $submittedPermissions = $request->input('permissions', []);

        // Fetch existing permissions for user's role
        foreach ($submittedPermissions as $moduleName => $data) {
            $updateData = [
                'can_view'   => isset($data['can_view']) ? 1 : 0,
                'can_add'    => isset($data['can_add']) ? 1 : 0,
                'can_edit'   => isset($data['can_edit']) ? 1 : 0,
                'can_delete' => isset($data['can_delete']) ? 1 : 0,
                'updated_at' => now(),
            ];

            // Try to fetch existing user-specific permission
            $perm = DB::table('role_permissions')
                ->where('user_id', $user->id)
                ->where('module_name', $moduleName)
                ->first();

            if ($perm) {
                // Update existing user-specific permission
                DB::table('role_permissions')
                    ->where('id', $perm->id)
                    ->update($updateData);
            } else {
                // Create new user-specific permission row
                DB::table('role_permissions')->insert(array_merge($updateData, [
                    'user_id' => $user->id,
                    'role_id' => null,
                    'module_name' => $moduleName,
                    'created_at' => now(),
                ]));
            }
        }

        return back()->with('success', 'Permissions updated successfully.');
    }
}
