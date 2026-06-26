<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'price_monthly' => 49000,
                'max_kk' => 50,
                'max_ai_extractions_per_month' => 30,
                'max_users' => 1,
                'is_popular' => false,
                'sort_order' => 1,
                'features' => [
                    'Hingga 50 Kartu Keluarga',
                    '30 ekstraksi foto KK dengan AI / bulan',
                    '1 akun pengurus',
                    'Portal mandiri warga',
                    'Transparansi kas RT',
                ],
            ],
            [
                'name' => 'Growth',
                'slug' => 'growth',
                'price_monthly' => 99000,
                'max_kk' => 200,
                'max_ai_extractions_per_month' => 100,
                'max_users' => 3,
                'is_popular' => true,
                'sort_order' => 2,
                'features' => [
                    'Hingga 200 Kartu Keluarga',
                    '100 ekstraksi foto KK dengan AI / bulan',
                    '3 akun pengurus',
                    'Semua fitur Starter',
                    'Import Excel data warga',
                    'Modul UMKM & Warta',
                ],
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'price_monthly' => 199000,
                'max_kk' => null,
                'max_ai_extractions_per_month' => null,
                'max_users' => null,
                'is_popular' => false,
                'sort_order' => 3,
                'features' => [
                    'Kartu Keluarga tanpa batas',
                    'Ekstraksi AI tanpa batas',
                    'Akun pengurus tanpa batas',
                    'Semua fitur Growth',
                    'Dukungan prioritas',
                ],
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
