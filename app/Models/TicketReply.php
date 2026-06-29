<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketReply extends Model
{
    protected $fillable = ['ticket_id', 'user_id', 'message', 'is_staff_reply', 'attachment'];

    protected $casts = [
        'is_staff_reply' => 'boolean',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        // withoutGlobalScopes() ensures super admin users (who have no tenant_id)
        // can also be loaded — prevents null when a staff reply is from super admin
        return $this->belongsTo(User::class)->withoutGlobalScopes();
    }
}
