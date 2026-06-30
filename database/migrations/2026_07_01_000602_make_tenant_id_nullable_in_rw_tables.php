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
        $tables = ['agendas', 'news', 'inventories'];
        
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                // To change a column to nullable, we need doctrine/dbal, 
                // but Laravel 10+ handles this natively if we just do ->change()
                $table->foreignId('tenant_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['agendas', 'news', 'inventories'];
        
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignId('tenant_id')->nullable(false)->change();
            });
        }
    }
};
