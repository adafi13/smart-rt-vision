<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Family;
use App\Models\Member;
use App\Models\Contribution;
use App\Models\Expense;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $rw = auth()->user()->rw;
        if (!$rw) {
            abort(404, 'RW tidak ditemukan.');
        }

        $tenantIds = $rw->tenants()->pluck('id');

        // Demografi
        $totalFamilies = Family::whereIn('tenant_id', $tenantIds)->count();
        $totalMembers = Member::whereIn('tenant_id', $tenantIds)->count();

        // Keuangan RW
        $totalPemasukanRW = Contribution::where('rw_id', $rw->id)->sum('jumlah');
        $totalPengeluaranRW = Expense::where('rw_id', $rw->id)->sum('jumlah');
        $saldoKasRW = $totalPemasukanRW - $totalPengeluaranRW;

        // Keuangan Gabungan RT
        $totalPemasukanGabungan = Contribution::whereIn('tenant_id', $tenantIds)->whereNull('rw_id')->sum('jumlah');
        $totalPengeluaranGabungan = Expense::whereIn('tenant_id', $tenantIds)->whereNull('rw_id')->sum('jumlah');
        $saldoKasGabungan = $totalPemasukanGabungan - $totalPengeluaranGabungan;

        // Demografi Lanjutan (Gender & Usia)
        $totalMale = Member::whereIn('tenant_id', $tenantIds)->where('jenis_kelamin', 'Laki-laki')->count();
        $totalFemale = Member::whereIn('tenant_id', $tenantIds)->where('jenis_kelamin', 'Perempuan')->count();

        // Hitung kelompok usia
        $now = now();
        $membersQuery = Member::whereIn('tenant_id', $tenantIds)->whereNotNull('tanggal_lahir');
        
        $anak = (clone $membersQuery)->where('tanggal_lahir', '>=', $now->copy()->subYears(12))->count(); // 0-12
        $remaja = (clone $membersQuery)->whereBetween('tanggal_lahir', [$now->copy()->subYears(25), $now->copy()->subYears(13)])->count(); // 13-25
        $dewasa = (clone $membersQuery)->whereBetween('tanggal_lahir', [$now->copy()->subYears(59), $now->copy()->subYears(26)])->count(); // 26-59
        $lansia = (clone $membersQuery)->where('tanggal_lahir', '<=', $now->copy()->subYears(60))->count(); // 60+

        // Breakdown per RT
        $rts = $rw->tenants()->withCount('families')
            ->with(['subscriptions.plan', 'rtStaffs' => fn($q) => $q->orderBy('order_level')])
            ->get()->map(function ($rt) {
            $pemasukan = Contribution::where('tenant_id', $rt->id)->whereNull('rw_id')->sum('jumlah');
            $pengeluaran = Expense::where('tenant_id', $rt->id)->whereNull('rw_id')->sum('jumlah');
            $rt->saldo = $pemasukan - $pengeluaran;
            return $rt;
        });

        // Hitung RT yang kedaluwarsa
        $expiredCount = 0;
        foreach ($rts as $rt) {
            if (!$rt->activeSubscription() && !$rt->onTrial()) {
                $expiredCount++;
            }
        }

        return view('rw.dashboard', compact(
            'rw', 'totalFamilies', 'totalMembers', 
            'saldoKasRW', 'saldoKasGabungan', 
            'rts', 'totalMale', 'totalFemale', 
            'anak', 'remaja', 'dewasa', 'lansia',
            'expiredCount'
        ));
    }
}
