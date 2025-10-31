<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\User;

class PermissionService
{
    /**
     * Check if current user has a specific permission for a module.
     *
     * @param string $moduleName   The module name (e.g. 'calendar viewing', 'task management')
     * @param string $action       The action (e.g. 'view', 'add', 'edit', 'delete')
     * @return bool
     */
    public function hasPermission(string $moduleName, string $action): bool
    {
        $user = User::current();

        if (!$user) return false;

        // Check user-specific permissions
        $permission = DB::table('role_permissions')
            ->where('user_id', $user->id)
            ->where('module_name', $moduleName)
            ->first();

        // Fallback to role-based default
        if (!$permission) {
            $permission = DB::table('role_permissions')
                ->where('role_id', $user->role_id)
                ->whereNull('user_id')
                ->where('module_name', $moduleName)
                ->first();
        }

        // No permission found at all
        if (!$permission) return false;

        $field = 'can_' . $action;

        // If the column doesn’t exist, assume unrestricted access
        if (!property_exists($permission, $field)) {
            return true;
        }

        // Return actual permission flag
        return $permission->$field == 1;
    }

    /**
     * Get all CRUD permissions (view/add/edit/delete) for the current user for a given module.
     *
     * @param string $moduleName   The module name (e.g. 'calendar viewing', 'task management')
     * @return array
     */
    public function getAllPermissions(string $moduleName): array
    {
        $user = User::current();

        // Default permission set
        $default = [
            'can_view' => false,
            'can_add' => false,
            'can_edit' => false,
            'can_delete' => false,
        ];

        if (!$user) return $default;

        // Try user-specific permissions first
        $perm = DB::table('role_permissions')
            ->where('user_id', $user->id)
            ->where('module_name', $moduleName)
            ->first();

        // Fallback to role-based defaults
        if (!$perm) {
            $perm = DB::table('role_permissions')
                ->where('role_id', $user->role_id)
                ->whereNull('user_id')
                ->where('module_name', $moduleName)
                ->first();
        }

        // Merge results with defaults (null-safe)
        return [
            'can_view' => $perm->can_view ?? false,
            'can_add' => $perm->can_add ?? false,
            'can_edit' => $perm->can_edit ?? false,
            'can_delete' => $perm->can_delete ?? false,
        ];
    }
}
