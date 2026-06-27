<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('ticket_number')->nullable()->after('id')->unique();
            $table->string('reporter_name')->nullable()->after('member_id');
            $table->string('reporter_phone')->nullable()->after('reporter_name');
            $table->foreignId('member_id')->nullable()->change();
        });

        // Generate ticket numbers for existing reports
        foreach (\App\Models\Report::all() as $report) {
            $report->ticket_number = 'TKT-'.date('ymd', strtotime($report->created_at)).'-'.strtoupper(Str::random(5));
            $report->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['ticket_number', 'reporter_name', 'reporter_phone']);
            $table->foreignId('member_id')->nullable(false)->change();
        });
    }
};
