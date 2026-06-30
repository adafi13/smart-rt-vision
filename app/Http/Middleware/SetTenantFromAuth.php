<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantFromAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Akun super admin tidak terikat tenant manapun — jangan biarkan masuk
        // ke area admin RT biasa, karena tanpa tenant_id terikat, query model
        // yang ber-scope tenant tidak terfilter sama sekali (bisa lihat semua RT).
        if ($user && ! $user->tenant_id) {
            if ($user->is_super_admin) {
                return redirect()->route('super-admin.index');
            }
            if ($user->isAdminRw()) {
                return redirect()->route('rw.dashboard');
            }
            abort(403, 'Akun ini tidak terhubung ke RT manapun.');
        }

        if ($user && $user->tenant_id) {
            $tenant = $user->tenant;
            
            if ($tenant->status === 'suspended') {
                return redirect()->route('suspended');
            }

            // Izinkan akses ke route expired, billing, dan proses logout/impersonation.
            $allowedRoutes = [
                'expired', 'super-admin.leave-impersonation', 'logout',
                'billing.index', 'billing.checkout', 'billing.success', 'billing.cancel'
            ];
            $currentRoute = request()->route() ? request()->route()->getName() : null;

            if (!$tenant->isActive() && !in_array($currentRoute, $allowedRoutes)) {
                return redirect()->route('expired');
            }

            app()->instance('currentTenantId', $tenant->id);
            app()->instance('currentTenant', $tenant);
        }

        return $next($request);
    }
}
