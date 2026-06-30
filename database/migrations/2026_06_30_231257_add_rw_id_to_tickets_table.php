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
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable()->change();
            $table->foreignId('rw_id')->nullable()->constrained('rws')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable(false)->change();
            $table->dropForeign(['rw_id']);
            $table->dropColumn('rw_id');
        });
    }
};
