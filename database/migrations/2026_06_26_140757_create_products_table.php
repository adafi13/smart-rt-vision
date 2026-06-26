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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->string('penjual');
            $table->string('whatsapp');
            $table->decimal('harga', 12, 2)->nullable();
            $table->string('kategori');
            $table->text('deskripsi')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('is_ready')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
