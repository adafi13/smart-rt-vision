<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRtRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !$user->isAdminRt()) {
            return redirect('/');
        }

        // Owner has access to everything
        if ($user->isRtOwner()) {
            return $next($request);
        }

        // Check if user has any of the required roles
        if (in_array($user->tenant_role, $roles)) {
            return $next($request);
        }

        abort(403, 'Akses ditolak. Anda tidak memiliki izin (Role) untuk mengakses halaman ini.');
    }
}
