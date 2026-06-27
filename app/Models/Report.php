<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'ticket_number',
        'member_id',
        'reporter_name',
        'reporter_phone',
        'kategori',
        'laporan',
        'foto_bukti',
        'status',
        'tanggapan_rt', // Kept for backward compat
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function replies()
    {
        return $this->hasMany(ReportReply::class)->orderBy('created_at', 'asc');
    }
}
