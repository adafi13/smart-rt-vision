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
    ];

    protected $casts = [
        'tanggal_keluar' => 'date',
    ];
}
