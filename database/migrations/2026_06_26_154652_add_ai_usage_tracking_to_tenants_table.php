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
        Schema::table('tenants', function (Blueprint $table) {
            $table->unsignedInteger('ai_extractions_used')->default(0)->after('trial_ends_at');
            $table->date('ai_extractions_period_start')->nullable()->after('ai_extractions_used');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['ai_extractions_used', 'ai_extractions_period_start']);
        });
    }
};
