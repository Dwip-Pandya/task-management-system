<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckModulePermission
{
    public function handle(Request $request, Closure $next, $module, $action)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        // Get the user's role permissions for the module
        $permission = DB::table('role_permissions')
            ->where('role_id', $user->role_id)
            ->where('module_name', $module)
            ->first();

        // If permission not found or not allowed
        if (!$permission || empty($permission->{'can_' . $action}) || $permission->{'can_' . $action} == 0) {
            return response()->view('errors.permission-denied', ['module' => $module, 'action' => $action], 403);
        }

        return $next($request);
    }
}
