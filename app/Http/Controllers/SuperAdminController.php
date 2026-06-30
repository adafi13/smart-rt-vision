<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function index(Request $request)
    {
        // Only for recent registrations on dashboard
        $tenants = Tenant::with([
            'subscriptions' => fn($q) => $q->latest()->limit(1)->with('plan'),
            'users' => fn($q) => $q->where('role', 'admin_rt')
                ->where(fn($q2) => $q2->where('tenant_role', 'owner')->orWhereNull('tenant_role'))
                ->limit(1),
        ])->withCount('families')->latest()->limit(8)->get();

        $activeTenantsStartOfMonth = Tenant::where('created_at', '<', now()->startOfMonth())->where('status', 'active')->count();
        $churnedThisMonth = Tenant::whereIn('status', ['expired', 'suspended'])
            ->where('updated_at', '>=', now()->startOfMonth())
            ->count();
        $churnRate = $activeTenantsStartOfMonth > 0 ? ($churnedThisMonth / $activeTenantsStartOfMonth) * 100 : 0;

        // Tenants expiring in next 7 days
        $expiringTenants = Tenant::with(['subscriptions' => fn($q) => $q->latest()->with('plan')])
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->where('status', 'trial')->whereBetween('trial_ends_at', [now(), now()->addDays(7)]);
                })->orWhereHas('subscriptions', function ($q3) {
                    $q3->where('status', 'active')->whereBetween('current_period_end', [now(), now()->addDays(7)]);
                });
            })
            ->get();

        // New tenants this month vs last month
        $newTenantsThisMonth = Tenant::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $newTenantsLastMonth = Tenant::whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->count();
        $tenantGrowth = $newTenantsLastMonth > 0 ? round((($newTenantsThisMonth - $newTenantsLastMonth) / $newTenantsLastMonth) * 100, 1) : 0;

        // Total members & families across all tenants (bypass tenant scope)
        $totalMembers  = \App\Models\Member::withoutGlobalScope(\App\Models\Scopes\TenantScope::class)->count();
        $totalFamilies = \App\Models\Family::withoutGlobalScope(\App\Models\Scopes\TenantScope::class)->count();

        // Activity logs today
        $logsToday = \App\Models\AuditLog::withoutGlobalScope(\App\Models\Scopes\TenantScope::class)
            ->whereDate('created_at', today())->count();

        // Revenue this month vs last month
        $revenueThisMonth = Subscription::whereNotNull('paid_at')
            ->whereMonth('paid_at', now()->month)->whereYear('paid_at', now()->year)->sum('amount');
        $revenueLastMonth = Subscription::whereNotNull('paid_at')
            ->whereMonth('paid_at', now()->subMonth()->month)->whereYear('paid_at', now()->subMonth()->year)->sum('amount');
        $revenueGrowth = $revenueLastMonth > 0 ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1) : 0;

        // Top 5 most active tenants (by family count)
        $topTenants = Tenant::withCount('families')->where('status', 'active')->orderByDesc('families_count')->limit(5)->get();

        // Tenant registrations per day last 14 days (for sparkline)
        $registrationTrend = collect(range(13, 0))->map(fn($d) => [
            'date'  => now()->subDays($d)->format('d M'),
            'count' => Tenant::whereDate('created_at', now()->subDays($d))->count(),
        ])->values();

        $stats = [
            'total'              => Tenant::count(),
            'trial'              => Tenant::where('status', 'trial')->count(),
            'active'             => Tenant::where('status', 'active')->count(),
            'expired'            => Tenant::whereIn('status', ['expired', 'suspended'])->count(),
            'mrr'                => Subscription::where('status', 'active')->where('current_period_end', '>', now())
                ->where('amount', '>', 0)
                ->join('plans', 'plans.id', '=', 'subscriptions.plan_id')->sum('plans.price_monthly'),
            'churn_rate'         => round($churnRate, 1),
            'total_revenue'      => Subscription::whereNotNull('paid_at')->sum('amount'),
            'revenue_this_month' => $revenueThisMonth,
            'revenue_growth'     => $revenueGrowth,
            'new_this_month'     => $newTenantsThisMonth,
            'tenant_growth'      => $tenantGrowth,
            'total_members'      => $totalMembers,
            'total_families'     => $totalFamilies,
            'logs_today'         => $logsToday,
            'total_ai_scans'     => Tenant::sum('ai_extractions_used'),
        ];

        // Build revenue chart data (last 6 months)
        $revenueChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->startOfMonth()->subMonths($i);
            $revenueChartData[] = [
                'label' => $month->translatedFormat('M Y'),
                'total' => Subscription::whereNotNull('paid_at')
                    ->whereMonth('paid_at', $month->month)->whereYear('paid_at', $month->year)->sum('amount'),
            ];
        }

        // Database Latency Check
        $start = microtime(true);
        \Illuminate\Support\Facades\DB::select('SELECT 1');
        $dbLatency = round((microtime(true) - $start) * 1000);

        return view('super-admin.index', compact(
            'stats', 'tenants', 'revenueChartData', 'expiringTenants',
            'topTenants', 'registrationTrend', 'newTenantsThisMonth', 'dbLatency'
        ));
    }

    public function tenants(Request $request)
    {
        $query = Tenant::with([
            'latestSubscription.plan',
            'owner',
        ])->withCount('families');

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhereHas('users', fn($u) => $u->where('email', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $tenants = $query->latest()->paginate(20)->withQueryString();

        $counts = [
            'all'       => Tenant::count(),
            'active'    => Tenant::where('status', 'active')->count(),
            'trial'     => Tenant::where('status', 'trial')->count(),
            'expired'   => Tenant::where('status', 'expired')->count(),
            'suspended' => Tenant::where('status', 'suspended')->count(),
        ];

        return view('super-admin.tenants', compact('tenants', 'counts'));
    }

    public function show(Tenant $tenant)
    {
        $tenant->load(['users', 'subscriptions.plan']);
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();

        // Owner = user admin_rt with owner/null tenant_role
        $ownerUser = $tenant->users()
            ->where('role', 'admin_rt')
            ->where(function ($q) {
                $q->where('tenant_role', 'owner')->orWhereNull('tenant_role');
            })
            ->first();

        $usage = [
            'kk'      => $tenant->families()->count(),
            'warga'   => \App\Models\Member::whereHas('family', fn($q) => $q->where('tenant_id', $tenant->id))->count(),
            'staff'   => $tenant->users()->where('role', 'admin_rt')->count(),
            'ai_used' => $tenant->ai_extractions_used,
        ];

        $activeSub = $tenant->activeSubscription();
        $isExpired = in_array($tenant->status, ['expired', 'suspended']);

        // Revenue and Transactions Data for ApoApps layout
        $revenueStats = [
            'total_revenue' => $tenant->subscriptions()->where('status', 'active')->sum('amount'),
            'trx_all_time' => $tenant->subscriptions()->where('status', 'active')->count(),
            'trx_this_month' => $tenant->subscriptions()->where('status', 'active')->whereMonth('paid_at', now()->month)->whereYear('paid_at', now()->year)->count(),
            'revenue_this_month' => $tenant->subscriptions()->where('status', 'active')->whereMonth('paid_at', now()->month)->whereYear('paid_at', now()->year)->sum('amount'),
        ];

        $staffs = $tenant->users()->where('role', 'admin_rt')->get();
        $recentTransactions = $tenant->subscriptions()->with('plan')->latest()->limit(5)->get();
        $histories = $tenant->subscriptions()->with('plan')->latest()->limit(3)->get(); // For right sidebar

        return view('super-admin.show', compact('tenant', 'plans', 'usage', 'ownerUser', 'activeSub', 'isExpired', 'revenueStats', 'staffs', 'recentTransactions', 'histories'));
    }
    public function edit(Tenant $tenant)
    {
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        $activeSub = $tenant->activeSubscription();
        return view('super-admin.edit', compact('tenant', 'plans', 'activeSub'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:trial,active,expired,suspended',
            'plan_id' => 'nullable|exists:plans,id',
            'current_period_end' => 'nullable|date',
        ]);

        $tenant->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        if ($request->plan_id && $request->current_period_end) {
            $activeSub = $tenant->activeSubscription();
            if ($activeSub) {
                // if same plan, just update end date
                if ($activeSub->plan_id == $request->plan_id) {
                    $activeSub->update(['current_period_end' => $request->current_period_end]);
                } else {
                    // end current sub and create new
                    $activeSub->update(['status' => 'expired']);
                    Subscription::forceCreate([
                        'tenant_id' => $tenant->id,
                        'plan_id' => $request->plan_id,
                        'status' => 'active',
                        'current_period_start' => now(),
                        'current_period_end' => $request->current_period_end,
                        'amount' => 0,
                        'paid_at' => now(),
                    ]);
                }
            } else {
                Subscription::forceCreate([
                    'tenant_id' => $tenant->id,
                    'plan_id' => $request->plan_id,
                    'status' => 'active',
                    'current_period_start' => now(),
                    'current_period_end' => $request->current_period_end,
                    'amount' => 0,
                    'paid_at' => now(),
                ]);
            }
        } elseif (!$request->plan_id) {
            // If they selected "Tanpa Paket", expire active sub
            if ($activeSub = $tenant->activeSubscription()) {
                $activeSub->update(['status' => 'expired']);
            }
        }

        return redirect()->route('super-admin.show', $tenant)->with('success', 'Data tenant berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Tenant $tenant)
    {
        $request->validate(['status' => 'required|in:trial,active,expired,suspended']);

        $tenant->update(['status' => $request->status]);

        return back()->with('success', "Status tenant {$tenant->name} diubah menjadi {$request->status}.");
    }

    public function grantSubscription(Request $request, Tenant $tenant)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'months' => 'required|integer|min:1|max:24',
        ]);

        $plan = Plan::find($request->plan_id);

        Subscription::forceCreate([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'current_period_start' => now(),
            'current_period_end' => now()->addMonths((int) $request->months),
            'amount' => 0,
            'paid_at' => now(),
        ]);

        $tenant->update(['status' => 'active']);

        return back()->with('success', "Subscription {$plan->name} ({$request->months} bulan) diberikan manual ke {$tenant->name}.");
    }

    public function resetOwnerPassword(Request $request, Tenant $tenant)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $owner = $tenant->users()
            ->where('role', 'admin_rt')
            ->where(function ($q) {
                $q->where('tenant_role', 'owner')->orWhereNull('tenant_role');
            })
            ->first();

        if (!$owner) {
            return back()->with('error', 'Owner RT tidak ditemukan.');
        }

        $owner->update(['password' => \Illuminate\Support\Facades\Hash::make($request->password)]);

        return back()->with('success', "Password Owner RT {$tenant->name} berhasil direset.");
    }
}
