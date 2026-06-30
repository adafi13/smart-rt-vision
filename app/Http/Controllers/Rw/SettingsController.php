<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $rw = auth()->user()->rw;
        return view('rw.settings', compact('rw'));
    }

    public function update(Request $request)
    {
        $rw = auth()->user()->rw;

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'address'  => ['required', 'string', 'max:500'],
            'city'     => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
        ]);

        $rw->update($request->only('name', 'address', 'city', 'province'));

        return back()->with('success', 'Informasi organisasi RW berhasil diperbarui.');
    }
}
