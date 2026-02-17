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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('roll_number')->unique();
            $table->string('name');
            $table->string('mobile', 15);
            $table->string('email')->nullable();
            
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            
            $table->string('status')->default('admission_done'); // admission_done, ongoing, placed
            $table->string('fee_status')->default('unpaid'); // unpaid, partial, fully_paid
            
            // Personal & Academic
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('highest_qualification')->nullable();
            $table->string('college_name')->nullable();
            $table->decimal('percentage', 5, 2)->nullable(); // 0-100
            
            // Parent/Guardian
            $table->string('parent_type')->default('Father'); // Father, Mother, Guardian
            $table->string('parent_name');
            $table->string('parent_mobile', 15);
            $table->string('parent_email')->nullable();
            
            // Fee Setup
            $table->decimal('total_fee', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('final_fee', 10, 2);
            $table->string('payment_type')->default('full'); // full, installments
            $table->text('notes')->nullable();
            
            // Link to Lead if converted
            $table->unsignedBigInteger('lead_id')->nullable(); 
            // We can't use constrained('leads') if leads table is created AFTER students table in migration order.
            // Check migration timestamps.
            // 2026_02_17_080017_create_students_table.php
            // 2026_02_17_080017_create_leads_table.php
            // They have same timestamp? Maybe just use unsignedBigInteger and index.
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
