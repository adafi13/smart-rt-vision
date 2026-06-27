<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryBorrowing;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $tenant = auth()->user()->tenant;
        
        $inventories = Inventory::where('tenant_id', $tenant->id)->latest()->get();
        
        // Use CASE instead of FIELD for SQLite compatibility
        $borrowings = InventoryBorrowing::with(['inventory', 'member'])
            ->where('tenant_id', $tenant->id)
            ->orderByRaw("CASE 
                WHEN status = 'pending' THEN 1 
                WHEN status = 'approved' THEN 2 
                WHEN status = 'returned' THEN 3 
                WHEN status = 'rejected' THEN 4 
                ELSE 5 
            END")
            ->latest()
            ->get();

        return view('admin.inventaris.index', compact('inventories', 'borrowings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_quantity' => 'required|integer|min:1',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
        ]);

        $tenant = auth()->user()->tenant;

        try {
            DB::transaction(function () use ($request, $tenant) {
                $inventory = Inventory::create([
                    'tenant_id' => $tenant->id,
                    'name' => $request->name,
                    'description' => $request->description,
                    'total_quantity' => $request->total_quantity,
                    'condition' => $request->condition,
                ]);

                AuditLog::create([
                    'tenant_id' => $tenant->id,
                    'user_id' => auth()->id(),
                    'action' => 'create_inventory',
                    'model_type' => Inventory::class,
                    'model_id' => $inventory->id,
                    'new_values' => $inventory->toArray(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return back()->with('success', 'Data inventaris berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan inventaris: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Inventory $inventory)
    {
        if ($inventory->tenant_id !== auth()->user()->tenant_id) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_quantity' => 'required|integer|min:1',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
        ]);

        try {
            DB::transaction(function () use ($request, $inventory) {
                $oldValues = $inventory->only('name', 'description', 'total_quantity', 'condition');
                
                $inventory->update($request->only('name', 'description', 'total_quantity', 'condition'));

                AuditLog::create([
                    'tenant_id' => $inventory->tenant_id,
                    'user_id' => auth()->id(),
                    'action' => 'update_inventory',
                    'model_type' => Inventory::class,
                    'model_id' => $inventory->id,
                    'old_values' => $oldValues,
                    'new_values' => $inventory->only('name', 'description', 'total_quantity', 'condition'),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return back()->with('success', 'Data inventaris berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui inventaris: ' . $e->getMessage());
        }
    }

    public function destroy(Inventory $inventory)
    {
        if ($inventory->tenant_id !== auth()->user()->tenant_id) abort(403);

        if ($inventory->borrowings()->whereIn('status', ['pending', 'approved'])->exists()) {
            return back()->with('error', 'Tidak dapat menghapus inventaris karena masih ada yang meminjam/pending.');
        }

        try {
            DB::transaction(function () use ($inventory) {
                $oldValues = $inventory->toArray();
                $inventory->delete();

                AuditLog::create([
                    'tenant_id' => $inventory->tenant_id,
                    'user_id' => auth()->id(),
                    'action' => 'delete_inventory',
                    'model_type' => Inventory::class,
                    'model_id' => $inventory->id,
                    'old_values' => $oldValues,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });
            return back()->with('success', 'Data inventaris berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus inventaris: ' . $e->getMessage());
        }
    }

    // Borrowing Actions
    public function approve(InventoryBorrowing $borrowing)
    {
        if ($borrowing->tenant_id !== auth()->user()->tenant_id) abort(403);

        try {
            DB::transaction(function () use ($borrowing) {
                $borrowing->update(['status' => 'approved']);

                AuditLog::create([
                    'tenant_id' => $borrowing->tenant_id,
                    'user_id' => auth()->id(),
                    'action' => 'approve_borrowing',
                    'model_type' => InventoryBorrowing::class,
                    'model_id' => $borrowing->id,
                    'old_values' => ['status' => 'pending'],
                    'new_values' => ['status' => 'approved'],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });
            return back()->with('success', 'Peminjaman disetujui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses peminjaman: ' . $e->getMessage());
        }
    }

    public function reject(InventoryBorrowing $borrowing)
    {
        if ($borrowing->tenant_id !== auth()->user()->tenant_id) abort(403);

        try {
            DB::transaction(function () use ($borrowing) {
                $borrowing->update(['status' => 'rejected']);

                AuditLog::create([
                    'tenant_id' => $borrowing->tenant_id,
                    'user_id' => auth()->id(),
                    'action' => 'reject_borrowing',
                    'model_type' => InventoryBorrowing::class,
                    'model_id' => $borrowing->id,
                    'old_values' => ['status' => 'pending'],
                    'new_values' => ['status' => 'rejected'],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });
            return back()->with('success', 'Peminjaman ditolak.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menolak peminjaman: ' . $e->getMessage());
        }
    }

    public function returnItem(InventoryBorrowing $borrowing)
    {
        if ($borrowing->tenant_id !== auth()->user()->tenant_id) abort(403);

        try {
            DB::transaction(function () use ($borrowing) {
                $borrowing->update([
                    'status' => 'returned',
                    'return_date' => now(),
                ]);

                AuditLog::create([
                    'tenant_id' => $borrowing->tenant_id,
                    'user_id' => auth()->id(),
                    'action' => 'return_borrowing',
                    'model_type' => InventoryBorrowing::class,
                    'model_id' => $borrowing->id,
                    'old_values' => ['status' => 'approved'],
                    'new_values' => ['status' => 'returned', 'return_date' => now()],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });
            return back()->with('success', 'Barang berhasil dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pengembalian barang: ' . $e->getMessage());
        }
    }
}
