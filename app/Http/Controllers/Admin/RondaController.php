<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\RondaSchedule;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RondaController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        // Fetch upcoming schedules (Grouped by date)
        $schedules = RondaSchedule::with('member')
            ->where('tenant_id', $tenantId)
            ->whereDate('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date', 'asc')
            ->get()
            ->groupBy(function($item) {
                return $item->date->format('Y-m-d');
            });

        // Fetch past schedules with attendances (including today)
        $history = RondaSchedule::with(['member', 'attendance'])
            ->where('tenant_id', $tenantId)
            ->whereDate('date', '<=', now()->format('Y-m-d'))
            ->orderBy('date', 'desc')
            ->paginate(15);

        // Fetch all members for the dropdown
        $members = Member::where('tenant_id', $tenantId)->orderBy('nama')->get();

        return view('admin.ronda.index', compact('schedules', 'history', 'members'));
    }

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'member_ids' => 'required|array',
            'member_ids.*' => 'exists:members,id',
        ]);

        $tenantId = auth()->user()->tenant_id;

        try {
            DB::transaction(function () use ($request, $tenantId) {
                $createdSchedules = [];

                foreach ($request->member_ids as $memberId) {
                    $schedule = RondaSchedule::updateOrCreate(
                        [
                            'tenant_id' => $tenantId,
                            'member_id' => $memberId,
                            'date' => $request->date,
                        ]
                    );
                    $createdSchedules[] = $schedule->id;
                }

                AuditLog::create([
                    'tenant_id' => $tenantId,
                    'user_id' => auth()->id(),
                    'action' => 'create_ronda_schedule',
                    'model_type' => RondaSchedule::class,
                    'model_id' => $createdSchedules[0] ?? 0,
                    'new_values' => ['date' => $request->date, 'member_ids' => $request->member_ids, 'schedule_ids' => $createdSchedules],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return back()->with('success', 'Jadwal ronda berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan jadwal ronda: ' . $e->getMessage());
        }
    }

    public function destroySchedule(RondaSchedule $schedule)
    {
        if ($schedule->tenant_id !== auth()->user()->tenant_id) abort(403);
        
        try {
            DB::transaction(function () use ($schedule) {
                $oldValues = $schedule->toArray();
                
                $schedule->delete();

                AuditLog::create([
                    'tenant_id' => $schedule->tenant_id,
                    'user_id' => auth()->id(),
                    'action' => 'delete_ronda_schedule',
                    'model_type' => RondaSchedule::class,
                    'model_id' => $schedule->id,
                    'old_values' => $oldValues,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });
            
            return back()->with('success', 'Jadwal dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan jadwal: ' . $e->getMessage());
        }
    }
}
