<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AgendaController extends Controller
{
    public function index()
    {
        $rwId = auth()->user()->rw->id;
        $agendas = Agenda::where('rw_id', $rwId)
            ->whereNull('tenant_id')
            ->orderBy('start_time', 'asc')
            ->paginate(15);
            
        return view('rw.agendas.index', compact('agendas'));
    }

    public function create()
    {
        return view('rw.agendas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:rapat,kerjabakti,posyandu,umum',
        ]);

        Agenda::create([
            'rw_id' => auth()->user()->rw->id,
            'tenant_id' => null,
            'created_by' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => Carbon::parse($request->start_time),
            'end_time' => $request->end_time ? Carbon::parse($request->end_time) : null,
            'location' => $request->location,
            'type' => $request->type,
        ]);

        return redirect()->route('rw.agendas.index')->with('success', 'Agenda kegiatan RW berhasil ditambahkan.');
    }

    public function edit(Agenda $agenda)
    {
        if ($agenda->rw_id !== auth()->user()->rw->id) {
            abort(403);
        }
        return view('rw.agendas.edit', compact('agenda'));
    }

    public function update(Request $request, Agenda $agenda)
    {
        if ($agenda->rw_id !== auth()->user()->rw->id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:rapat,kerjabakti,posyandu,umum',
        ]);

        $agenda->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => Carbon::parse($request->start_time),
            'end_time' => $request->end_time ? Carbon::parse($request->end_time) : null,
            'location' => $request->location,
            'type' => $request->type,
        ]);

        return redirect()->route('rw.agendas.index')->with('success', 'Agenda kegiatan RW berhasil diperbarui.');
    }

    public function destroy(Agenda $agenda)
    {
        if ($agenda->rw_id !== auth()->user()->rw->id) {
            abort(403);
        }

        $agenda->delete();
        return back()->with('success', 'Agenda kegiatan RW berhasil dihapus.');
    }
}