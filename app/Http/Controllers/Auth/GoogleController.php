<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal masuk menggunakan Google. Silakan coba lagi.');
        }

        // Check if a user with this email already exists
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Update the user's google_id and avatar if they logged in with a regular account previously
            if (! $user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }
        } else {
            // Create a new user if they don't exist
            $user = User::create([
                'name' => $googleUser->getName() ?? 'Pengguna',
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(24)),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'role' => 'admin_rt', // Defaulting new Google signups to admin_rt role
                'tenant_role' => 'owner', // Default owner
            ]);
        }

        // Log the user in
        Auth::login($user, true); // True to remember the user session

        // Redirect based on role
        if ($user->is_super_admin) {
            return redirect()->intended(route('super-admin.index', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
