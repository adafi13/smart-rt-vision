<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

/**
 * Dijalankan via cron cPanel (mis. setiap hari): php artisan app:check-subscription-expiry
 * Tidak butuh queue worker — cocok untuk shared hosting.
 */
#[Signature('app:check-subscription-expiry')]
#[Description('Cek trial & subscription yang akan/sudah berakhir, lalu update status dan kirim notifikasi.')]
class CheckSubscriptionExpiry extends Command
{
    public function handle(): void
    {
        $this->warnTrialsEndingSoon();
        $this->expireFinishedTrials();
        $this->expireFinishedSubscriptions();
    }

    private function warnTrialsEndingSoon(): void
    {
        $tenants = Tenant::where('status', 'trial')
            ->whereBetween('trial_ends_at', [now(), now()->addDays(3)])
            ->get();

        foreach ($tenants as $tenant) {
            $this->notify($tenant, 'Trial Anda akan berakhir dalam beberapa hari', "Halo {$tenant->name}, trial Anda berakhir pada {$tenant->trial_ends_at->translatedFormat('d F Y')}. Segera pilih paket berlangganan agar layanan tidak terganggu.");
            $this->info("Reminder dikirim ke tenant #{$tenant->id} ({$tenant->name}) — trial berakhir {$tenant->trial_ends_at}.");
        }
    }

    private function expireFinishedTrials(): void
    {
        $tenants = Tenant::where('status', 'trial')->where('trial_ends_at', '<', now())->get();

        foreach ($tenants as $tenant) {
            $tenant->update(['status' => 'expired']);
            $this->notify($tenant, 'Trial Anda telah berakhir', "Halo {$tenant->name}, trial 14 hari Anda telah berakhir. Silakan pilih paket berlangganan di menu Paket & Tagihan untuk melanjutkan layanan.");
            $this->info("Tenant #{$tenant->id} ({$tenant->name}) diubah ke status expired (trial habis).");
        }
    }

    private function expireFinishedSubscriptions(): void
    {
        $subscriptions = Subscription::where('status', 'active')
            ->where('current_period_end', '<', now())
            ->get();

        foreach ($subscriptions as $subscription) {
            $subscription->update(['status' => 'expired']);

            $tenant = $subscription->tenant;
            if ($tenant && ! $tenant->activeSubscription()) {
                $tenant->update(['status' => 'expired']);
                $this->notify($tenant, 'Subscription Anda telah berakhir', "Halo {$tenant->name}, subscription Anda telah berakhir. Silakan perpanjang di menu Paket & Tagihan untuk melanjutkan layanan.");
            }

            $this->info("Subscription #{$subscription->id} (tenant #{$subscription->tenant_id}) diubah ke status expired.");
        }
    }

    private function notify(Tenant $tenant, string $subject, string $body): void
    {
        if (! $tenant->email) {
            return;
        }

        try {
            Mail::raw($body, function ($message) use ($tenant, $subject) {
                $message->to($tenant->email)->subject($subject);
            });
        } catch (\Throwable $e) {
            $this->warn("Gagal kirim email ke tenant #{$tenant->id}: {$e->getMessage()}");
        }
    }
}
