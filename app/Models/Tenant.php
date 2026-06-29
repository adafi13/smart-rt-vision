<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'ai_extractions_period_start' => 'date',
    ];

    public function onTrial(): bool
    {
        return $this->status === 'trial' && $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function isActive(): bool
    {
        return $this->status === 'active' || $this->onTrial();
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function families()
    {
        return $this->hasMany(Family::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function latestSubscription()
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    public function owner()
    {
        return $this->hasOne(User::class)
            ->where('role', 'admin_rt')
            ->where(function($q) {
                $q->where('tenant_role', 'owner')->orWhereNull('tenant_role');
            })
            ->ofMany('id', 'min');
    }

    public function activeSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('current_period_end', '>', now())
            ->latest('current_period_end')
            ->first();
    }

    /**
     * Plan yang berlaku saat ini: dari subscription aktif, atau plan Starter
     * selama masih trial (agar trial tetap punya batas yang wajar).
     * Null artinya tidak punya akses (trial habis & belum berlangganan).
     */
    public function effectivePlan(): ?Plan
    {
        if ($sub = $this->activeSubscription()) {
            return $sub->plan;
        }

        if ($this->onTrial()) {
            return new Plan([
                'name' => 'Trial (Uji Coba)',
                'slug' => 'trial',
                'description' => 'Paket uji coba gratis dengan fitur terbatas.',
                'price' => 0,
                'max_users' => 10,
                'max_kk' => 50,
                'max_ai_extractions_per_month' => 100,
                'features' => ['Fitur Dasar', '100x Scan AI'],
                'is_active' => true,
            ]);
        }

        return null;
    }

    public function canAddMoreKk(): bool
    {
        $plan = $this->effectivePlan();

        if (! $plan) {
            return false;
        }

        if ($plan->isUnlimitedKk()) {
            return true;
        }

        return $this->families()->count() < $plan->max_kk;
    }

    public function canRunAiExtraction(): bool
    {
        $plan = $this->effectivePlan();

        if (! $plan) {
            return false;
        }

        if ($plan->isUnlimitedAi()) {
            return true;
        }

        $this->resetAiUsageIfNewPeriod();

        return $this->ai_extractions_used < $plan->max_ai_extractions_per_month;
    }

    public function incrementAiUsage(): void
    {
        $this->resetAiUsageIfNewPeriod();
        $this->increment('ai_extractions_used');
    }

    private function resetAiUsageIfNewPeriod(): void
    {
        if (! $this->ai_extractions_period_start || $this->ai_extractions_period_start->lt(now()->startOfMonth())) {
            $this->forceFill([
                'ai_extractions_used' => 0,
                'ai_extractions_period_start' => now()->startOfMonth(),
            ])->save();
        }
    }

    public function canAddMoreUsers(): bool
    {
        $plan = $this->effectivePlan();

        if (! $plan) {
            return false;
        }

        if ($plan->isUnlimitedUsers()) {
            return true;
        }

        return $this->users()->count() < $plan->max_users;
    }
}
