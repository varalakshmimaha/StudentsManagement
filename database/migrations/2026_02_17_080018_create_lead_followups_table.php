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
        Schema::create('lead_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            
            $table->string('outcome'); // Not reachable, Interested, etc.
            $table->text('notes')->nullable();
            $table->date('next_followup_date')->nullable();
            
            $table->foreignId('created_by')->constrained('users'); // Logged By
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_followups');
    }
};
