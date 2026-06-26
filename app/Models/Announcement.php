<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Announcement extends Model
{
    protected $guarded = ['id'];

    protected $appends = ['type_label', 'status_label'];

    protected $casts = [
        'is_active'      => 'boolean',
        'is_dismissible' => 'boolean',
        'starts_at'      => 'datetime',
        'ends_at'        => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function dismissals()
    {
        return $this->hasMany(AnnouncementDismissal::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    /** Hanya yang aktif dan dalam periode tayang */
    public function scopePublished($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    /** Alias untuk scopePublished (backward compat) */
    public function scopeActive($query)
    {
        return $this->scopePublished($query);
    }

    /** Scope berdasarkan target role */
    public function scopeForRole($query, $role)
    {
        return $query->where(function ($q) use ($role) {
            $q->where('target', 'all')->orWhere('target', $role);
        });
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /** Cek apakah sudah di-dismiss oleh user tertentu */
    public function isDismissedBy($userId): bool
    {
        return $this->dismissals()->where('user_id', $userId)->exists();
    }

    // ─── Computed Attributes (for x-data JSON) ────────────────────────

    public function getTypeLabelAttribute(): array
    {
        return match ($this->type) {
            'info'    => [
                'label'      => 'Info',
                'bg'         => 'bg-blue-50',
                'text'       => 'text-blue-700',
                'border'     => 'border-blue-200',
                'icon_color' => 'text-blue-500',
            ],
            'warning' => [
                'label'      => 'Peringatan',
                'bg'         => 'bg-amber-50',
                'text'       => 'text-amber-700',
                'border'     => 'border-amber-200',
                'icon_color' => 'text-amber-500',
            ],
            'success' => [
                'label'      => 'Kabar Baik',
                'bg'         => 'bg-emerald-50',
                'text'       => 'text-emerald-700',
                'border'     => 'border-emerald-200',
                'icon_color' => 'text-emerald-500',
            ],
            'danger'  => [
                'label'      => 'Penting',
                'bg'         => 'bg-red-50',
                'text'       => 'text-red-700',
                'border'     => 'border-red-200',
                'icon_color' => 'text-red-500',
            ],
            default   => [
                'label'      => 'Info',
                'bg'         => 'bg-slate-50',
                'text'       => 'text-slate-700',
                'border'     => 'border-slate-200',
                'icon_color' => 'text-slate-500',
            ],
        };
    }

    public function getStatusLabelAttribute(): string
    {
        if (! $this->is_active) return 'Nonaktif';
        if ($this->starts_at && $this->starts_at->isFuture()) return 'Terjadwal';
        if ($this->ends_at   && $this->ends_at->isPast())    return 'Kedaluwarsa';
        return 'Aktif';
    }
}
