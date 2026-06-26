<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Models\AnnouncementDismissal;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Bagikan active global announcements ke SEMUA view (seperti ApoApps)
        View::composer('*', function ($view) {
            if (! auth()->check()) {
                return;
            }

            $user = auth()->user();

            // Super Admin tidak melihat popup pengumuman mitra
            if ($user->is_super_admin) {
                return;
            }

            $role = $user->rt_role ?? 'owner';

            // Ambil ID yang sudah di-dismiss oleh user ini
            $dismissedIds = AnnouncementDismissal::where('user_id', $user->id)
                ->pluck('announcement_id')
                ->toArray();

            $announcements = Announcement::published()
                ->forRole($role)
                ->when(! empty($dismissedIds), fn ($q) => $q->whereNotIn('id', $dismissedIds))
                ->orderByDesc('created_at')
                ->get();

            $view->with('globalAnnouncements', $announcements);
        });
    }
}
