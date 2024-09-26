<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsApprovedSP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Assuming you have an 'approved' column in your users table
        // Adjust the column name based on your actual database schema
        if (auth()->check() && auth()->user()->role_id == 3 && is_null(auth()->user()->approved_at)) {
            return response()->json([
                "status" => "Unauthorized",
                "user" =>  auth()->user(),
                'message' => 'Your profile is not approved.'
            ], 403);
        }
        return $next($request);
    }
}
