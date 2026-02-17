<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Branch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles first
        $this->call(RoleSeeder::class);

        // Create a default branch
        $branch = Branch::create([
            'name' => 'Main Branch',
            'code' => 'MB001',
            'address' => '123 Main Street, City',
            'status' => 'active',
        ]);

        // Get Super Admin role
        $superAdminRole = Role::where('name', 'Super Admin')->first();

        // Create Super Admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'mobile' => '9876543210',
            'password' => Hash::make('password'),
            'role_id' => $superAdminRole->id,
            'status' => 'active',
        ]);

        // Attach branch to user
        $admin->branches()->attach($branch->id);

        // Get Teacher and Staff roles
        $teacherRole = Role::where('name', 'Teacher')->first();
        $staffRole = Role::where('name', 'Staff')->first();

        // Create Teachers
        $teacherNames = ['Rajesh Kumar', 'Priya Sharma', 'Amit Patel', 'Sneha Reddy', 'Vikram Singh'];
        foreach ($teacherNames as $index => $name) {
            $teacher = User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
                'mobile' => '987654' . str_pad($index + 10, 4, '0', STR_PAD_LEFT),
                'password' => Hash::make('password'),
                'role_id' => $teacherRole->id,
                'status' => 'active',
            ]);
            $teacher->branches()->attach($branch->id);
        }

        // Create Staff members
        $staffNames = ['Anjali Verma', 'Rohan Gupta', 'Kavya Nair'];
        foreach ($staffNames as $index => $name) {
            $staff = User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
                'mobile' => '987654' . str_pad($index + 20, 4, '0', STR_PAD_LEFT),
                'password' => Hash::make('password'),
                'role_id' => $staffRole->id,
                'status' => 'active',
            ]);
            $staff->branches()->attach($branch->id);
        }
    }
}
