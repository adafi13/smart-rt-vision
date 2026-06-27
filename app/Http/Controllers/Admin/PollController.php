<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PollController extends Controller
{
    public function index()
    {
        $tenantId = app('currentTenant')->id;
        $polls = Poll::where('tenant_id', $tenantId)->latest()->paginate(10);
        return view('admin.polls.index', compact('polls'));
    }

    public function create()
    {
        return view('admin.polls.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,closed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $poll = Poll::create([
                    'tenant_id' => app('currentTenant')->id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'status' => $request->status,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                ]);

                foreach ($request->options as $optionText) {
                    PollOption::create([
                        'poll_id' => $poll->id,
                        'option_text' => $optionText,
                    ]);
                }
            });

            return redirect()->route('admin.polls.index')->with('success', 'Musyawarah Digital / Polling berhasil dibuat.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat polling: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Poll $poll)
    {
        if ($poll->tenant_id !== app('currentTenant')->id) abort(403);
        
        $poll->load(['options', 'votes']);
        return view('admin.polls.show', compact('poll'));
    }

    public function edit(Poll $poll)
    {
        if ($poll->tenant_id !== app('currentTenant')->id) abort(403);
        return view('admin.polls.edit', compact('poll'));
    }

    public function update(Request $request, Poll $poll)
    {
        if ($poll->tenant_id !== app('currentTenant')->id) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,closed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $poll->update($request->only(['title', 'description', 'status', 'start_date', 'end_date']));

        return redirect()->route('admin.polls.index')->with('success', 'Polling berhasil diperbarui.');
    }

    public function destroy(Poll $poll)
    {
        if ($poll->tenant_id !== app('currentTenant')->id) abort(403);
        $poll->delete();
        return redirect()->route('admin.polls.index')->with('success', 'Polling berhasil dihapus.');
    }
}
