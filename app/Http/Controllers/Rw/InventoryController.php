<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryBorrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $rwId = auth()->user()->rw->id;
        
        $inventories = Inventory::where('rw_id', $rwId)->whereNull('tenant_id')->latest()->get();
        
        // Use CASE instead of FIELD for SQLite compatibility
        $borrowings = InventoryBorrowing::with(['inventory', 'member'])
            ->where('rw_id', $rwId)
            ->whereNull('tenant_id')
            ->orderByRaw("CASE 
                WHEN status = 'pending' THEN 1 
                WHEN status = 'approved' THEN 2 
                WHEN status = 'returned' THEN 3 
                WHEN status = 'rejected' THEN 4 
                ELSE 5 
            END")
            ->latest()
            ->get();

        return view('rw.inventaris.index', compact('inventories', 'borrowings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_quantity' => 'required|integer|min:1',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
        ]);

        $rwId = auth()->user()->rw->id;

        try {
            DB::transaction(function () use ($request, $rwId) {
                Inventory::create([
                    'rw_id' => $rwId,
                    'tenant_id' => null,
                    'name' => $request->name,
                    'description' => $request->description,
                    'total_quantity' => $request->total_quantity,
                    'condition' => $request->condition,
                ]);
            });

            return back()->with('success', 'Data inventaris RW berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan inventaris: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Inventory $inventory)
    {
        if ($inventory->rw_id !== auth()->user()->rw->id) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_quantity' => 'required|integer|min:1',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
        ]);

        try {
            DB::transaction(function () use ($request, $inventory) {
                $inventory->update($request->only('name', 'description', 'total_quantity', 'condition'));
            });

            return back()->with('success', 'Data inventaris RW berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui inventaris: ' . $e->getMessage());
        }
    }

    public function destroy(Inventory $inventory)
    {
        if ($inventory->rw_id !== auth()->user()->rw->id) abort(403);

        if ($inventory->borrowings()->whereIn('status', ['pending', 'approved'])->exists()) {
            return back()->with('error', 'Tidak dapat menghapus inventaris karena masih ada yang meminjam/pending.');
        }

        try {
            DB::transaction(function () use ($inventory) {
                $inventory->delete();
            });
            return back()->with('success', 'Data inventaris RW berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus inventaris: ' . $e->getMessage());
        }
    }

    // Borrowing Actions
    public function approve(InventoryBorrowing $borrowing)
    {
        if ($borrowing->rw_id !== auth()->user()->rw->id) abort(403);

        try {
            DB::transaction(function () use ($borrowing) {
                $borrowing->update(['status' => 'approved']);
            });
            return back()->with('success', 'Peminjaman disetujui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses peminjaman: ' . $e->getMessage());
        }
    }

    public function reject(InventoryBorrowing $borrowing)
    {
        if ($borrowing->rw_id !== auth()->user()->rw->id) abort(403);

        try {
            DB::transaction(function () use ($borrowing) {
                $borrowing->update(['status' => 'rejected']);
            });
            return back()->with('success', 'Peminjaman ditolak.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menolak peminjaman: ' . $e->getMessage());
        }
    }

    public function returnItem(InventoryBorrowing $borrowing)
    {
        if ($borrowing->rw_id !== auth()->user()->rw->id) abort(403);

        try {
            DB::transaction(function () use ($borrowing) {
                $borrowing->update([
                    'status' => 'returned',
                    'return_date' => now(),
                ]);
            });
            return back()->with('success', 'Barang berhasil dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pengembalian barang: ' . $e->getMessage());
        }
    }
}