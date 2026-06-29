<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::withCount(['subscriptions' => function ($query) {
            $query->where('status', 'active')->where('current_period_end', '>', now());
        }])->orderBy('sort_order')->get();
        return view('super-admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('super-admin.plans.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatePlan($request);
        Plan::create($data);

        return redirect()->route('super-admin.plans.index')->with('success', 'Paket berlangganan berhasil ditambahkan.');
    }

    public function edit(Plan $plan)
    {
        return view('super-admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $this->validatePlan($request, $plan);
        $plan->update($data);

        return redirect()->route('super-admin.plans.index')->with('success', 'Paket berlangganan berhasil diperbarui.');
    }

    public function destroy(Plan $plan)
    {
        if ($plan->subscriptions()->exists()) {
            return back()->with('error', 'Paket tidak bisa dihapus karena masih ada tenant yang berlangganan pada paket ini. Anda bisa menonaktifkannya saja.');
        }

        $plan->delete();
        return redirect()->route('super-admin.plans.index')->with('success', 'Paket berlangganan berhasil dihapus.');
    }

    private function validatePlan(Request $request, ?Plan $plan = null)
    {
        $planId = $plan ? $plan->id : '';
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug,' . $planId,
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'max_kk' => 'nullable|integer|min:1',
            'max_users' => 'nullable|integer|min:1',
            'max_ai_extractions_per_month' => 'nullable|integer|min:1',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'required|integer',
        ]);

        $data['is_popular'] = $request->boolean('is_popular');
        $data['is_active'] = $request->boolean('is_active');
        $data['features'] = []; // Modul eksklusif dihilangkan, selalu kosong

        return $data;
    }
}
