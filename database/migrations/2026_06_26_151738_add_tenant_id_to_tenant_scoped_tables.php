<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'users', 'families', 'members', 'contributions', 'expenses',
        'letter_requests', 'reports', 'news', 'products', 'life_events',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->nullOnDelete();
            });
        }

        // Backfill: move all existing data into a first tenant so nothing is lost.
        $firstUser = DB::table('users')->orderBy('id')->first();

        $tenantId = DB::table('tenants')->insertGetId([
            'name' => config('app.name', 'SmartRT Vision'),
            'slug' => 'rt-pertama',
            'email' => $firstUser->email ?? null,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($this->tables as $table) {
            DB::table($table)->update(['tenant_id' => $tenantId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropConstrainedForeignId('tenant_id');
            });
        }
    }
};
