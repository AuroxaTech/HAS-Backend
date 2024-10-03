<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    
    public function handle($request, Closure $next, $role_id)
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == $role_id) {
                return $next($request);
            }
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
