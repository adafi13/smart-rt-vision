<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminAgendaController extends Controller
{
    public function index()
    {
        $tenantId = auth()->user()->tenant_id;
        $agendas = Agenda::where('tenant_id', $tenantId)
            ->orderBy('start_time', 'asc')
            ->paginate(15);
            
        return view('admin.agendas.index', compact('agendas'));
    }

    public function create()
    {
        return view('admin.agendas.create');
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
            'tenant_id' => auth()->user()->tenant_id,
            'created_by' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => Carbon::parse($request->start_time),
            'end_time' => $request->end_time ? Carbon::parse($request->end_time) : null,
            'location' => $request->location,
            'type' => $request->type,
        ]);

        return redirect()->route('admin.agendas.index')->with('success', 'Agenda kegiatan berhasil ditambahkan.');
    }

    public function edit(Agenda $agenda)
    {
        if ($agenda->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }
        return view('admin.agendas.edit', compact('agenda'));
    }

    public function update(Request $request, Agenda $agenda)
    {
        if ($agenda->tenant_id !== auth()->user()->tenant_id) {
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

        return redirect()->route('admin.agendas.index')->with('success', 'Agenda kegiatan berhasil diperbarui.');
    }

    public function destroy(Agenda $agenda)
    {
        if ($agenda->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $agenda->delete();
        return back()->with('success', 'Agenda kegiatan berhasil dihapus.');
    }
}
