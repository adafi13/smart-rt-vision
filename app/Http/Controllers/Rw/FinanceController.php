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

        $totalPemasukan = Contribution::where('rw_id', $rw->id)->sum('amount');
        $totalPengeluaran = Expense::where('rw_id', $rw->id)->sum('amount');
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
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        $rw = auth()->user()->rw;

        Contribution::create([
            'tenant_id' => $request->tenant_id,
            'rw_id' => $rw->id,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
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
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'required|string|max:255',
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
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
            'receipt_path' => $receiptPath,
            'recorded_by' => auth()->id(),
        ]);

        return back()->with('success', 'Pengeluaran kas RW berhasil dicatat.');
    }

    public function destroyExpense(Expense $expense)
    {
        if ($expense->rw_id !== auth()->user()->rw->id) {
            abort(403);
        }

        if ($expense->receipt_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($expense->receipt_path);
        }

        $expense->delete();
        return back()->with('success', 'Pengeluaran kas RW berhasil dihapus.');
    }
}
