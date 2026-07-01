<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use App\Models\LetterRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use App\Models\AuditLog;

class LetterRequestController extends Controller
{
    public function index(Request $request)
    {
        $rw = auth()->user()->rw;
        
        // Hanya tampilkan surat yang sudah disetujui RT dan berasal dari RT di bawah RW ini
        $query = LetterRequest::with(['member.family.tenant'])
            ->whereHas('member.family.tenant', function ($q) use ($rw) {
                $q->where('rw_id', $rw->id);
            })
            ->where('status', 'Disetujui')
            ->latest();

        if ($rwStatus = $request->input('rw_status')) {
            $query->where('rw_status', $rwStatus);
        }

        $letterRequests = $query->paginate(15)->withQueryString();
        
        $rwSignature = Setting::get("rw_{$rw->id}_signature_path");
        $rwName = $rw->name;

        return view('rw.letter-requests.index', compact('letterRequests', 'rwStatus', 'rwSignature', 'rwName'));
    }

    public function update(Request $request, LetterRequest $letterRequest)
    {
        $request->validate([
            'rw_status' => 'required|in:Pending,Disetujui,Ditolak',
            'catatan_rw' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request, $letterRequest) {
                $oldValues = $letterRequest->only(['rw_status', 'catatan_rw']);
                
                $letterRequest->update($request->only(['rw_status', 'catatan_rw']));

                AuditLog::create([
                    'tenant_id' => $letterRequest->member->family->tenant_id,
                    'user_id' => auth()->id(),
                    'action' => 'update_rw_letter_request_status',
                    'model_type' => LetterRequest::class,
                    'model_id' => $letterRequest->id,
                    'old_values' => $oldValues,
                    'new_values' => $letterRequest->only(['rw_status', 'catatan_rw']),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return back()->with('success', 'Status persetujuan tingkat RW diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    public function downloadPdf(LetterRequest $letterRequest)
    {
        if ($letterRequest->status !== 'Disetujui') {
            return back()->with('error', 'Surat belum disetujui oleh RT.');
        }

        $tenantId = $letterRequest->member->family->tenant_id;
        $rw = $letterRequest->member->family->tenant->rw;

        $rtSignaturePath = Setting::get("tenant_{$tenantId}_rt_signature_path");
        $rtSignature = $rtSignaturePath ? public_path('storage/' . $rtSignaturePath) : null;
        
        if ($rtSignature && file_exists($rtSignature)) {
            $type = pathinfo($rtSignature, PATHINFO_EXTENSION);
            $data = file_get_contents($rtSignature);
            $rtSignature = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $rtName = Setting::get("tenant_{$tenantId}_rt_name");
        
        $rwSignature = null;
        $rwName = null;
        $rwHeadName = null;

        if ($letterRequest->rw_status === 'Disetujui' && $rw) {
            $rwSignaturePath = Setting::get("rw_{$rw->id}_signature_path");
            $rwSignatureFile = $rwSignaturePath ? public_path('storage/' . $rwSignaturePath) : null;
            if ($rwSignatureFile && file_exists($rwSignatureFile)) {
                $type = pathinfo($rwSignatureFile, PATHINFO_EXTENSION);
                $data = file_get_contents($rwSignatureFile);
                $rwSignature = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
            $rwName = $rw->name;
            $rwHeadName = Setting::get("rw_{$rw->id}_head_name") ?: '...........................';
        }

        $member = $letterRequest->member;

        $pdf = Pdf::loadView('pdf.surat_pengantar', [
            'nomor_surat' => $letterRequest->id,
            'keperluan' => $letterRequest->keperluan,
            'jenis_surat' => $letterRequest->jenis_surat,
            'member' => $member,
            'tenant' => $letterRequest->member->family->tenant,
            'rtSignature' => $rtSignature,
            'rtName' => $rtName,
            'rwSignature' => $rwSignature,
            'rwName' => $rwName,
            'rwHeadName' => $rwHeadName,
        ]);

        if (request()->has('preview')) {
            return $pdf->stream("Surat_Pengantar_{$member->nama}.pdf");
        }
        return $pdf->download("Surat_Pengantar_{$member->nama}.pdf");
    }
}
