<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanicAlert extends Model
{
    use \App\Models\Concerns\BelongsToTenant;

    protected $fillable = [
        'reporter_name',
        'reporter_contact',
        'location',
        'type',
        'status',
        'resolved_by',
        'resolved_at',
        'resolution_note',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }}
