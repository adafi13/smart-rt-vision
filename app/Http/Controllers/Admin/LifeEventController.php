<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LifeEvent;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LifeEventController extends Controller
{
    public function index(Request $request)
    {
        $query = LifeEvent::with('member.family')->latest();

        if ($status = $request->input('status')) {
            $query->where('status_verifikasi', $status);
        }

        $lifeEvents = $query->paginate(15)->withQueryString();

        return view('admin.life-events.index', compact('lifeEvents'));
    }

    public function update(Request $request, LifeEvent $lifeEvent)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:Pending,Terverifikasi,Ditolak',
        ]);

        try {
            DB::transaction(function () use ($request, $lifeEvent) {
                $oldValues = $lifeEvent->only(['status_verifikasi']);
                
                $lifeEvent->update($request->only(['status_verifikasi']));

                AuditLog::create([
                    'tenant_id' => app('currentTenant')->id,
                    'user_id' => auth()->id(),
                    'action' => 'update_life_event_status',
                    'model_type' => LifeEvent::class,
                    'model_id' => $lifeEvent->id,
                    'old_values' => $oldValues,
                    'new_values' => $lifeEvent->only(['status_verifikasi']),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return back()->with('success', 'Status verifikasi peristiwa diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    public function destroy(LifeEvent $lifeEvent)
    {
        try {
            DB::transaction(function () use ($lifeEvent) {
                $oldValues = $lifeEvent->toArray();
                
                $lifeEvent->delete();

                AuditLog::create([
                    'tenant_id' => app('currentTenant')->id,
                    'user_id' => auth()->id(),
                    'action' => 'delete_life_event',
                    'model_type' => LifeEvent::class,
                    'model_id' => $lifeEvent->id,
                    'old_values' => $oldValues,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return back()->with('success', 'Data peristiwa dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus peristiwa: ' . $e->getMessage());
        }
    }
}
