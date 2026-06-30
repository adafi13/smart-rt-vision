<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        $rwName = null;
        if ($request->has('ref')) {
            $rw = \App\Models\Rw::where('invite_code', $request->ref)->first();
            if ($rw) {
                $rwName = $rw->name;
            }
        }
        return view('auth.register', compact('rwName'));
    }

    /**
     * Handle an incoming registration request: creates a new RT (tenant)
     * together with its first pengurus (admin) account, on a 14-day trial.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'tenant_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
        ], [
            'terms.accepted' => 'Anda harus menyetujui Syarat & Ketentuan.',
        ]);

        $user = DB::transaction(function () use ($request) {
            $rw_id = null;
            if ($request->has('ref')) {
                $rw = \App\Models\Rw::where('invite_code', $request->ref)->first();
                if ($rw) {
                    $rw_id = $rw->id;
                }
            }

            $tenant = Tenant::create([
                'name' => $request->tenant_name,
                'slug' => $this->generateUniqueSlug($request->tenant_name),
                'email' => $request->email,
                'status' => 'trial',
                'trial_ends_at' => now()->addDays(14),
                'rw_id' => $rw_id,
            ]);

            return User::forceCreate([
                'tenant_id' => $tenant->id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin_rt',
                'tenant_role' => 'owner',
            ]);
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false))
            ->with('success', 'Selamat datang! Trial 14 hari Anda sudah aktif. Yuk mulai isi data warga.');
    }

    /**
     * Slug yang dilarang karena bertabrakan dengan rute sistem
     * (didaftarkan sebelum grup "/{tenant:slug}" di routes/web.php).
     */
    private const RESERVED_SLUGS = [
        'login', 'register', 'logout', 'dashboard', 'billing', 'profile',
        'super-admin', 'admin', 'kk', 'warga', 'export', 'webhook',
        'forgot-password', 'reset-password', 'verify-email', 'confirm-password',
    ];

    private function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'rt';
        $slug = $base;
        $i = 1;

        while (in_array($slug, self::RESERVED_SLUGS) || Tenant::where('slug', $slug)->exists()) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }
}
