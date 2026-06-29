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
            'finance' => Setting::where('group', 'finance')->pluck('value', 'key')->toArray(),
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
                Setting::set($key, $value, null, $group);
            }
        }

        return back()->with('success', 'Pengaturan global berhasil diperbarui.');
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
            
            // Redirect ke secret URL agar browser mendapatkan cookie bypass, lalu Laravel akan mengarahkan ke '/'
            // Ini mencegah Admin terkena layar 503 setelah mengaktifkan mode maintenance.
            return redirect('/superadmin');
        }
    }
}
