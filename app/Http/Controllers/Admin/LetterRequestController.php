<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LetterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AuditLog;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class LetterRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = LetterRequest::with('member.family')->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $letterRequests = $query->paginate(15)->withQueryString();
        
        $tenantId = app('currentTenant')->id;
        $rtSignature = Setting::get("tenant_{$tenantId}_rt_signature_path");
        $rtName = Setting::get("tenant_{$tenantId}_rt_name");

        return view('admin.letter-requests.index', compact('letterRequests', 'status', 'rtSignature', 'rtName'));
    }

    public function updateSignature(Request $request)
    {
        $request->validate([
            'rt_name' => 'required|string|max:255',
            'rt_signature' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $tenantId = app('currentTenant')->id;

        Setting::set("tenant_{$tenantId}_rt_name", $request->rt_name);

        if ($request->hasFile('rt_signature')) {
            $oldSignature = Setting::get("tenant_{$tenantId}_rt_signature_path");
            if ($oldSignature) {
                Storage::disk('public')->delete($oldSignature);
            }

            $path = $request->file('rt_signature')->store('signatures', 'public');
            Setting::set("tenant_{$tenantId}_rt_signature_path", $path);
        } elseif ($request->filled('signature_data')) {
            $oldSignature = Setting::get("tenant_{$tenantId}_rt_signature_path");
            if ($oldSignature) {
                Storage::disk('public')->delete($oldSignature);
            }
            
            $base64Data = $request->signature_data;
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                $type = strtolower($type[1]);
                if (in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                    $base64Data = base64_decode($base64Data);
                    $filename = 'signatures/' . uniqid() . '.' . $type;
                    Storage::disk('public')->put($filename, $base64Data);
                    Setting::set("tenant_{$tenantId}_rt_signature_path", $filename);
                }
            }
        }

        return back()->with('success', 'Pengaturan Kop & Tanda Tangan berhasil disimpan.');
    }

    public function downloadPdf(LetterRequest $letterRequest)
    {
        if ($letterRequest->status !== 'Disetujui') {
            return back()->with('error', 'Hanya surat yang disetujui yang dapat dicetak.');
        }

        $tenantId = app('currentTenant')->id;
        $rtSignaturePath = Setting::get("tenant_{$tenantId}_rt_signature_path");
        $rtSignature = $rtSignaturePath ? public_path('storage/' . $rtSignaturePath) : null;
        
        // If local dev environment where public_path might fail for dompdf, encode as base64
        if ($rtSignature && file_exists($rtSignature)) {
            $type = pathinfo($rtSignature, PATHINFO_EXTENSION);
            $data = file_get_contents($rtSignature);
            $rtSignature = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $rtName = Setting::get("tenant_{$tenantId}_rt_name");
        $member = $letterRequest->member;

        $pdf = Pdf::loadView('pdf.surat_pengantar', [
            'nomor_surat' => $letterRequest->id,
            'keperluan' => $letterRequest->keperluan,
            'jenis_surat' => $letterRequest->jenis_surat,
            'member' => $member,
            'rtSignature' => $rtSignature,
            'rtName' => $rtName,
        ]);

        if (request()->has('preview')) {
            return $pdf->stream("Surat_Pengantar_{$member->nama}.pdf");
        }
        return $pdf->download("Surat_Pengantar_{$member->nama}.pdf");
    }

    public function update(Request $request, LetterRequest $letterRequest)
    {
        $request->validate([
            'status' => 'required|in:Pending,Disetujui,Ditolak',
            'catatan_admin' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request, $letterRequest) {
                $oldValues = $letterRequest->only(['status', 'catatan_admin']);
                
                $letterRequest->update($request->only(['status', 'catatan_admin']));

                AuditLog::create([
                    'tenant_id' => app('currentTenant')->id,
                    'user_id' => auth()->id(),
                    'action' => 'update_letter_request_status',
                    'model_type' => LetterRequest::class,
                    'model_id' => $letterRequest->id,
                    'old_values' => $oldValues,
                    'new_values' => $letterRequest->only(['status', 'catatan_admin']),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return back()->with('success', 'Status permohonan surat diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    public function destroy(LetterRequest $letterRequest)
    {
        try {
            DB::transaction(function () use ($letterRequest) {
                $oldValues = $letterRequest->toArray();
                
                $letterRequest->delete();

                AuditLog::create([
                    'tenant_id' => app('currentTenant')->id,
                    'user_id' => auth()->id(),
                    'action' => 'delete_letter_request',
                    'model_type' => LetterRequest::class,
                    'model_id' => $letterRequest->id,
                    'old_values' => $oldValues,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return back()->with('success', 'Permohonan surat dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus permohonan: ' . $e->getMessage());
        }
    }
}
