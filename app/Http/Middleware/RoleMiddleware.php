<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();

    if (!in_array($user->peran, $roles)) {
        abort(403, 'Anda tidak punya akses ke halaman ini');
    }

    return $next($request);
}

}
