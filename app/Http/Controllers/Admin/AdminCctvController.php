<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cctv;
use Illuminate\Http\Request;

class AdminCctvController extends Controller
{
    public function index()
    {
        $tenantId = auth()->user()->tenant_id;
        $cctvs = Cctv::where('tenant_id', $tenantId)->get();
            
        return view('admin.cctvs.index', compact('cctvs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'stream_url' => 'required|string', // Accept Iframe or raw URL
            'status' => 'required|in:active,inactive,maintenance',
        ]);

        Cctv::create([
            'tenant_id' => auth()->user()->tenant_id,
            'name' => $request->name,
            'location' => $request->location,
            'stream_url' => $request->stream_url,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Kamera CCTV berhasil ditambahkan.');
    }

    public function update(Request $request, Cctv $cctv)
    {
        if ($cctv->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'stream_url' => 'required|string',
            'status' => 'required|in:active,inactive,maintenance',
        ]);

        $cctv->update([
            'name' => $request->name,
            'location' => $request->location,
            'stream_url' => $request->stream_url,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Kamera CCTV berhasil diperbarui.');
    }

    public function destroy(Cctv $cctv)
    {
        if ($cctv->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $cctv->delete();
        return back()->with('success', 'Kamera CCTV berhasil dihapus.');
    }
}
