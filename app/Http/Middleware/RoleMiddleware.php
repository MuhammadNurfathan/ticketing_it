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
     * @param  string  $roles
     * @return mixed
     */
  public function handle(Request $request, Closure $next, ...$roles)
{
    if (!Auth::check()) {
        return redirect('/'); // belum login
    }

    $user = Auth::user();
    $rolesArray = array_map('intval', $roles); // semua param jadi integer

    if (!in_array((int)$user->role_id, $rolesArray)) {
    abort(403); // Laravel otomatis pakai resources/views/errors/403.blade.php
}


    return $next($request);
}

}
