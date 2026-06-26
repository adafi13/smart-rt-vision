<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'judul',
        'slug',
        'kategori',
        'isi',
        'gambar',
        'is_penting',
    ];

    protected $casts = [
        'is_penting' => 'boolean',
    ];
}
