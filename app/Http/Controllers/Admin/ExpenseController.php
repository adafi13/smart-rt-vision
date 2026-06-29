<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\AuditLog;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::latest('tanggal_keluar');

        if ($search = $request->input('search')) {
            $query->where('keterangan', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
        }

        $expenses = $query->paginate(15)->withQueryString();

        return view('admin.expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('admin.expenses.create');
    }

    public function edit(Expense $expense)
    {
        return view('admin.expenses.edit', compact('expense'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'keterangan' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_keluar' => 'required|date',
            'kategori' => 'required|string',
            'bukti_nota' => 'nullable|image|max:2048',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $path = null;
                if ($request->hasFile('bukti_nota')) {
                    $path = $request->file('bukti_nota')->store('bukti-nota', 'public');
                }

                $expense = Expense::create([
                    'keterangan' => $request->keterangan,
                    'jumlah' => $request->jumlah,
                    'tanggal_keluar' => $request->tanggal_keluar,
                    'kategori' => $request->kategori,
                    'bukti_nota' => $path,
                ]);

                AuditLog::create([
                    'tenant_id' => app('currentTenant')->id,
                    'user_id' => auth()->id(),
                    'action' => 'create_expense',
                    'model_type' => Expense::class,
                    'model_id' => $expense->id,
                    'new_values' => $expense->only(['keterangan', 'jumlah', 'kategori']),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });
            return redirect()->route('admin.pengeluaran.index')->with('success', 'Data pengeluaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan pengeluaran: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'keterangan' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_keluar' => 'required|date',
            'kategori' => 'required|string',
            'bukti_nota' => 'nullable|image|max:2048',
        ]);

        try {
            DB::transaction(function () use ($request, $expense) {
                $oldValues = $expense->only(['keterangan', 'jumlah', 'kategori']);
                $path = $expense->bukti_nota;
                
                if ($request->hasFile('bukti_nota')) {
                    if ($path) {
                        Storage::disk('public')->delete($path);
                    }
                    $path = $request->file('bukti_nota')->store('bukti-nota', 'public');
                }

                $expense->update([
                    'keterangan' => $request->keterangan,
                    'jumlah' => $request->jumlah,
                    'tanggal_keluar' => $request->tanggal_keluar,
                    'kategori' => $request->kategori,
                    'bukti_nota' => $path,
                ]);

                AuditLog::create([
                    'tenant_id' => app('currentTenant')->id,
                    'user_id' => auth()->id(),
                    'action' => 'update_expense',
                    'model_type' => Expense::class,
                    'model_id' => $expense->id,
                    'old_values' => $oldValues,
                    'new_values' => $expense->only(['keterangan', 'jumlah', 'kategori']),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });
            return redirect()->route('admin.pengeluaran.index')->with('success', 'Data pengeluaran berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui pengeluaran: ' . $e->getMessage());
        }
    }

    public function destroy(Expense $expense)
    {
        try {
            DB::transaction(function () use ($expense) {
                $oldValues = $expense->toArray();
                $path = $expense->bukti_nota;
                
                $expense->delete();
                
                if ($path) {
                    Storage::disk('public')->delete($path);
                }

                AuditLog::create([
                    'tenant_id' => app('currentTenant')->id,
                    'user_id' => auth()->id(),
                    'action' => 'delete_expense',
                    'model_type' => Expense::class,
                    'model_id' => $expense->id,
                    'old_values' => $oldValues,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });
            return back()->with('success', 'Data pengeluaran dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus pengeluaran: ' . $e->getMessage());
        }
    }
}
