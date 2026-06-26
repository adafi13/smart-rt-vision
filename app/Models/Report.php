<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'member_id',
        'kategori',
        'laporan',
        'foto_bukti',
        'status',
        'tanggapan_rt',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
