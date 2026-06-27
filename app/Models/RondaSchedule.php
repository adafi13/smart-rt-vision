<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RondaSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'member_id',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function attendance()
    {
        return $this->hasOne(RondaAttendance::class);
    }
}
