<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function store(Request $request)
    {
        $request->validate([
            'keterangan' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_keluar' => 'required|date',
            'kategori' => 'required|string',
            'bukti_nota' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('bukti_nota')) {
            $path = $request->file('bukti_nota')->store('bukti-nota', 'public');
        }

        Expense::create([
            'keterangan' => $request->keterangan,
            'jumlah' => $request->jumlah,
            'tanggal_keluar' => $request->tanggal_keluar,
            'kategori' => $request->kategori,
            'bukti_nota' => $path,
        ]);

        return back()->with('success', 'Data pengeluaran berhasil ditambahkan.');
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

        return back()->with('success', 'Data pengeluaran berhasil diperbarui.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return back()->with('success', 'Data pengeluaran dihapus.');
    }
}
