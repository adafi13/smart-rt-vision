<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        // Get logs for the current tenant's staff (BelongsToTenant handles scoping usually, but let's be explicit just in case)
        $tenantId = auth()->user()->tenant_id;
        
        $logs = AuditLog::with('user')
            ->where('tenant_id', $tenantId)
            ->latest()
            ->paginate(20);
            
        return view('admin.audit_logs.index', compact('logs'));
    }
}
