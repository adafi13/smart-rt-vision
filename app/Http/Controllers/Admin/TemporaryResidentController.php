<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TemporaryResident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemporaryResidentController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = app('currentTenant')->id;
        $query = TemporaryResident::where('tenant_id', $tenantId)->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $residents = $query->paginate(15)->withQueryString();

        return view('admin.temporary-residents.index', compact('residents', 'status'));
    }

    public function update(Request $request, TemporaryResident $resident)
    {
        if ($resident->tenant_id !== app('currentTenant')->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:Pending,Terverifikasi,Ditolak',
            'catatan' => 'nullable|string',
        ]);

        $resident->update($request->only(['status', 'catatan']));

        return back()->with('success', 'Status warga kos/kontrakan berhasil diperbarui.');
    }

    public function destroy(TemporaryResident $resident)
    {
        if ($resident->tenant_id !== app('currentTenant')->id) {
            abort(403);
        }

        if ($resident->foto_ktp) {
            Storage::disk('public')->delete($resident->foto_ktp);
        }

        $resident->delete();

        return back()->with('success', 'Data warga kos/kontrakan berhasil dihapus.');
    }
}
