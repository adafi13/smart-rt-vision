<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportReply;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $tenant = auth()->user()->tenant;
        
        $query = Report::with(['member', 'replies.user'])->where('tenant_id', $tenant->id)->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $reports = $query->paginate(15)->withQueryString();

        return view('admin.reports.index', compact('reports'));
    }

    public function update(Request $request, Report $report)
    {
        if ($report->tenant_id !== auth()->user()->tenant_id) abort(403);

        $request->validate([
            'status' => 'required|in:Menunggu,Proses,Selesai,Ditolak',
            'message' => 'required|string',
            'attachment' => 'nullable|image|max:2048',
        ]);

        try {
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('reports', 'public');
            }

            DB::transaction(function () use ($request, $report, $attachmentPath) {
                $oldStatus = $report->status;
                
                // Save reply
                $reply = ReportReply::create([
                    'tenant_id' => $report->tenant_id,
                    'report_id' => $report->id,
                    'user_id' => auth()->id(),
                    'message' => $request->message,
                    'attachment_path' => $attachmentPath,
                    'is_system' => false,
                ]);

                // If status changed, log it
                if ($oldStatus !== $request->status) {
                    $report->update(['status' => $request->status]);
                    
                    ReportReply::create([
                        'tenant_id' => $report->tenant_id,
                        'report_id' => $report->id,
                        'user_id' => auth()->id(),
                        'message' => "Status diubah dari $oldStatus menjadi " . $request->status,
                        'is_system' => true,
                    ]);
                }

                AuditLog::create([
                    'tenant_id' => app('currentTenant')->id,
                    'user_id' => auth()->id(),
                    'action' => 'reply_report',
                    'model_type' => Report::class,
                    'model_id' => $report->id,
                    'old_values' => ['status' => $oldStatus],
                    'new_values' => ['status' => $report->status, 'reply' => $request->message],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return back()->with('success', 'Tanggapan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses tanggapan: ' . $e->getMessage());
        }
    }

    public function destroy(Report $report)
    {
        if ($report->tenant_id !== auth()->user()->tenant_id) abort(403);
        
        try {
            DB::transaction(function () use ($report) {
                $oldValues = $report->toArray();
                $report->delete();

                AuditLog::create([
                    'tenant_id' => app('currentTenant')->id,
                    'user_id' => auth()->id(),
                    'action' => 'delete_report',
                    'model_type' => Report::class,
                    'model_id' => $report->id,
                    'old_values' => $oldValues,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });
            return back()->with('success', 'Laporan beserta riwayatnya dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }
    }
}
