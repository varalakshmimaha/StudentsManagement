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
            $table->string('current_city')->nullable()->after('current_address');
            $table->string('current_state')->nullable()->after('current_city');
            $table->string('current_pincode')->nullable()->after('current_state');
            
            $table->string('permanent_city')->nullable()->after('permanent_address');
            $table->string('permanent_state')->nullable()->after('permanent_city');
            $table->string('permanent_pincode')->nullable()->after('permanent_state');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'current_city', 
                'current_state', 
                'current_pincode', 
                'permanent_city', 
                'permanent_state', 
                'permanent_pincode'
            ]);
        });
    }
};
