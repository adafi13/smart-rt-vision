<?php

use App\Models\Rw;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$rw = Rw::create([
    'name' => 'RW 05 Desa Mekar',
    'slug' => 'rw-05-mekar',
    'address' => 'Jalan Mekar Sari No. 1',
    'city' => 'Jakarta Selatan',
    'province' => 'DKI Jakarta',
]);

Tenant::query()->update(['rw_id' => $rw->id]);

User::updateOrCreate(
    ['email' => 'rw05@kaka.ai'],
    [
        'name' => 'Bapak Ketua RW 05',
        'password' => Hash::make('password123'),
        'role' => 'admin_rw',
        'rw_id' => $rw->id,
        'tenant_id' => null,
    ]
);

echo "Data berhasil dibuat!\n";
