<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Rw;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class RwRegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register-rw');
    }

    public function store(Request $request)
    {
        $request->validate([
            'rw_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Generate unique slug for RW
        $slug = Str::slug($request->rw_name);
        $originalSlug = $slug;
        $counter = 1;
        while (Rw::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Create RW
        $rw = Rw::create([
            'name' => $request->rw_name,
            'slug' => $slug,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
        ]);

        // Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin_rw',
            'rw_id' => $rw->id,
            'tenant_id' => null,
        ]);

        Auth::login($user);

        return redirect()->route('rw.dashboard');
    }
}
