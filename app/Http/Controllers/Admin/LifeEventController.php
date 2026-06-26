<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LifeEvent;
use Illuminate\Http\Request;

class LifeEventController extends Controller
{
    public function index(Request $request)
    {
        $query = LifeEvent::with('member')->latest();

        if ($status = $request->input('status')) {
            $query->where('status_verifikasi', $status);
        }

        $lifeEvents = $query->paginate(15)->withQueryString();

        return view('admin.life-events.index', compact('lifeEvents'));
    }

    public function update(Request $request, LifeEvent $lifeEvent)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:Pending,Terverifikasi,Ditolak',
        ]);

        $lifeEvent->update($request->only(['status_verifikasi']));

        return back()->with('success', 'Status verifikasi peristiwa diperbarui.');
    }

    public function destroy(LifeEvent $lifeEvent)
    {
        $lifeEvent->delete();

        return back()->with('success', 'Data peristiwa dihapus.');
    }
}
