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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete(); // Store batch context
            
            $table->string('status'); // present, absent
            $table->text('remarks')->nullable();
            
            $table->foreignId('marked_by')->constrained('users');
            
            $table->unique(['student_id', 'date', 'batch_id']); // Prevent duplicate attendance for same student/batch/date
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
