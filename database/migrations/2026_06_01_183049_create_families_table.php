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
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_kk', 16)->unique();
            $table->string('nama_kepala_keluarga');
            $table->string('alamat');
            $table->string('rt', 5);
            $table->string('rw', 5);
            $table->string('desa_kelurahan');
            $table->string('kecamatan');
            $table->string('kabupaten_kota');
            $table->string('provinsi');
            $table->string('kode_pos', 10)->nullable();
            $table->string('foto_path')->nullable();
            $table->enum('status_verifikasi', ['draft', 'terverifikasi'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('families');
    }
};
