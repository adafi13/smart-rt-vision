<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LetterRequest extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'member_id',
        'jenis_surat',
        'keperluan',
        'status',
        'catatan_admin',
    
        'rw_status',
    
        'catatan_rw',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function rw()
    {
        return $this->belongsTo(\App\Models\Rw::class, 'rw_id');
    }
}
