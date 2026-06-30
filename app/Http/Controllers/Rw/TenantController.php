<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Family;
use App\Models\Member;
use App\Models\Contribution;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    public function index()
    {
        $rw = auth()->user()->rw;
        $rts = Tenant::where('rw_id', $rw->id)
            ->withCount('families')
            ->with(['users' => fn($q) => $q->where('tenant_role', 'owner')->select('id', 'name', 'email', 'tenant_id'), 'subscriptions.plan', 'rtStaffs' => fn($q) => $q->orderBy('order_level')])
            ->latest()
            ->paginate(15);

        return view('rw.tenants.index', compact('rts', 'rw'));
    }

    public function create()
    {
        return view('rw.tenants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'rt_name'    => ['required', 'string', 'max:255'],
            'admin_name' => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $rw = auth()->user()->rw;

        $slug = $originalSlug = Str::slug($request->rt_name);
        $counter = 1;
        while (Tenant::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        DB::transaction(function () use ($request, $slug, $rw) {
            $tenant = Tenant::create([
                'name'   => $request->rt_name,
                'slug'   => $slug,
                'status' => 'active',
                'rw_id'  => $rw->id,
            ]);

            User::create([
                'name'        => $request->admin_name,
                'email'       => $request->email,
                'password'    => Hash::make($request->password),
                'role'        => 'admin_rt',
                'tenant_role' => 'owner',
                'tenant_id'   => $tenant->id,
            ]);
        });

        return redirect()->route('rw.tenants.index')->with('success', 'RT "' . $request->rt_name . '" berhasil didaftarkan!');
    }

    public function show(Tenant $tenant)
    {
        $rw = auth()->user()->rw;

        // Pastikan tenant milik RW ini
        if ($tenant->rw_id !== $rw->id) {
            abort(403);
        }

        $owner = $tenant->users()->where('tenant_role', 'owner')->first();
        $totalKK     = Family::where('tenant_id', $tenant->id)->count();
        $totalWarga  = Member::where('tenant_id', $tenant->id)->count();
        $pemasukan   = Contribution::where('tenant_id', $tenant->id)->sum('jumlah');
        $pengeluaran = Expense::where('tenant_id', $tenant->id)->sum('jumlah');
        $saldo       = $pemasukan - $pengeluaran;

        $recentFamilies = Family::where('tenant_id', $tenant->id)
            ->latest()->limit(5)->get();

        return view('rw.tenants.show', compact(
            'tenant', 'rw', 'owner',
            'totalKK', 'totalWarga', 'saldo',
            'pemasukan', 'pengeluaran', 'recentFamilies'
        ));
    }

    public function toggleStatus(Tenant $tenant)
    {
        $rw = auth()->user()->rw;
        if ($tenant->rw_id !== $rw->id) {
            abort(403);
        }

        $tenant->update([
            'status' => $tenant->status === 'active' ? 'inactive' : 'active'
        ]);

        $msg = $tenant->status === 'active' ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "RT \"{$tenant->name}\" berhasil {$msg}.");
    }

    public function adoptSearch(Request $request)
    {
        $rw    = auth()->user()->rw;
        $query = $request->get('q');
        $results = collect([]);

        if ($query) {
            $results = Tenant::where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('slug', 'like', "%{$query}%")
                      ->orWhereHas('users', function ($uq) use ($query) {
                          $uq->where('tenant_role', 'owner')
                             ->where('email', 'like', "%{$query}%");
                      });
                })
                ->withCount('families')
                ->limit(10)
                ->get();
        }

        return view('rw.tenants.adopt', compact('rw', 'results', 'query'));
    }

    public function adopt(Request $request, Tenant $tenant)
    {
        $request->validate([
            'token' => 'required|string|size:6'
        ], [
            'token.size' => 'Token harus terdiri dari 6 karakter.'
        ]);

        $rw = auth()->user()->rw;

        // Hanya bisa klaim RT yang belum punya RW
        if ($tenant->rw_id !== null) {
            return back()->with('error', 'RT ini sudah tergabung dengan RW lain.');
        }

        // Verifikasi token RT
        $tokenInput = strtoupper(trim($request->token));
        if (!$tenant->adoption_token || $tenant->adoption_token !== $tokenInput) {
            return back()->with('error', 'Token Gabung RT tidak valid atau belum di-generate.');
        }

        // Setelah diklaim, kita bisa hapus/reset tokennya agar tidak disalahgunakan
        $tenant->update([
            'rw_id' => $rw->id,
            'adoption_token' => null
        ]);

        return redirect()->route('rw.tenants.index')
            ->with('success', "RT \"{$tenant->name}\" berhasil diklaim ke dalam {$rw->name}!");
    }
}
