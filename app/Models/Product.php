<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'nama_produk',
        'penjual',
        'whatsapp',
        'harga',
        'kategori',
        'deskripsi',
        'foto',
        'is_ready',
    ];

    protected $casts = [
        'is_ready' => 'boolean',
    ];
}
