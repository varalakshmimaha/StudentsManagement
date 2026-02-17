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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 15)->unique(); // Leads list: Phone (unique check mentioned in prompts)
            $table->string('email')->nullable();
            $table->string('source')->nullable(); // Walk-in, Referral, etc.
            
            $table->foreignId('preferred_branch_id')->nullable()->constrained('branches')->nullOnDelete();
            
            // Interested courses: simple text or json? "Interested Courses (multi-select)"
            // Let's use JSON for multi-select storage
            $table->json('interested_courses')->nullable();
            
            $table->string('status')->default('new'); // new, contacted, scheduled, counselling_done, interested, not_interested, lost, converted
            
            $table->foreignId('assigned_counsellor_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->date('next_followup_date')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
