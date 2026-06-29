<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    public function cekIuran(Request $request)
    {
        $request->validate(['nik' => 'required|numeric']);

        $warga = Member::with('family')->where('nik', $request->nik)->first();

        if (! $warga) {
            return response()->json(['success' => false, 'message' => 'NIK tidak ditemukan.']);
        }

        $riwayat = Contribution::where('family_id', $warga->family_id)
            ->orderBy('periode', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'jenis' => $item->jenis_iuran,
                    'periode' => Carbon::parse($item->periode)->translatedFormat('F Y'),
                    'jumlah' => 'Rp '.number_format($item->jumlah, 0, ',', '.'),
                    'tanggal' => Carbon::parse($item->tanggal_bayar)->format('d/m/Y'),
                ];
            });

        // Calculate arrears for the current year up to the current month
        $year = now()->year;
        $monthsToCount = now()->month;
        $tenant = app('currentTenant');
        $nominalIuran = $tenant->nominal_iuran_bulanan ?? 50000;

        $paidMonths = Contribution::where('family_id', $warga->family_id)
            ->whereYear('periode', $year)
            ->get(['periode'])
            ->map(function ($c) {
                return Carbon::parse($c->periode)->month;
            })
            ->unique()
            ->toArray();

        $monthsOwed = 0;
        $unpaidMonths = [];

        for ($m = 1; $m <= $monthsToCount; $m++) {
            if (!in_array($m, $paidMonths)) {
                $monthsOwed++;
                $unpaidMonths[] = Carbon::create()->month($m)->translatedFormat('F');
            }
        }

        $totalArrears = $monthsOwed * $nominalIuran;

        $arrearsInfo = [
            'months_owed' => $monthsOwed,
            'unpaid_months' => $unpaidMonths,
            'total_arrears' => 'Rp ' . number_format($totalArrears, 0, ',', '.'),
            'nominal_iuran' => 'Rp ' . number_format($nominalIuran, 0, ',', '.'),
        ];

        return response()->json([
            'success' => true,
            'nama' => $warga->nama,
            'kk' => $warga->family->nomor_kk,
            'data' => $riwayat,
            'arrears' => $arrearsInfo,
        ]);
    }
}
