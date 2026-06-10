<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Supports single or comma-separated roles: role:admin  or  role:admin,instructor
     */
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        // Flatten all provided roles (supports both variadic and comma-separated)
        $allowed = [];
        foreach ($roles as $r) {
            foreach (explode(',', $r) as $part) {
                $allowed[] = trim($part);
            }
        }

        if (Auth::check() && in_array(Auth::user()->role, $allowed, true)) {
            return $next($request);
        }

        abort(403);
    }
}