<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Scopes\TenantScope;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        // withoutGlobalScope ensures SuperAdmin sees ALL tenants' logs
        $query = AuditLog::withoutGlobalScope(TenantScope::class)
            ->with(['user', 'tenant'])
            ->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        if ($tenantId = $request->input('tenant_id')) {
            $query->where('tenant_id', $tenantId);
        }

        if ($action = $request->input('action')) {
            $query->where('action', $action);
        }

        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $logs    = $query->paginate(50)->withQueryString();
        $tenants = \App\Models\Tenant::orderBy('name')->get(['id', 'name']);

        // Collect all unique actions for the filter dropdown
        $actions = AuditLog::withoutGlobalScope(TenantScope::class)
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        // Stats
        $totalToday = AuditLog::withoutGlobalScope(TenantScope::class)
            ->whereDate('created_at', today())->count();
        $totalMonth = AuditLog::withoutGlobalScope(TenantScope::class)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();
        $totalAll = AuditLog::withoutGlobalScope(TenantScope::class)->count();

        return view('super-admin.audit-logs.index', compact(
            'logs', 'tenants', 'actions', 'totalToday', 'totalMonth', 'totalAll'
        ));
    }
}
