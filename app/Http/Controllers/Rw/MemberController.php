<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Exports\RwWargaExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\AuditLog;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $rw = auth()->user()->rw;
        if (!$rw) {
            abort(404, 'RW tidak ditemukan.');
        }

        $tenantIds = $rw->tenants()->pluck('id');
        
        $query = Member::with(['tenant', 'family'])
            ->whereIn('tenant_id', $tenantIds);

        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'like', "%{$searchTerm}%")
                  ->orWhere('nik', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('rt_id') && $request->rt_id !== 'all') {
            // Verify that the requested RT belongs to this RW
            if (in_array($request->rt_id, $tenantIds->toArray())) {
                $query->where('tenant_id', $request->rt_id);
            }
        }

        $members = $query->orderBy('tenant_id')->orderBy('nama')->paginate(20)->withQueryString();
        $rts = $rw->tenants()->orderBy('name')->get();

        return view('rw.members.index', compact('members', 'rts', 'rw'));
    }

    public function exportExcel(Request $request)
    {
        $rw = auth()->user()->rw;
        if (!$rw) {
            abort(404, 'RW tidak ditemukan.');
        }

        $tenantIds = $rw->tenants()->pluck('id')->toArray();
        $rtId = null;

        if ($request->filled('rt_id') && $request->rt_id !== 'all') {
            if (in_array($request->rt_id, $tenantIds)) {
                $rtId = $request->rt_id;
            }
        }

        AuditLog::create([
            'tenant_id' => auth()->user()->tenant_id ?? 0,
            'user_id' => auth()->id(),
            'action' => 'export_excel_warga_rw',
            'model_type' => 'Export',
            'model_id' => 0,
            'new_values' => ['format' => 'xlsx', 'rt_id' => $rtId ?? 'all'],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return Excel::download(new RwWargaExport($rw->id, $rtId), 'rekap_warga_rw_' . date('Ymd_Hi') . '.xlsx');
    }
}
