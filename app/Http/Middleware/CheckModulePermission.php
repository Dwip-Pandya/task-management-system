<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class CheckModulePermission
{
    public function handle(Request $request, Closure $next, $module, $action)
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();

        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        // First check if the user has custom (user-specific) permissions
        $permission = DB::table('role_permissions')
            ->where('user_id', $user->id)
            ->where('module_name', $module)
            ->first();

        // If not, fallback to the role-based default permissions
        if (!$permission) {
            $permission = DB::table('role_permissions')
                ->where('role_id', $user->role_id)
                ->whereNull('user_id')
                ->where('module_name', $module)
                ->first();
        }

        // If no permission found or the specific action (e.g., can_view) is 0 or null → deny
        if (
            !$permission ||
            empty($permission->{'can_' . $action}) ||
            $permission->{'can_' . $action} == 0
        ) {
            return response()->view('errors.permission-denied', [
                'module' => $module,
                'action' => $action
            ], 403);
        }

        return $next($request);
    }
}
