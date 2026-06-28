<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VacantHome;
use App\Models\VacantHomeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VacantHomeController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'Aktif');
        $vacantHomes = VacantHome::where('tenant_id', auth()->user()->tenant_id)
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.vacant-homes.index', compact('vacantHomes'));
    }

    public function show(VacantHome $vacantHome)
    {
        abort_if($vacantHome->tenant_id !== auth()->user()->tenant_id, 403);
        $vacantHome->load('logs.petugas');
        return view('admin.vacant-homes.show', compact('vacantHome'));
    }

    public function storeLog(Request $request, VacantHome $vacantHome)
    {
        abort_if($vacantHome->tenant_id !== auth()->user()->tenant_id, 403);
        
        $request->validate([
            'foto_bukti' => 'required|image|max:5120',
            'catatan_petugas' => 'nullable|string',
        ]);

        $path = $request->file('foto_bukti')->store('vacant_homes', 'public');

        VacantHomeLog::create([
            'vacant_home_id' => $vacantHome->id,
            'petugas_id' => auth()->id(),
            'petugas_nama' => auth()->user()->name,
            'waktu_patroli' => now(),
            'foto_bukti' => $path,
            'catatan_petugas' => $request->catatan_petugas,
        ]);

        return back()->with('success', 'Log patroli berhasil ditambahkan.');
    }

    public function updateStatus(Request $request, VacantHome $vacantHome)
    {
        abort_if($vacantHome->tenant_id !== auth()->user()->tenant_id, 403);
        $vacantHome->update(['status' => 'Selesai']);
        return back()->with('success', 'Status rumah kosong ditandai selesai (warga sudah kembali).');
    }

    public function destroy(VacantHome $vacantHome)
    {
        abort_if($vacantHome->tenant_id !== auth()->user()->tenant_id, 403);
        foreach($vacantHome->logs as $log) {
            if ($log->foto_bukti) Storage::disk('public')->delete($log->foto_bukti);
        }
        $vacantHome->delete();
        return redirect()->route('admin.vacant-homes.index')->with('success', 'Data titip rumah berhasil dihapus.');
    }
}
