<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LetterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AuditLog;

class LetterRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = LetterRequest::with('member.family')->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $letterRequests = $query->paginate(15)->withQueryString();

        return view('admin.letter-requests.index', compact('letterRequests', 'status'));
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
