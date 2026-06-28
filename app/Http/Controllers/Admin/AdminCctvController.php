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
        
        $ezvizAppKey = \App\Models\Setting::get("tenant_{$tenantId}_ezviz_app_key", "");
        $ezvizAppSecret = \App\Models\Setting::get("tenant_{$tenantId}_ezviz_app_secret", "");
            
        return view('admin.cctvs.index', compact('cctvs', 'ezvizAppKey', 'ezvizAppSecret'));
    }

    public function updateEzvizSettings(Request $request)
    {
        $request->validate([
            'ezviz_app_key' => 'nullable|string',
            'ezviz_app_secret' => 'nullable|string',
        ]);

        $tenantId = auth()->user()->tenant_id;
        \App\Models\Setting::set("tenant_{$tenantId}_ezviz_app_key", $request->ezviz_app_key);
        \App\Models\Setting::set("tenant_{$tenantId}_ezviz_app_secret", $request->ezviz_app_secret);
        
        // Clear cached token if any
        \App\Services\EzvizService::clearCachedToken($tenantId);

        return back()->with('success', 'Konfigurasi API EZVIZ berhasil diperbarui.');
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
