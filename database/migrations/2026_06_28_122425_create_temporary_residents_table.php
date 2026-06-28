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
        Schema::create('temporary_residents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('nama_pemilik');
            $table->string('alamat_kos');
            $table->string('nama_penghuni');
            $table->string('nik_penghuni', 16);
            $table->string('foto_ktp');
            $table->enum('status', ['Pending', 'Terverifikasi', 'Ditolak'])->default('Pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_residents');
    }
};
