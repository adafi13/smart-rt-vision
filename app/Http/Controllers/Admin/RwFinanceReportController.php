<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contribution;
use App\Models\Expense;
use App\Models\Tenant;

class RwFinanceReportController extends Controller
{
    public function index(Request $request)
    {
        $tenant = app('currentTenant');
        
        if (!$tenant || !$tenant->rw_id) {
            return redirect()->route('dashboard')->with('error', 'Laporan Kas RW tidak tersedia karena RT ini belum tergabung ke RW manapun.');
        }

        $rwId = $tenant->rw_id;

        // Iuran yang diterima RW dari RT-RT
        $contributions = Contribution::withoutGlobalScope(\App\Models\Scopes\TenantScope::class)
            ->with('tenant')
            ->where('rw_id', $rwId)
            ->latest('tanggal_bayar')
            ->paginate(10, ['*'], 'contributions_page');

        // Pengeluaran kas RW
        $expenses = Expense::withoutGlobalScope(\App\Models\Scopes\TenantScope::class)
            ->where('rw_id', $rwId)
            ->latest('tanggal_keluar')
            ->paginate(10, ['*'], 'expenses_page');

        $totalPemasukanRW = Contribution::withoutGlobalScope(\App\Models\Scopes\TenantScope::class)
            ->where('rw_id', $rwId)->sum('jumlah');
            
        $totalPengeluaranRW = Expense::withoutGlobalScope(\App\Models\Scopes\TenantScope::class)
            ->where('rw_id', $rwId)->sum('jumlah');
            
        $saldoAkhirRW = $totalPemasukanRW - $totalPengeluaranRW;

        return view('admin.rw_finance.index', compact(
            'contributions',
            'expenses',
            'totalPemasukanRW',
            'totalPengeluaranRW',
            'saldoAkhirRW'
        ));
    }
}
