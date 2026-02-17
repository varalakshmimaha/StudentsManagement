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
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile', 15)->unique()->after('email');
            $table->string('username')->nullable()->unique()->after('mobile');
            $table->foreignId('role_id')->nullable()->constrained()->nullOnDelete()->after('username'); // Nullable to allow creating user first then assigning role if needed, or seeding. Constraints works fine if table exists.
            $table->string('status')->default('active')->after('role_id');
            $table->timestamp('last_login_at')->nullable()->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['mobile', 'username', 'role_id', 'status', 'last_login_at']);
        });
    }
};
