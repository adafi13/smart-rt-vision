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
        Schema::table('members', function (Blueprint $table) {
            $table->unsignedInteger('nomor_surat')->nullable()->after('kewarganegaraan');
            $table->string('status_warga')->default('Aktif')->after('nomor_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['nomor_surat', 'status_warga']);
        });
    }
};
