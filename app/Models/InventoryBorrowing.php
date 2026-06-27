<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryBorrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'inventory_id',
        'member_id',
        'borrower_name',
        'borrower_contact',
        'quantity',
        'borrow_date',
        'expected_return_date',
        'return_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'expected_return_date' => 'date',
        'return_date' => 'date',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'pending' => '<span class="px-2 py-1 bg-amber-50 text-amber-600 rounded-md text-xs font-medium border border-amber-200">Menunggu</span>',
            'approved' => '<span class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded-md text-xs font-medium border border-indigo-200">Dipinjam</span>',
            'returned' => '<span class="px-2 py-1 bg-emerald-50 text-emerald-600 rounded-md text-xs font-medium border border-emerald-200">Dikembalikan</span>',
            'rejected' => '<span class="px-2 py-1 bg-rose-50 text-rose-600 rounded-md text-xs font-medium border border-rose-200">Ditolak</span>',
            default => '<span class="px-2 py-1 bg-gray-50 text-gray-600 rounded-md text-xs font-medium border border-gray-200">Unknown</span>',
        };
    }
}
