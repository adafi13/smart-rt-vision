<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'user_id', 'ticket_number', 'category', 'subject', 'status', 'priority', 'assigned_to', 'resolved_at'
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function getStatusLabelAttribute(): array
    {
        return match ($this->status) {
            'open' => ['label' => 'Open', 'text' => 'text-rose-600', 'bg' => 'bg-rose-50', 'border' => 'border-rose-200'],
            'answered' => ['label' => 'Answered', 'text' => 'text-amber-600', 'bg' => 'bg-amber-50', 'border' => 'border-amber-200'],
            'resolved' => ['label' => 'Resolved', 'text' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'border' => 'border-emerald-200'],
            'closed' => ['label' => 'Closed', 'text' => 'text-slate-500', 'bg' => 'bg-slate-100', 'border' => 'border-slate-200'],
            default => ['label' => $this->status, 'text' => 'text-slate-500', 'bg' => 'bg-slate-100', 'border' => 'border-slate-200'],
        };
    }

    public function getPriorityLabelAttribute(): array
    {
        return match ($this->priority) {
            'low' => ['label' => 'Rendah', 'text' => 'text-slate-500', 'bg' => 'bg-slate-50', 'dot' => 'bg-slate-400', 'border' => 'border-slate-200'],
            'normal' => ['label' => 'Normal', 'text' => 'text-indigo-600', 'bg' => 'bg-indigo-50', 'dot' => 'bg-indigo-500', 'border' => 'border-indigo-200'],
            'high' => ['label' => 'Tinggi', 'text' => 'text-amber-600', 'bg' => 'bg-amber-50', 'dot' => 'bg-amber-500', 'border' => 'border-amber-200'],
            'urgent' => ['label' => 'Urgent', 'text' => 'text-rose-600', 'bg' => 'bg-rose-50', 'dot' => 'bg-rose-500', 'border' => 'border-rose-200'],
            default => ['label' => $this->priority, 'text' => 'text-slate-500', 'bg' => 'bg-slate-50', 'dot' => 'bg-slate-400', 'border' => 'border-slate-200'],
        };
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'technical' => 'Kendala Teknis',
            'billing' => 'Tagihan & Pembayaran',
            'general' => 'Pertanyaan Umum',
            'feature_request' => 'Permintaan Fitur',
            default => 'Lainnya',
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }
}
