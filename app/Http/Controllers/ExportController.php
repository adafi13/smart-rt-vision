<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\WargaExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Family;
use App\Models\AuditLog;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function excel()
    {
        $tenant = auth()->user()->tenant;
        
        AuditLog::create([
            'tenant_id' => $tenant->id,
            'user_id' => auth()->id(),
            'action' => 'export_excel_warga',
            'model_type' => 'Export',
            'model_id' => 0,
            'new_values' => ['format' => 'xlsx'],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return Excel::download(new WargaExport, 'data_warga_' . date('Ymd_Hi') . '.xlsx');
    }

    public function pdf()
    {
        $tenant = auth()->user()->tenant;
        $tenantName = $tenant->name ?? 'Lingkungan RT/RW';

        // Hanya mengambil keluarga dari tenant saat ini
        $families = Family::with('members')
            ->where('tenant_id', $tenant->id)
            ->get();

        AuditLog::create([
            'tenant_id' => $tenant->id,
            'user_id' => auth()->id(),
            'action' => 'export_pdf_warga',
            'model_type' => 'Export',
            'model_id' => 0,
            'new_values' => ['format' => 'pdf'],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $pdf = Pdf::loadView('exports.warga_pdf', compact('families', 'tenantName'))->setPaper('a4', 'landscape');
        
        return $pdf->download('rekap_warga_' . date('Ymd_Hi') . '.pdf');
    }
}
