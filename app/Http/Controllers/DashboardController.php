<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKk = \App\Models\Family::count();
        $totalWarga = \App\Models\Member::count();
        $totalLakiLaki = \App\Models\Member::where('jenis_kelamin', 'Laki-laki')->count();
        $totalPerempuan = \App\Models\Member::where('jenis_kelamin', 'Perempuan')->count();

        $members = \App\Models\Member::all(['jenis_kelamin', 'tanggal_lahir', 'pendidikan']);
        
        $ageGroups = ['Balita (0-4)' => 0, 'Anak-anak (5-11)' => 0, 'Remaja (12-25)' => 0, 'Dewasa (26-45)' => 0, 'Lansia (46+)' => 0];
        $pendidikanStats = [];
        
        foreach($members as $m) {
            if ($m->tanggal_lahir) {
                $age = \Carbon\Carbon::parse($m->tanggal_lahir)->age;
                if ($age <= 4) $ageGroups['Balita (0-4)']++;
                elseif ($age <= 11) $ageGroups['Anak-anak (5-11)']++;
                elseif ($age <= 25) $ageGroups['Remaja (12-25)']++;
                elseif ($age <= 45) $ageGroups['Dewasa (26-45)']++;
                else $ageGroups['Lansia (46+)']++;
            }
            
            if ($m->pendidikan && $m->pendidikan !== '-') {
                $p = strtoupper($m->pendidikan);
                $pendidikanStats[$p] = ($pendidikanStats[$p] ?? 0) + 1;
            }
        }
        
        arsort($pendidikanStats);

        $activePanicAlerts = \App\Models\PanicAlert::where('status', 'active')->latest()->get();
        
        $pendingReports = \App\Models\Report::where('status', 'menunggu')->count();
        $pendingReportsList = \App\Models\Report::where('status', 'menunggu')->latest()->take(5)->get();
        
        $pendingLetterRequests = \App\Models\LetterRequest::where('status', 'menunggu')->count();
        $pendingLetterRequestsList = \App\Models\LetterRequest::where('status', 'menunggu')->latest()->take(5)->get();
        
        // Data Keuangan Kas RT
        $totalPemasukan = \App\Models\Contribution::sum('jumlah');
        $totalPengeluaran = \App\Models\Expense::sum('jumlah');
        $saldoKas = $totalPemasukan - $totalPengeluaran;
        
        $pemasukanBulanIni = \App\Models\Contribution::whereMonth('tanggal_bayar', now()->month)
                                                     ->whereYear('tanggal_bayar', now()->year)
                                                     ->sum('jumlah');
                                                     
        $pengeluaranBulanIni = \App\Models\Expense::whereMonth('tanggal_keluar', now()->month)
                                                  ->whereYear('tanggal_keluar', now()->year)
                                                  ->sum('jumlah');
                                                  
        // Log Aktivitas Terbaru
        $latestActivities = \App\Models\AuditLog::with('user')->latest()->take(8)->get();
        
        // Cek Kuota AI
        $tenant = auth()->user()->tenant;
        $aiRemaining = 0;
        $aiPercentage = 100; // Asumsi aman jika tidak ada limit
        $aiLimit = 0;
        
        if ($tenant) {
            $plan = $tenant->effectivePlan();
            $aiUsed = $tenant->ai_extractions_used ?? 0;
            if ($plan && $plan->max_ai_extractions_per_month > 0) {
                $aiLimit = $plan->max_ai_extractions_per_month;
                $aiRemaining = max(0, $aiLimit - $aiUsed);
                $aiPercentage = ($aiRemaining / $aiLimit) * 100;
            } else {
                $aiRemaining = 'Unlimited';
            }
        }

        // Fetch RW Broadcasts
        $rwBroadcasts = collect();
        if ($tenant && $tenant->rw_id) {
            $rwBroadcasts = \App\Models\RwBroadcast::where('rw_id', $tenant->rw_id)
                ->where('status', 'active')
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dashboard', compact(
            'totalKk', 'totalWarga', 'totalLakiLaki', 'totalPerempuan',
            'ageGroups', 'pendidikanStats', 'activePanicAlerts',
            'pendingReports', 'pendingReportsList', 'pendingLetterRequests', 'pendingLetterRequestsList',
            'saldoKas', 'pemasukanBulanIni', 'pengeluaranBulanIni',
            'latestActivities',
            'aiRemaining', 'aiPercentage', 'aiLimit', 'rwBroadcasts'
        ));
    }
}
