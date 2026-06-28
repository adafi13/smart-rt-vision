<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BillingController extends Controller
{
    public function index()
    {
        $tenant = app('currentTenant');
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        
        // Active subscription logic
        $subscription = Subscription::where('tenant_id', $tenant->id)
            ->whereIn('status', ['active', 'pending_payment'])
            ->latest()
            ->first();

        // History of all transactions
        $histories = Subscription::where('tenant_id', $tenant->id)
            ->with('plan')
            ->latest()
            ->get();

        return view('billing.index', compact('tenant', 'plans', 'subscription', 'histories'));
    }

    public function checkout(Request $request, Plan $plan, XenditService $xendit)
    {
        if (! $xendit->isConfigured()) {
            return back()->with('error', 'Pembayaran online belum dikonfigurasi oleh penyedia layanan. Hubungi PT Sekawan Putra Pratama untuk aktivasi manual.');
        }

        $tenant = app('currentTenant');
        $user = auth()->user();
        
        // Check if there's already a pending payment for this plan
        $pending = Subscription::where('tenant_id', $tenant->id)
            ->where('plan_id', $plan->id)
            ->where('status', 'pending_payment')
            ->latest()
            ->first();

        if ($pending && $pending->payment_url) {
            return redirect($pending->payment_url);
        }

        $externalId = 'SUB-'.$tenant->id.'-'.now()->format('YmdHis');

        $subscription = Subscription::forceCreate([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => 'pending_payment',
            'payment_external_id' => $externalId,
            'amount' => $plan->price_monthly,
        ]);

        try {
            $result = $xendit->createInvoice(
                $externalId,
                $plan->price_monthly,
                "Subscription {$plan->name} - {$tenant->name}",
                ['email' => $user->email],
                route('billing.success', ['subscription' => $subscription->id]),
                route('billing.index')
            );
            
            // Save payment url to allow resuming
            $subscription->update([
                'payment_url' => $result['invoice_url']
            ]);
            
        } catch (\Throwable $e) {
            Log::error('Xendit checkout error: '.$e->getMessage());

            return back()->with('error', 'Gagal memproses pembayaran. Silakan coba lagi.');
        }

        return redirect($result['invoice_url']);
    }

    public function cancel(Subscription $subscription, XenditService $xendit)
    {
        if ($subscription->tenant_id != app('currentTenant')->id) {
            abort(403);
        }

        if ($subscription->status !== 'pending_payment') {
            return back()->with('error', 'Hanya tagihan berstatus menunggu pembayaran yang bisa dibatalkan.');
        }

        // Technically we can hit Xendit API to expire invoice here, but setting status to cancelled is enough locally
        $subscription->update(['status' => 'cancelled']);

        return back()->with('success', 'Tagihan berhasil dibatalkan.');
    }

    public function success(Subscription $subscription)
    {
        if ($subscription->tenant_id != app('currentTenant')->id) {
            abort(403);
        }

        // We load the plan as well
        $subscription->load('plan');

        return view('billing.success', compact('subscription'));
    }

    /**
     * Webhook callback dari Xendit (server-to-server, tanpa sesi/CSRF).
     */
    public function webhook(Request $request, XenditService $xendit)
    {
        if (! $xendit->isValidCallback($request->header('x-callback-token'))) {
            Log::warning('Xendit webhook: invalid callback token');

            return response()->json(['message' => 'invalid callback token'], 403);
        }

        $payload = $request->all();

        $subscription = Subscription::withoutGlobalScope(\App\Models\Scopes\TenantScope::class)
            ->where('payment_external_id', $payload['external_id'] ?? null)
            ->first();

        if (! $subscription) {
            return response()->json(['message' => 'order not found'], 404);
        }

        $status = $payload['status'] ?? null;

        if ($status === 'PAID' || $status === 'SETTLED') {
            $subscription->update([
                'status' => 'active',
                'payment_reference_id' => $payload['id'] ?? null,
                'current_period_start' => now(),
                'current_period_end' => now()->addDays(30),
                'paid_at' => now(),
            ]);

            $subscription->tenant->update(['status' => 'active']);
        } elseif (in_array($status, ['EXPIRED', 'FAILED'])) {
            $subscription->update(['status' => 'cancelled']);
        }

        return response()->json(['message' => 'ok']);
    }
}
