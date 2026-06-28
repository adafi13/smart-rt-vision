<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacantHomeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'vacant_home_id',
        'petugas_id',
        'petugas_nama',
        'waktu_patroli',
        'foto_bukti',
        'catatan_petugas',
    ];

    protected $casts = [
        'waktu_patroli' => 'datetime',
    ];

    public function vacantHome()
    {
        return $this->belongsTo(VacantHome::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
