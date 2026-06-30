<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rw extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function broadcasts(): HasMany
    {
        return $this->hasMany(RwBroadcast::class);
    }

    protected static function booted()
    {
        static::creating(function ($rw) {
            if (empty($rw->invite_code)) {
                $rw->invite_code = \Illuminate\Support\Str::random(10);
            }
        });
    }
}
