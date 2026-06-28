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
        Schema::create('vacant_homes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('pelapor_nama');
            $table->string('alamat_rumah');
            $table->string('nomor_wa');
            $table->date('tanggal_pergi');
            $table->date('tanggal_pulang');
            $table->text('catatan_warga')->nullable();
            $table->enum('status', ['Aktif', 'Selesai'])->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacant_homes');
    }
};
