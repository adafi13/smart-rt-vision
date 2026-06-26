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
        Schema::create('life_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->string('jenis_laporan');
            $table->string('nama_subjek');
            $table->date('tanggal_kejadian');
            $table->text('keterangan')->nullable();
            $table->string('bukti_dokumen')->nullable();
            $table->string('status_verifikasi')->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('life_events');
    }
};
