<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('creator')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('super-admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('super-admin.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'message'        => 'required|string',
            'type'           => 'required|in:info,warning,success,danger',
            'target'         => 'required|in:all,owner,bendahara,sekretaris',
            'is_active'      => 'boolean',
            'is_dismissible' => 'boolean',
            'starts_at'      => 'nullable|date',
            'ends_at'        => 'nullable|date|after_or_equal:starts_at',
        ]);

        $validated['is_active']      = $request->boolean('is_active', true);
        $validated['is_dismissible'] = $request->boolean('is_dismissible', true);
        $validated['created_by']     = auth()->id();

        Announcement::create($validated);

        return redirect()->route('super-admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dibuat dan akan segera tampil ke mitra RT.');
    }

    public function edit(Announcement $announcement)
    {
        return view('super-admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'message'        => 'required|string',
            'type'           => 'required|in:info,warning,success,danger',
            'target'         => 'required|in:all,owner,bendahara,sekretaris',
            'is_active'      => 'boolean',
            'is_dismissible' => 'boolean',
            'starts_at'      => 'nullable|date',
            'ends_at'        => 'nullable|date|after_or_equal:starts_at',
        ]);

        $validated['is_active']      = $request->boolean('is_active', true);
        $validated['is_dismissible'] = $request->boolean('is_dismissible', true);

        $announcement->update($validated);

        // Reset dismissals agar semua user melihat pengumuman yang diupdate
        if ($request->boolean('reset_dismissals')) {
            $announcement->dismissals()->delete();
        }

        return redirect()->route('super-admin.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->dismissals()->delete();
        $announcement->delete();

        return redirect()->route('super-admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }

    public function toggleActive(Announcement $announcement)
    {
        $announcement->update(['is_active' => ! $announcement->is_active]);
        $status = $announcement->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Pengumuman berhasil {$status}.");
    }
}
