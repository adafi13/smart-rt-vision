<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('sort_order')->get();
        return view('super-admin.plans.index', compact('plans'));
    }

    public function create()
    {
        $plan = new Plan();
        return view('super-admin.plans.form', compact('plan'));
    }

    public function store(Request $request)
    {
        $data = $this->validatePlan($request);
        Plan::create($data);

        return redirect()->route('super-admin.plans.index')->with('success', 'Paket berlangganan berhasil ditambahkan.');
    }

    public function edit(Plan $plan)
    {
        return view('super-admin.plans.form', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $this->validatePlan($request);
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

    private function validatePlan(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug,' . ($request->route('plan') ? $request->route('plan')->id : ''),
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'max_kk' => 'nullable|integer|min:1',
            'max_ai_extractions_per_month' => 'nullable|integer|min:1',
            'features' => 'nullable|string',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'required|integer',
        ]);

        // Convert features from newline-separated string back to array for storage
        if (!empty($data['features'])) {
            $data['features'] = array_map('trim', explode("\n", trim($data['features'])));
            $data['features'] = array_filter($data['features']); // remove empty lines
        } else {
            $data['features'] = [];
        }

        return $data;
    }
}
