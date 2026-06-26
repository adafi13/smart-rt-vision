<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LifeEvent extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'member_id',
        'jenis_laporan',
        'nama_subjek',
        'tanggal_kejadian',
        'keterangan',
        'bukti_dokumen',
        'status_verifikasi',
    ];

    protected $casts = [
        'tanggal_kejadian' => 'date',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
