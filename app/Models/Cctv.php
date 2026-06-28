<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cctv extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'location',
        'stream_url',
        'status',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
