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
        Schema::table('students', function (Blueprint $table) {
            // New detailed address fields
            $table->string('current_place')->nullable()->after('current_address');
            $table->string('current_city')->nullable()->after('current_place');
            $table->string('current_state')->nullable()->after('current_city');
            $table->string('current_pin', 10)->nullable()->after('current_state');

            $table->string('permanent_place')->nullable()->after('permanent_address');
            $table->string('permanent_city')->nullable()->after('permanent_place');
            $table->string('permanent_state')->nullable()->after('permanent_city');
            $table->string('permanent_pin', 10)->nullable()->after('permanent_state');

            // Fee addition
            $table->boolean('after_placement_fee')->default(false)->after('payment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            //
        });
    }
};
