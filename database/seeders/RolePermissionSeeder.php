<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch role IDs
        $roles = DB::table('roles')->pluck('id', 'name');

        // ---------------- Modules ----------------
        $modules = [
            'user management',
            'project management',
            'task management',
            'report generation',
            'calendar viewing',
            'view chart analytics'
        ];

        $rolePermissions = [];

        // --------- Admin ---------
        foreach ($modules as $module) {
            $rolePermissions[] = [
                'role_id' => $roles['admin'],
                'user_id' => null, // default role-level permission
                'module_name' => $module,
                'can_view' => 1,
                'can_add' => 1,
                'can_edit' => 1,
                'can_delete' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // --------- User ---------
        foreach ($modules as $module) {
            $rolePermissions[] = [
                'role_id' => $roles['user'],
                'user_id' => null,
                'module_name' => $module,
                'can_view' => ($module == 'task management' || $module == 'calendar viewing') ? 1 : 0,
                'can_add' => ($module == 'task management') ? 1 : 0,
                'can_edit' => ($module == 'task management') ? 1 : 0,
                'can_delete' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // --------- Project Manager ---------
        foreach ($modules as $module) {
            $rolePermissions[] = [
                'role_id' => $roles['project manager'],
                'user_id' => null,
                'module_name' => $module,
                'can_view' => ($module == 'user management') ? 0 : 1,
                'can_add' => ($module == 'user management') ? 0 : 1,
                'can_edit' => ($module == 'user management') ? 0 : 1,
                'can_delete' => ($module == 'user management') ? 0 : 1,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // --------- Project Member ---------
        foreach ($modules as $module) {
            $rolePermissions[] = [
                'role_id' => $roles['project member'],
                'user_id' => null,
                'module_name' => $module,
                'can_view' => ($module == 'task management' || $module == 'calendar viewing') ? 1 : 0,
                'can_add' => 0,
                'can_edit' => 0,
                'can_delete' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        DB::table('role_permissions')->insert($rolePermissions);
    }
}
