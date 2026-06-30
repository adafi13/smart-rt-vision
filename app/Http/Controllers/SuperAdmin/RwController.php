<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Rw;
use Illuminate\Http\Request;

class RwController extends Controller
{
    public function index(Request $request)
    {
        $query = Rw::withCount(['tenants as rts_count', 'users as admins_count']);

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('province', 'like', "%{$search}%")
                  ->orWhereHas('users', fn($u) => $u->where('email', 'like', "%{$search}%"));
            });
        }

        $rws = $query->latest()->paginate(20)->withQueryString();

        $counts = [
            'total' => Rw::count(),
        ];

        return view('super-admin.rws.index', compact('rws', 'counts'));
    }

    public function show(Rw $rw)
    {
        $rw->load(['users', 'tenants.latestSubscription.plan']);
        $rw->loadCount('tenants');

        $activeRtsCount = $rw->tenants->where('status', 'active')->count();
        $trialRtsCount = $rw->tenants->where('status', 'trial')->count();
        $expiredRtsCount = $rw->tenants->whereIn('status', ['expired', 'suspended'])->count();

        // Calculate estimated MRR from active subscriptions under this RW
        $estimatedMrr = 0;
        foreach ($rw->tenants as $rt) {
            $sub = $rt->activeSubscription();
            if ($sub && $sub->plan) {
                $estimatedMrr += $sub->plan->price_monthly;
            }
        }

        return view('super-admin.rws.show', compact('rw', 'activeRtsCount', 'trialRtsCount', 'expiredRtsCount', 'estimatedMrr'));
    }
}
