<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // cek login
        if (!Auth::check()) {
            return redirect('/login'); // redirect ke login kalau belum login
        }

        $user = Auth::user();

        // ubah semua role numeric ke integer, role string tetap
        $rolesNormalized = array_map(function ($r) {
            return is_numeric($r) ? (int)$r : $r;
        }, $roles);

        $allowed = false;

        foreach ($rolesNormalized as $role) {
            // support role_id atau role_name
            if (is_int($role) && $user->role_id === $role) {
                $allowed = true;
                break;
            } elseif (is_string($role) && strtolower($user->role->name) === strtolower($role)) {
                $allowed = true;
                break;
            }
        }

        if (! $allowed) {
            abort(403, 'Unauthorized action for your role.');
        }

        return $next($request);
    }
}