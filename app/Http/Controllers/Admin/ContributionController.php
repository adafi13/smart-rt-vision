<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use App\Models\Family;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    public function index(Request $request)
    {
        $query = Contribution::with('family')->latest('periode');

        if ($search = $request->input('search')) {
            $query->whereHas('family', function ($q) use ($search) {
                $q->where('nomor_kk', 'like', "%{$search}%")
                  ->orWhere('nama_kepala_keluarga', 'like', "%{$search}%");
            });
        }

        $contributions = $query->paginate(15)->withQueryString();
        $families = Family::orderBy('nama_kepala_keluarga')->get(['id', 'nomor_kk', 'nama_kepala_keluarga']);

        return view('admin.contributions.index', compact('contributions', 'families'));
    }

    public function create()
    {
        $families = Family::orderBy('nama_kepala_keluarga')->get(['id', 'nomor_kk', 'nama_kepala_keluarga']);
        return view('admin.contributions.create', compact('families'));
    }

    public function edit(Contribution $contribution)
    {
        $families = Family::orderBy('nama_kepala_keluarga')->get(['id', 'nomor_kk', 'nama_kepala_keluarga']);
        return view('admin.contributions.edit', compact('contribution', 'families'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'family_id' => 'required|exists:families,id',
            'jenis_iuran' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
            'periode' => 'required|date',
            'tanggal_bayar' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
                $contribution = Contribution::create($request->only(['family_id', 'jenis_iuran', 'jumlah', 'periode', 'tanggal_bayar', 'keterangan']));
                
                \App\Models\AuditLog::create([
                    'tenant_id' => app('currentTenant')->id,
                    'user_id' => auth()->id(),
                    'action' => 'create_contribution',
                    'model_type' => Contribution::class,
                    'model_id' => $contribution->id,
                    'new_values' => ['jenis_iuran' => $contribution->jenis_iuran, 'jumlah' => $contribution->jumlah],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });
            return redirect()->route('admin.iuran.index')->with('success', 'Data iuran berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan iuran: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, Contribution $contribution)
    {
        $request->validate([
            'family_id' => 'required|exists:families,id',
            'jenis_iuran' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
            'periode' => 'required|date',
            'tanggal_bayar' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $contribution) {
                $oldValues = $contribution->only(['family_id', 'jenis_iuran', 'jumlah', 'periode', 'tanggal_bayar', 'keterangan']);
                $contribution->update($request->only(['family_id', 'jenis_iuran', 'jumlah', 'periode', 'tanggal_bayar', 'keterangan']));
                
                \App\Models\AuditLog::create([
                    'tenant_id' => app('currentTenant')->id,
                    'user_id' => auth()->id(),
                    'action' => 'update_contribution',
                    'model_type' => Contribution::class,
                    'model_id' => $contribution->id,
                    'old_values' => $oldValues,
                    'new_values' => $contribution->only(['family_id', 'jenis_iuran', 'jumlah', 'periode', 'tanggal_bayar', 'keterangan']),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });
            return redirect()->route('admin.iuran.index')->with('success', 'Data iuran berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui iuran: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Contribution $contribution)
    {
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($contribution) {
                $oldValues = $contribution->toArray();
                $contribution->delete();
                
                \App\Models\AuditLog::create([
                    'tenant_id' => app('currentTenant')->id,
                    'user_id' => auth()->id(),
                    'action' => 'delete_contribution',
                    'model_type' => Contribution::class,
                    'model_id' => $contribution->id,
                    'old_values' => $oldValues,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });
            return back()->with('success', 'Data iuran dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus iuran: ' . $e->getMessage());
        }
    }
}
