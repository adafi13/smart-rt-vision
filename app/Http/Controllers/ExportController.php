<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\WargaExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Family;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function excel()
    {
        return Excel::download(new WargaExport, 'data_warga.xlsx');
    }

    public function pdf()
    {
        $families = Family::with('members')->get();
        $pdf = Pdf::loadView('exports.warga_pdf', compact('families'))->setPaper('a4', 'landscape');
        return $pdf->download('rekap_warga.pdf');
    }
}
