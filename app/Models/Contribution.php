<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contribution extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'family_id',
        'jenis_iuran',
        'jumlah',
        'periode',
        'tanggal_bayar',
        'keterangan',
    ];

    protected $casts = [
        'periode' => 'date',
        'tanggal_bayar' => 'date',
    ];

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }
}
