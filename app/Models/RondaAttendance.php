<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RondaAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'ronda_schedule_id',
        'member_id',
        'clock_in',
        'location',
        'photo_path',
    ];

    protected $casts = [
        'clock_in' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function schedule()
    {
        return $this->belongsTo(RondaSchedule::class, 'ronda_schedule_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
