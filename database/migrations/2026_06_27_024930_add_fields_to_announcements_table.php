<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->string('target_audience')->default('all')->after('type')->comment('all, owner, bendahara, sekretaris');
            $table->boolean('can_be_dismissed')->default(true)->after('is_active');
            $table->timestamp('starts_at')->nullable()->after('can_be_dismissed');
            $table->timestamp('ends_at')->nullable()->after('starts_at');
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn(['target_audience', 'can_be_dismissed', 'starts_at', 'ends_at']);
        });
    }
};
