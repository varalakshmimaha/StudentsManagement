<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;

class ModulePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view courses',
            'view batches',
            'view students',
            'view fees',
            'view attendance',
            'view reports',
            'view leads',
            'view followups',
            'view branches',
        ];

        foreach ($permissions as $perm) {
            // Check if exists first to avoid duplicates if re-run
            $exists = DB::table('permissions')->where('name', $perm)->exists();
            
            if (!$exists) {
                DB::table('permissions')->insert([
                    'name' => $perm,
                    'guard_name' => 'web',
                    'group_name' => 'Module',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
