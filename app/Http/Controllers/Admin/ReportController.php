<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with('member')->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $reports = $query->paginate(15)->withQueryString();

        return view('admin.reports.index', compact('reports'));
    }

    public function update(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Proses,Selesai',
            'tanggapan_rt' => 'nullable|string',
        ]);

        $report->update($request->only(['status', 'tanggapan_rt']));

        return back()->with('success', 'Status laporan diperbarui.');
    }

    public function destroy(Report $report)
    {
        $report->delete();

        return back()->with('success', 'Laporan dihapus.');
    }
}
