<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TenantSettingsController extends Controller
{
    public function index()
    {
        $tenant = auth()->user()->tenant;
        return view('admin.settings.index', compact('tenant'));
    }

    public function generateToken()
    {
        $tenant = auth()->user()->tenant;
        // Generate random 6 character alphanumeric token uppercase
        $token = strtoupper(Str::random(6));
        $tenant->update(['adoption_token' => $token]);

        return back()->with('success', 'Token Gabung RW berhasil dibuat.');
    }
}
