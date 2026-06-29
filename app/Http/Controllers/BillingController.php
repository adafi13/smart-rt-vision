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

        // Active Coupons to show promo banners
        $activeCoupons = \App\Models\Coupon::where('is_active', true)
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->where(function($q) {
                $q->whereNull('max_uses')
                  ->orWhereRaw('used_count < max_uses');
            })
            ->get();

        return view('billing.index', compact('tenant', 'plans', 'subscription', 'histories', 'activeCoupons'));
    }

    public function checkout(Request $request, Plan $plan, XenditService $xendit)
    {
        if (! $xendit->isConfigured()) {
            return back()->with('error', 'Pembayaran online belum dikonfigurasi oleh penyedia layanan. Hubungi PT Sekawan Putra Pratama untuk aktivasi manual.');
        }

        $tenant = app('currentTenant');
        $user = auth()->user();
        $externalId = 'SUB-'.$tenant->id.'-'.now()->format('YmdHis');

        $cycle = $request->query('cycle', 'monthly');
        $amount = $cycle === 'yearly' ? $plan->price_yearly : $plan->price_monthly;

        $coupon = null;
        if ($request->filled('coupon_code')) {
            $code = strtoupper(trim($request->coupon_code));
            $coupon = \App\Models\Coupon::where('code', $code)->where('is_active', true)->first();
            
            if (!$coupon) {
                return back()->with('error', 'Kode promo tidak valid atau tidak aktif.');
            }
            if ($coupon->expires_at && $coupon->expires_at->isPast()) {
                return back()->with('error', 'Kode promo sudah kedaluwarsa.');
            }
            if ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) {
                return back()->with('error', 'Kuota penggunaan kode promo sudah habis.');
            }
            
            if ($coupon->discount_type === 'percent') {
                $discount = $amount * ($coupon->discount_value / 100);
                $amount -= $discount;
            } else {
                $amount -= $coupon->discount_value;
            }
            
            if ($amount < 0) {
                $amount = 0;
            }
            $amount = (int) $amount;
        }

        // Check if there's already a pending payment for this plan
        $pending = Subscription::where('tenant_id', $tenant->id)
            ->where('plan_id', $plan->id)
            ->where('status', 'pending_payment')
            ->latest()
            ->first();

        if ($pending) {
            // Cancel old pending payment so they can use the new coupon
            $pending->update(['status' => 'cancelled']);
        }

        $subscription = Subscription::forceCreate([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => $amount <= 0 ? 'active' : 'pending_payment',
            'billing_cycle' => $cycle,
            'payment_external_id' => $externalId,
            'amount' => $amount,
            'coupon_id' => $coupon ? $coupon->id : null,
        ]);

        if ($amount <= 0) {
            // It's completely free! Bypass Xendit
            $periodEnd = $cycle === 'yearly' ? now()->addYears(1) : now()->addDays(30);
            $subscription->update([
                'paid_at' => now(),
                'current_period_start' => now(),
                'current_period_end' => $periodEnd,
            ]);
            $tenant->update(['status' => 'active']);
            
            if ($coupon) {
                $coupon->increment('used_count');
            }
            
            return redirect()->route('billing.success', ['subscription' => $subscription->id])->with('success', 'Paket berhasil diaktifkan dengan kode promo!');
        }

        $desc = "Subscription {$plan->name} ({$cycle}) - {$tenant->name}";
        if ($coupon) {
            $desc .= " (Promo: {$coupon->code})";
        }

        try {
            $result = $xendit->createInvoice(
                $externalId,
                $amount,
                $desc,
                ['email' => $user->email],
                route('billing.success', ['subscription' => $subscription->id]),
                route('billing.index')
            );
            
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
            // Prevent double processing
            if ($subscription->status !== 'active') {
                $periodEnd = $subscription->billing_cycle === 'yearly' 
                    ? now()->addYears(1) 
                    : now()->addDays(30);

                $subscription->update([
                    'status' => 'active',
                    'payment_reference_id' => $payload['id'] ?? null,
                    'paid_at' => now(),
                    'current_period_start' => now(),
                    'current_period_end' => $periodEnd,
                ]);

                $subscription->tenant->update(['status' => 'active']);

                if ($subscription->coupon_id) {
                    \App\Models\Coupon::where('id', $subscription->coupon_id)->increment('used_count');
                }
            }
        } elseif (in_array($status, ['EXPIRED', 'FAILED'])) {
            $subscription->update(['status' => 'cancelled']);
        }

        return response()->json(['message' => 'ok']);
    }
}
