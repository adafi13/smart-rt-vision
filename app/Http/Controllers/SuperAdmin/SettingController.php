<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // Load all settings grouped
        $settings = [
            'general' => Setting::where('group', 'general')->pluck('value', 'key')->toArray(),
            'trial'   => Setting::where('group', 'trial')->pluck('value', 'key')->toArray(),
        ];
        
        $isMaintenance = file_exists(storage_path('framework/down'));
        
        return view('super-admin.settings.index', compact('settings', 'isMaintenance'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $group => $keys) {
            foreach ($keys as $key => $value) {
                Setting::set($key, $value, 'string', $group);
            }
        }

        return back()->with('success', 'Pengaturan global berhasil diperbarui.');
    }

    public function updateAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,danger',
        ]);

        \App\Models\Announcement::create([
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Pengumuman global berhasil disiarkan.');
    }

    public function toggleMaintenance(Request $request)
    {
        if (file_exists(storage_path('framework/down'))) {
            \Illuminate\Support\Facades\Artisan::call('up');
            return back()->with('success', 'Sistem kembali online.');
        } else {
            \Illuminate\Support\Facades\Artisan::call('down', [
                '--secret' => 'superadmin' // Allows bypassing maintenance via /superadmin path
            ]);
            return back()->with('success', 'Mode Maintenance diaktifkan! Pengguna biasa tidak dapat mengakses sistem.');
        }
    }
}
