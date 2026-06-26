<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ImpersonationController extends Controller
{
    public function impersonate(Tenant $tenant)
    {
        // Temukan Admin RT pertama dari tenant tersebut
        $admin = $tenant->users()->where('role', 'admin_rt')->first();
        
        if (!$admin) {
            return back()->with('error', 'Tenant ini belum memiliki Admin RT yang bisa digunakan untuk menyamar.');
        }

        // Simpan id superadmin asli
        session()->put('impersonated_by', Auth::id());
        session()->put('impersonating_tenant', $tenant->name);

        // Login sebagai Admin RT
        Auth::login($admin);

        return redirect()->route('dashboard')->with('success', "Berhasil menyamar sebagai Admin RT: {$tenant->name}");
    }

    public function leave()
    {
        if (!session()->has('impersonated_by')) {
            return redirect('/');
        }

        $superAdminId = session()->pull('impersonated_by');
        session()->forget('impersonating_tenant');

        $superAdmin = User::find($superAdminId);
        
        if ($superAdmin) {
            Auth::login($superAdmin);
            return redirect()->route('super-admin.index')->with('success', 'Berhasil keluar dari mode penyamaran.');
        }

        return redirect('/');
    }
}
