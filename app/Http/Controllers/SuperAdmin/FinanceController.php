<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index()
    {
        // Total Revenue All Time
        $totalRevenue = Subscription::whereNotNull('paid_at')->sum('amount');

        // Revenue this month
        $revenueThisMonth = Subscription::whereNotNull('paid_at')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        // Revenue last month
        $revenueLastMonth = Subscription::whereNotNull('paid_at')
            ->whereMonth('paid_at', now()->subMonth()->month)
            ->whereYear('paid_at', now()->subMonth()->year)
            ->sum('amount');

        // Build last 12 months array
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            // Gunakan startOfMonth agar tidak ada bug lompat bulan pada tanggal 31
            $month = now()->startOfMonth()->subMonths($i);
            $months[] = [
                'label' => $month->translatedFormat('M Y'),
                'total' => Subscription::whereNotNull('paid_at')
                    ->whereMonth('paid_at', $month->month)
                    ->whereYear('paid_at', $month->year)
                    ->sum('amount'),
            ];
        }

        // Revenue breakdown by plan (SQLite-compatible)
        $byPlan = Subscription::with('plan')
            ->whereNotNull('paid_at')
            ->select('plan_id', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('plan_id')
            ->get();

        // MRR (Monthly Recurring Revenue)
        // Hanya hitung langganan aktif yang benar-benar berbayar (amount > 0)
        // dan menghindari langganan manual (bypass) yang amount = 0
        $mrr = Subscription::where('status', 'active')
            ->where('current_period_end', '>', now())
            ->where('amount', '>', 0)
            ->join('plans', 'plans.id', '=', 'subscriptions.plan_id')
            ->sum('plans.price_monthly');

        // AI Cost Analytics
        $aiCostPerScan = (int) \App\Models\Setting::get('ai_cost_per_scan', 150, 'finance');
        $totalAiScans = Tenant::sum('ai_extractions_used');
        $estimatedAiCost = $totalAiScans * $aiCostPerScan;

        $topAiTenants = Tenant::with(['owner', 'rw'])
            ->where('ai_extractions_used', '>', 0)
            ->orderByDesc('ai_extractions_used')
            ->take(5)
            ->get();

        $stats = compact('totalRevenue', 'revenueThisMonth', 'revenueLastMonth', 'mrr', 'months', 'byPlan', 'totalAiScans', 'estimatedAiCost', 'aiCostPerScan', 'topAiTenants');

        return view('super-admin.finance.index', compact('stats'));
    }
}
