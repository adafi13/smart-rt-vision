<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use App\Models\RwBroadcast;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    public function index()
    {
        $rw = auth()->user()->rw;
        $broadcasts = $rw->broadcasts()->latest()->paginate(10);
        return view('rw.broadcasts.index', compact('rw', 'broadcasts'));
    }

    public function create()
    {
        return view('rw.broadcasts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $rw = auth()->user()->rw;
        $rw->broadcasts()->create([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
        ]);

        return redirect()->route('rw.broadcasts.index')->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function edit(RwBroadcast $broadcast)
    {
        $rw = auth()->user()->rw;
        if ($broadcast->rw_id !== $rw->id) abort(403);

        return view('rw.broadcasts.edit', compact('broadcast'));
    }

    public function update(Request $request, RwBroadcast $broadcast)
    {
        $rw = auth()->user()->rw;
        if ($broadcast->rw_id !== $rw->id) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $broadcast->update([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
        ]);

        return redirect()->route('rw.broadcasts.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(RwBroadcast $broadcast)
    {
        $rw = auth()->user()->rw;
        if ($broadcast->rw_id !== $rw->id) abort(403);

        $broadcast->delete();
        return redirect()->route('rw.broadcasts.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
