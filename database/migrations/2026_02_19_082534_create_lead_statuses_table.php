<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lead_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('color')->nullable()->default('gray');
            $table->integer('order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // Seed Default Statuses
        $statuses = [
            ['name' => 'New', 'color' => 'blue', 'order' => 10],
            ['name' => 'Contacted', 'color' => 'yellow', 'order' => 20],
            ['name' => 'Walk-in', 'color' => 'green', 'order' => 30],
            ['name' => 'Lost', 'color' => 'red', 'order' => 40],
            ['name' => 'Interested', 'color' => 'indigo', 'order' => 50],
            ['name' => 'Converted', 'color' => 'purple', 'order' => 60],
            ['name' => 'Counselling Done', 'color' => 'teal', 'order' => 70],
        ];

        foreach ($statuses as $status) {
            DB::table('lead_statuses')->insert(array_merge($status, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_statuses');
    }
};
