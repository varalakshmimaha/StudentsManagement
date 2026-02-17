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
            // Address
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();
            
            // Academic
            $table->string('highest_qualification')->nullable();
            $table->string('college_name')->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            
            // Parent/Guardian
            $table->string('parent_type')->nullable(); // Father, Mother, Guardian
            $table->string('parent_name')->nullable();
            $table->string('parent_mobile')->nullable();
            $table->string('parent_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'current_address', 'permanent_address', 
                'highest_qualification', 'college_name', 'percentage',
                'parent_type', 'parent_name', 'parent_mobile', 'parent_email'
            ]);
        });
    }
};
