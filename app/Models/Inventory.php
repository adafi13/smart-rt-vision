<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'total_quantity',
        'condition',
        'image_path',
    
        'rw_id',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function borrowings()
    {
        return $this->hasMany(InventoryBorrowing::class);
    }

    // Hitung berapa stok yang sedang dipinjam (yang statusnya belum 'returned' atau 'rejected')
    public function getBorrowedQuantityAttribute()
    {
        return $this->borrowings()
            ->whereIn('status', ['pending', 'approved'])
            ->sum('quantity');
    }

    // Hitung sisa barang yang bisa dipinjam
    public function getAvailableQuantityAttribute()
    {
        return max(0, $this->total_quantity - $this->borrowed_quantity);
    }

    public function rw()
    {
        return $this->belongsTo(\App\Models\Rw::class, 'rw_id');
    }
}
