<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class CheckDeactivatedUser
{
    public function handle(Request $request, Closure $next)
    {
        // Get logged in user ID from session
        $userId = $request->session()->get('user_id');

        if (!$userId) {
            // No user logged in, continue request
            return $next($request);
        }

        // Fetch user including soft-deleted, with role
        $user = User::with(['role'])->withTrashed()->find($userId);

        if (!$user) {
            // User doesn't exist (maybe deleted), clear session
            $request->session()->forget('user_id');
            return redirect('/login');
        }

        // Check if the user is soft-deleted
        $isDeactivated = $user->deleted_at !== null;

        // Share variables globally for all views
        view()->share([
            'current_user' => $user,
            'role' => strtolower($user->role->name ?? 'user'),
            'is_deactivated' => $isDeactivated,
        ]);

        // Update session
        $request->session()->put('is_deactivated', $isDeactivated);

        return $next($request);
    }
}
