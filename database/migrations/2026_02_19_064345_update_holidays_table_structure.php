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
        Schema::table('holidays', function (Blueprint $table) {
            $table->string('type')->default('General')->after('date'); // General, Branch Specific
            $table->boolean('is_recurring')->default(false)->after('type');
            $table->boolean('is_active')->default(true)->after('is_recurring');
            $table->string('month_day', 5)->nullable()->after('is_recurring'); // format: MM-DD
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('holidays', function (Blueprint $table) {
            $table->dropColumn(['type', 'is_recurring', 'is_active', 'month_day']);
        });
    }
};
