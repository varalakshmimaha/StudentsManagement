<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        // app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        // Since we are not using Spatie package yet (I created custom models), but the logic is similar.
        // Wait, I created `Role` and `Permission` models myself. So I should use them directly.
        // Or if I am using Spatie package, I should have installed it. The prompt didn't say to use Spatie, so I created models.
        // Let's assume custom implementation for now or I can install Spatie later.
        // Given I created models with `guard_name`, it mimics Spatie.
        
        $permissions = [
            'view stats',
            'manage branches',
            'manage roles',
            'manage users',
            'manage courses',
            'manage batches',
            'manage students',
            'manage fees',
            'manage attendance',
            'manage reports',
            'manage leads',
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            \App\Models\Permission::create(['name' => $permission, 'group_name' => 'General']); // simplified group for now
        }

        // Create Roles
        $roles = [
            'Super Admin',
            'Admin',
            'Teacher',
            'Counsellor',
            'Accountant',
        ];

        foreach ($roles as $role) {
            \App\Models\Role::create(['name' => $role]);
        }

        // Create Super Admin User
        $superAdminRole = \App\Models\Role::where('name', 'Super Admin')->first();
        
        \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'mobile' => '9999999999',
            'username' => 'superadmin',
            'password' => bcrypt('password'),
            'role_id' => $superAdminRole->id,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);    }
}
