<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ForceChangePassword
{
    public function handle(Request $request, Closure $next)
    {
        $user = User::current();

        if ($user) {
            // Check if user is admin@gmail.com AND still using default password
            if ($user->email === 'admin@gmail.com' && Hash::check('admin123', $user->password)) {
                // Only set flag if not already on update-password route
                if (!$request->is('admin/update-password')) {
                    session(['force_password_change' => true]);
                }
            }
        }

        return $next($request);
    }
}
