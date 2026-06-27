<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PanicAlert;
use Illuminate\Http\Request;

class PanicAlertController extends Controller
{
    public function index()
    {
        $alerts = PanicAlert::latest()->paginate(15);
        return view('admin.panic_alerts.index', compact('alerts'));
    }

    public function resolve(Request $request, PanicAlert $panicAlert)
    {
        $request->validate([
            'resolution_note' => 'required|string|max:1000'
        ]);

        $panicAlert->update([
            'status' => 'resolved',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
            'resolution_note' => $request->resolution_note,
        ]);

        return back()->with('success', 'Status darurat berhasil diselesaikan.');
    }
}
