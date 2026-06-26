<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Upgrade announcements table — add missing columns from ApoApps schema
        Schema::table('announcements', function (Blueprint $table) {
            // is_dismissible replaces can_be_dismissed for consistency
            if (!Schema::hasColumn('announcements', 'is_dismissible')) {
                $table->boolean('is_dismissible')->default(true)->after('is_active');
            }
            // Drop can_be_dismissed if it exists (from previous migration)
            if (Schema::hasColumn('announcements', 'can_be_dismissed')) {
                $table->dropColumn('can_be_dismissed');
            }
            // Change target_audience → target for consistency with ApoApps
            if (Schema::hasColumn('announcements', 'target_audience') && !Schema::hasColumn('announcements', 'target')) {
                $table->string('target')->default('all')->after('type');
                $table->dropColumn('target_audience');
            }
            // Add type success
            // Add created_by
            if (!Schema::hasColumn('announcements', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('ends_at');
            }
        });

        // Create announcement_dismissals tracking table
        Schema::create('announcement_dismissals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('announcement_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('dismissed_at')->useCurrent();
            $table->unique(['announcement_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcement_dismissals');
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn(['is_dismissible', 'created_by']);
            if (Schema::hasColumn('announcements', 'target')) {
                $table->dropColumn('target');
            }
        });
    }
};
