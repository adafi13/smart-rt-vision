<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Models\AnnouncementDismissal;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Global Audit Logging
        Event::listen('eloquent.*', function ($eventName, array $data) {
            if (app()->runningInConsole()) return;
            
            // Only listen to created, updated, deleted
            if (!preg_match('/^eloquent\.(created|updated|deleted)\:\s(.+)$/', $eventName, $matches)) {
                return;
            }
        
            $actionType = $matches[1];
            $modelClass = $matches[2];
            $model = $data[0] ?? null;
        
            if (!$model || !is_object($model)) return;
        
            // Ignore AuditLog itself
            if ($model instanceof \App\Models\AuditLog) return;
            // Ignore system/Laravel models and jobs
            if (str_starts_with($modelClass, 'Laravel\\')) return;
            if (str_starts_with($modelClass, 'Illuminate\\')) return;
            // Ignore sessions, temporary items if any
        
            // Determine tenant
            $tenantId = $model->tenant_id ?? (auth()->check() ? auth()->user()->tenant_id : null);
            if (!$tenantId && app()->bound('currentTenant')) {
                 $tenantId = app('currentTenant')->id ?? null;
            }

            // Determine RW
            $rwId = $model->rw_id ?? (auth()->check() ? auth()->user()->rw_id : null);

            // Jika tidak ada tenant_id dan rw_id, abaikan log
            if (!$tenantId && !$rwId) return;

            $user = auth()->user();

            $oldValues = [];
            $newValues = [];

            if ($actionType === 'updated') {
                $oldValues = $model->getOriginal();
                $newValues = $model->getChanges();
                unset($oldValues['updated_at'], $newValues['updated_at']);
                if (empty($newValues)) return; // No actual data changed
            } elseif ($actionType === 'created') {
                $newValues = $model->getAttributes();
            } elseif ($actionType === 'deleted') {
                $oldValues = $model->getAttributes();
            }

            try {
                \App\Models\AuditLog::create([
                    'tenant_id' => $tenantId,
                    'rw_id'     => $rwId,
                    'user_id' => $user ? $user->id : null,
                    'action' => $actionType . '_' . strtolower(class_basename($model)),
                    'model_type' => $modelClass,
                    'model_id' => $model->id ?? null,
                    'old_values' => $oldValues,
                    'new_values' => $newValues,
                    'ip_address' => Request::ip(),
                    'user_agent' => Request::userAgent(),
                ]);
            } catch (\Exception $e) {
                // Fail silently so it doesn't break user experience
                \Illuminate\Support\Facades\Log::error('Failed to log audit: ' . $e->getMessage());
            }
        });
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
