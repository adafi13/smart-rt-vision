<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use App\Models\Family;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TunggakanController extends Controller
{
    // Iuran per month is now dynamic from tenant settings 
    
    public function index(Request $request)
    {
        // Month & Year selection (Default to current)
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $selectedDate = Carbon::createFromDate($year, $month, 1);
        
        $tenant = app('currentTenant');
        $nominalIuran = $tenant->nominal_iuran_bulanan ?? 50000;
        
        // Tab 1: Bulanan
        $families = Family::orderBy('nama_kepala_keluarga')->get();
        
        $contributionsThisMonth = Contribution::whereYear('periode', $year)
            ->whereMonth('periode', $month)
            ->pluck('jumlah', 'family_id'); // family_id => jumlah

        $bulanan = $families->map(function ($family) use ($contributionsThisMonth) {
            $hasPaid = $contributionsThisMonth->has($family->id);
            return (object) [
                'family' => $family,
                'status' => $hasPaid ? 'LUNAS' : 'MENUNGGAK',
                'amount_paid' => $hasPaid ? $contributionsThisMonth[$family->id] : 0,
                'arrears_amount' => $hasPaid ? 0 : $nominalIuran,
            ];
        });

        // Tab 2: Tahunan (Akumulasi Tunggakan sejak Januari tahun terpilih hingga bulan berjalan)
        // If year is past year, count all 12 months. If current year, count up to current month.
        $monthsToCount = ($year == now()->year) ? now()->month : 12;
        if ($year > now()->year) $monthsToCount = 0; // Future year, no arrears yet

        // Fetch all contributions for the selected year and group by family
        // We use Carbon to extract the month in PHP to be 100% Database Agnostic (MySQL/SQLite compatible)
        $yearlyContributions = Contribution::whereYear('periode', $year)
            ->get(['family_id', 'periode'])
            ->groupBy('family_id')
            ->map(function ($contributions) {
                return $contributions->map(function ($c) {
                    return Carbon::parse($c->periode)->month;
                })->unique()->toArray();
            });

        $tahunan = $families->map(function ($family) use ($yearlyContributions, $monthsToCount) {
            $paidMonths = $yearlyContributions->has($family->id) ? $yearlyContributions[$family->id] : [];
            
            $monthsOwed = 0;
            $unpaidMonthNames = [];
            
            for ($m = 1; $m <= $monthsToCount; $m++) {
                if (!in_array($m, $paidMonths)) {
                    $monthsOwed++;
                    $unpaidMonthNames[] = Carbon::create()->month($m)->translatedFormat('F');
                }
            }
            
            return (object) [
                'family' => $family,
                'months_owed' => $monthsOwed,
                'unpaid_months_list' => $unpaidMonthNames,
                'total_arrears' => $monthsOwed * $nominalIuran,
            ];
        })->filter(function ($item) {
            return $item->months_owed > 0; // Only show those who owe
        })->sortByDesc('months_owed');

        return view('admin.tunggakan.index', compact(
            'bulanan', 
            'tahunan', 
            'month', 
            'year', 
            'selectedDate',
            'nominalIuran'
        ));
    }

    public function updateSetting(Request $request)
    {
        $request->validate([
            'nominal_iuran_bulanan' => 'required|numeric|min:0'
        ]);

        $tenant = app('currentTenant');
        $tenant->update([
            'nominal_iuran_bulanan' => $request->nominal_iuran_bulanan
        ]);

        return back()->with('success', 'Nominal Iuran Wajib Bulanan berhasil diperbarui. Seluruh perhitungan tunggakan telah disesuaikan.');
    }
}
