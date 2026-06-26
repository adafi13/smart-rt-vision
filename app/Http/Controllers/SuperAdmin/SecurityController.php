<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SecurityController extends Controller
{
    public function index()
    {
        return view('super-admin.security.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email,' . auth()->id(),
            'current_password' => 'nullable|string',
            'password'         => 'nullable|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
            }
        }

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            if (!$request->filled('current_password')) {
                return back()->withErrors(['current_password' => 'Masukkan password saat ini untuk mengubah password.']);
            }
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Profil dan keamanan akun berhasil diperbarui.');
    }
}
