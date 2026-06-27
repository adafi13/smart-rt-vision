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
        Schema::create('inventory_borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inventory_id')->constrained()->cascadeOnDelete();
            
            // Warga info (either linked to member or manual entry)
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->string('borrower_name')->nullable();
            $table->string('borrower_contact')->nullable();
            
            $table->integer('quantity')->default(1);
            
            // Dates
            $table->date('borrow_date');
            $table->date('expected_return_date');
            $table->date('return_date')->nullable();
            
            // pending, approved, rejected, returned
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_borrowings');
    }
};
