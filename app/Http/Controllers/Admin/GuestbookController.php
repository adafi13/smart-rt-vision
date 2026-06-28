<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guestbook;
use Illuminate\Http\Request;

class GuestbookController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = app('currentTenant')->id;
        $query = Guestbook::where('tenant_id', $tenantId)->latest();

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $guestbooks = $query->paginate(15)->withQueryString();
        $status = $request->input('status');

        return view('admin.guestbooks.index', compact('guestbooks', 'status'));
    }

    public function markAsLeft(Guestbook $guestbook)
    {
        if ($guestbook->tenant_id !== app('currentTenant')->id) abort(403);
        
        $guestbook->update([
            'status' => 'Keluar',
            'waktu_keluar' => now()
        ]);

        return back()->with('success', 'Tamu ditandai sudah keluar.');
    }

    public function destroy(Guestbook $guestbook)
    {
        if ($guestbook->tenant_id !== app('currentTenant')->id) abort(403);
        $guestbook->delete();
        return back()->with('success', 'Data tamu dihapus.');
    }

    public function updatePin(Request $request)
    {
        $request->validate([
            'pin' => 'required|numeric|digits_between:4,8'
        ]);

        $tenantId = app('currentTenant')->id;
        \App\Models\Setting::set("tenant_{$tenantId}_security_pin", $request->pin);

        return back()->with('success', 'PIN Keamanan Satpam berhasil diperbarui!');
    }
}
