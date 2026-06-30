<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'keterangan',
        'jumlah',
        'tanggal_keluar',
        'kategori',
        'bukti_nota',
    
        'rw_id',
    ];

    protected $casts = [
        'tanggal_keluar' => 'date',
    ];

    public function rw()
    {
        return $this->belongsTo(\App\Models\Rw::class, 'rw_id');
    }
}
