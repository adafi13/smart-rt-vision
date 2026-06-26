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

        // Build last 12 months array (loop per-month query — SQLite-compatible)
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
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

        // MRR
        $mrr = Subscription::where('status', 'active')
            ->where('current_period_end', '>', now())
            ->join('plans', 'plans.id', '=', 'subscriptions.plan_id')
            ->sum('plans.price_monthly');

        $stats = compact('totalRevenue', 'revenueThisMonth', 'revenueLastMonth', 'mrr', 'months', 'byPlan');

        return view('super-admin.finance.index', compact('stats'));
    }
}
