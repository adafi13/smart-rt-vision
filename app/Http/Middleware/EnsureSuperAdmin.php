<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(auth()->check() && auth()->user()->is_super_admin, 403);

        $user = auth()->user();
        $route = $request->route()->getName();

        // Hanya Owner Super Admin yang boleh kelola Staff
        if (!$user->isSuperAdminOwner() && str_starts_with($route, 'super-admin.staff')) {
            abort(403, 'Hanya Owner yang diizinkan mengakses manajemen staff.');
        }

        // Support tidak boleh buka Billing & Pengaturan
        if ($user->isSuperAdminSupport() && (str_starts_with($route, 'super-admin.transactions') || str_starts_with($route, 'super-admin.settings') || str_starts_with($route, 'super-admin.plans'))) {
            abort(403, 'Role Support tidak diizinkan mengakses modul ini.');
        }

        // Finance tidak boleh buka Pengaturan Global
        if ($user->isSuperAdminFinance() && (str_starts_with($route, 'super-admin.settings') || str_starts_with($route, 'super-admin.audit-logs'))) {
            abort(403, 'Role Finance tidak diizinkan mengakses modul ini.');
        }

        return $next($request);
    }
}
