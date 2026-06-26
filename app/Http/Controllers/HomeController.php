<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Expense;
use App\Models\Family;
use App\Models\Member;
use App\Models\News;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $totalIuran = Contribution::sum('jumlah');
        $totalPengeluaran = Expense::sum('jumlah');

        $chartData = Expense::select('kategori', DB::raw('sum(jumlah) as total'))
            ->groupBy('kategori')
            ->get();

        $stats = [
            'tenant' => app('currentTenant'),
            'total_warga' => Member::where('status_warga', 'Aktif')->count(),
            'total_kk' => Family::count(),
            'laki_laki' => Member::where('status_warga', 'Aktif')->where('jenis_kelamin', 'Laki-laki')->count(),
            'perempuan' => Member::where('status_warga', 'Aktif')->where('jenis_kelamin', 'Perempuan')->count(),

            'warta' => News::orderBy('created_at', 'desc')->take(3)->get(),
            'produk' => Product::where('is_ready', true)->orderBy('created_at', 'desc')->take(4)->get(),

            'total_masuk' => $totalIuran,
            'total_keluar' => $totalPengeluaran,
            'saldo_kas' => $totalIuran - $totalPengeluaran,

            'chart_labels' => $chartData->pluck('kategori'),
            'chart_values' => $chartData->pluck('total'),
        ];

        return view('public.home', $stats);
    }

    public function cekNik(\Illuminate\Http\Request $request)
    {
        $nik = $request->query('nik');
        $warga = $nik ? Member::where('nik', $nik)->first() : null;

        return response()->json([
            'found' => (bool) $warga,
            'nama' => $warga ? substr($warga->nama, 0, 2).str_repeat('*', max(0, strlen($warga->nama) - 4)).substr($warga->nama, -2) : null,
            'status' => $warga ? $warga->status_warga : null,
        ]);
    }

    public function privasi()
    {
        return view('public.privasi', ['tenant' => app('currentTenant')]);
    }

    public function syarat()
    {
        return view('public.syarat', ['tenant' => app('currentTenant')]);
    }
}
