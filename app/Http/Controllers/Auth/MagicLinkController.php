<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class MagicLinkController extends Controller
{
    public function request()
    {
        return view('auth.magic-link');
    }

    public function send(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $url = URL::temporarySignedRoute(
                'magic-link.verify',
                now()->addMinutes(15),
                ['user' => $user->id]
            );

            Mail::send('emails.magic-link', ['url' => $url, 'user' => $user], function ($message) use ($user) {
                $message->to($user->email)->subject('Magic Link - SmartRT Vision');
            });
        }

        return back()->with('status', 'Jika email Anda terdaftar, kami telah mengirimkan tautan masuk rahasia.');
    }

    public function verify(Request $request, User $user)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Tautan telah kedaluwarsa atau tidak valid.');
        }

        Auth::login($user);
        
        return redirect()->intended(route('dashboard', absolute: false));
    }
}
