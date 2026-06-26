<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('families', function (Blueprint $table) {
            $table->dropUnique('families_nomor_kk_unique');
            $table->unique(['tenant_id', 'nomor_kk']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('families', function (Blueprint $table) {
            $table->dropUnique(['tenant_id', 'nomor_kk']);
            $table->unique('nomor_kk');
        });
    }
};
