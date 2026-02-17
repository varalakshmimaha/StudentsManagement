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
        Schema::table('leads', function (Blueprint $table) {
            $table->date('counselling_date')->nullable();
            $table->text('counselling_notes')->nullable();
            $table->string('counselling_outcome')->nullable(); // Interested, Not Interested, Need Time, Will Join Later
            $table->date('estimated_joining_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'counselling_date',
                'counselling_notes',
                'counselling_outcome',
                'estimated_joining_date',
            ]);
        });
    }
};
