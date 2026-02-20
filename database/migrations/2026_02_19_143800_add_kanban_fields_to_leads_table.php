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
            $table->unsignedBigInteger('batch_id')->nullable()->after('interested_courses');
            $table->date('joining_date')->nullable()->after('batch_id');
            $table->string('lost_reason')->nullable()->after('status');
            $table->text('lost_reason_notes')->nullable()->after('lost_reason');
            
            // Foreign key constraint if batches table exists (It should)
            // Using logic to check if table exists is hard in migration without DB facade, 
            // but standard Laravel projects have batches table if mentioned in controller.
            // Using simple index for now to be safe, or explicit FK if confident.
            // I'll skip explicit FK constraint to avoid "table not found" risk if migration order is weird,
            // but for data integrity it's better. I'll add it.
            // Actually, let's just make it a column for now to be safe against migration order issues in dev env.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['batch_id', 'joining_date', 'lost_reason', 'lost_reason_notes']);
        });
    }
};
