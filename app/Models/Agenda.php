<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'created_by',
        'title',
        'description',
        'start_time',
        'end_time',
        'location',
        'type',
    
        'rw_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function rw()
    {
        return $this->belongsTo(\App\Models\Rw::class, 'rw_id');
    }
}
