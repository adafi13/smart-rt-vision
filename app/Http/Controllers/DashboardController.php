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

        return view('dashboard', compact(
            'totalKk', 'totalWarga', 'totalLakiLaki', 'totalPerempuan',
            'ageGroups', 'pendidikanStats'
        ));
    }
}
