<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with(['user', 'tenant'])->latest();

        if ($search = $request->input('search')) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('action', 'like', "%{$search}%");
        }

        if ($tenantId = $request->input('tenant_id')) {
            $query->where('tenant_id', $tenantId);
        }

        $logs = $query->paginate(30)->withQueryString();
        $tenants = \App\Models\Tenant::orderBy('name')->get(['id', 'name']);

        return view('super-admin.audit-logs.index', compact('logs', 'tenants'));
    }
}
