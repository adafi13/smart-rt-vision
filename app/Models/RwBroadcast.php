<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RwBroadcast extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function rw()
    {
        return $this->belongsTo(Rw::class);
    }
}
