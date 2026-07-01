<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contribution;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $rw = auth()->user()->rw;

        // Iuran dari RT ke RW (Contributions where rw_id = $rw->id)
        $contributions = Contribution::with('tenant')
            ->where('rw_id', $rw->id)
            ->latest()
            ->paginate(10, ['*'], 'contributions_page');

        // Pengeluaran kas RW (Expenses where rw_id = $rw->id)
        $expenses = Expense::where('rw_id', $rw->id)
            ->latest()
            ->paginate(10, ['*'], 'expenses_page');

        $totalPemasukan = Contribution::where('rw_id', $rw->id)->sum('jumlah');
        $totalPengeluaran = Expense::where('rw_id', $rw->id)->sum('jumlah');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        $rts = Tenant::where('rw_id', $rw->id)->get();

        return view('rw.finance.index', compact(
            'contributions',
            'expenses',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoAkhir',
            'rts'
        ));
    }

    public function storeContribution(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_bayar' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $rw = auth()->user()->rw;

        Contribution::create([
            'tenant_id' => $request->tenant_id,
            'rw_id' => $rw->id,
            'family_id' => null, // now nullable
            'jenis_iuran' => 'Iuran RT ke RW',
            'jumlah' => $request->jumlah,
            'periode' => $request->tanggal_bayar, // use same date for periode
            'tanggal_bayar' => $request->tanggal_bayar,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Data iuran RT berhasil dicatat.');
    }

    public function destroyContribution(Contribution $contribution)
    {
        if ($contribution->rw_id !== auth()->user()->rw->id) {
            abort(403);
        }
        $contribution->delete();
        return back()->with('success', 'Data iuran RT berhasil dihapus.');
    }

    public function storeExpense(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:0',
            'tanggal_keluar' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'receipt' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $rw = auth()->user()->rw;
        $receiptPath = null;

        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        Expense::create([
            'rw_id' => $rw->id,
            'tenant_id' => null, // Explicitly null for RW global expense
            'jumlah' => $request->jumlah,
            'tanggal_keluar' => $request->tanggal_keluar,
            'keterangan' => $request->keterangan,
            'kategori' => 'Operasional RW', // default category
            'bukti_nota' => $receiptPath,
            'recorded_by' => auth()->id(),
        ]);

        return back()->with('success', 'Pengeluaran kas RW berhasil dicatat.');
    }

    public function destroyExpense(Expense $expense)
    {
        if ($expense->rw_id !== auth()->user()->rw->id) {
            abort(403);
        }

        if ($expense->bukti_nota) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($expense->bukti_nota);
        }

        $expense->delete();
        return back()->with('success', 'Pengeluaran kas RW berhasil dihapus.');
    }
}
