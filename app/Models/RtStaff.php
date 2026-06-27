<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RtStaff extends Model
{
    use HasFactory;

    protected $table = 'rt_staff';

    protected $fillable = [
        'tenant_id',
        'name',
        'position',
        'order_level',
        'photo',
        'phone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_level' => 'integer',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
