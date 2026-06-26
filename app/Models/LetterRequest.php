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
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
