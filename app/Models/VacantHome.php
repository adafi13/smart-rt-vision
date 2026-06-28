<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacantHome extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'pelapor_nama',
        'alamat_rumah',
        'nomor_wa',
        'tanggal_pergi',
        'tanggal_pulang',
        'catatan_warga',
        'status',
    ];

    protected $casts = [
        'tanggal_pergi' => 'date',
        'tanggal_pulang' => 'date',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function logs()
    {
        return $this->hasMany(VacantHomeLog::class)->orderBy('waktu_patroli', 'desc');
    }
}
