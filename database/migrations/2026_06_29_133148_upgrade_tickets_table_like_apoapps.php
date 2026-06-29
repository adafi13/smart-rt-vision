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
            $table->string('ticket_number')->nullable()->after('id')->unique();
            $table->string('category')->default('general')->after('subject'); // technical, billing, general, feature_request
            $table->foreignId('assigned_to')->nullable()->after('priority')->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable()->after('assigned_to');
        });

        Schema::table('ticket_replies', function (Blueprint $table) {
            $table->boolean('is_staff_reply')->default(false)->after('message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropColumn(['ticket_number', 'category', 'assigned_to', 'resolved_at']);
        });

        Schema::table('ticket_replies', function (Blueprint $table) {
            $table->dropColumn('is_staff_reply');
        });
    }
};
