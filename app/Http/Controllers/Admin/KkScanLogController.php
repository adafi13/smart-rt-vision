<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KkScanLog;
use Illuminate\Http\Request;

class KkScanLogController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;
        
        $query = KkScanLog::with('user')
            ->where('tenant_id', $tenantId);

        if ($request->has('status') && in_array($request->status, ['success', 'failed'])) {
            $query->where('status', $request->status);
        }

        $logs = $query->latest()->paginate(15);
            
        return view('admin.kk_scan_logs.index', compact('logs'));
    }
}
