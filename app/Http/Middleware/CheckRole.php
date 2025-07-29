<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle($request, Closure $next, ...$roles)
{
    $user = Auth::user();
    $userRole = $user->role ?? null;

    if (!in_array($userRole, $roles)) {
        abort(403, 'Unauthorized.');
    }

    return $next($request);
}

}
