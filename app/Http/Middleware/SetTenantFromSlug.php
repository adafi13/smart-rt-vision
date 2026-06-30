<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetTenantFromSlug
{
    public function handle(Request $request, Closure $next): Response
    {
        $routeTenant = $request->route('tenant');

        $tenant = $routeTenant instanceof Tenant
            ? $routeTenant
            : Tenant::where('slug', $routeTenant)->first();

        abort_if(! $tenant, 404);

        app()->instance('currentTenantId', $tenant->id);
        app()->instance('currentTenant', $tenant);
        
        if ($tenant->rw_id) {
            app()->instance('currentRwId', $tenant->rw_id);
            app()->instance('currentRw', $tenant->rw);
        }

        $request->route()->setParameter('tenant', $tenant);
        URL::defaults(['tenant' => $tenant->slug]);

        return $next($request);
    }
}
