<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Plan;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $results = [];

        // Search Tenants
        $tenants = Tenant::where('name', 'like', "%{$query}%")
                    ->orWhere('slug', 'like', "%{$query}%")
                    ->take(5)
                    ->get();
        
        foreach ($tenants as $tenant) {
            $results[] = [
                'type' => 'Tenant (RT)',
                'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>',
                'title' => $tenant->name,
                'subtitle' => $tenant->slug . ' - ' . ($tenant->status === 'active' ? 'Aktif' : 'Trial'),
                'url' => route('super-admin.show', $tenant)
            ];
        }

        // Search Plans
        $plans = Plan::where('name', 'like', "%{$query}%")
                    ->take(3)
                    ->get();
                    
        foreach ($plans as $plan) {
            $results[] = [
                'type' => 'Paket',
                'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>',
                'title' => $plan->name,
                'subtitle' => 'Rp ' . number_format($plan->price_monthly, 0, ',', '.'),
                'url' => route('super-admin.plans.index')
            ];
        }

        return response()->json($results);
    }
}
