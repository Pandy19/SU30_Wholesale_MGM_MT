<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // ALWAYS allow access for Owners
        if ($user->role === 'owner') {
            return $next($request);
        }
        
        // If no roles specified, allow access (already passed auth)
        if (empty($roles)) {
            return $next($request);
        }

        // Check if user has any of the required roles
        // We use the 'role' column from users table (which is the slug)
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Unauthorized access
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return redirect()->route('dashboard.index')->with('error', 'You do not have permission to access this page.');
    }
}
