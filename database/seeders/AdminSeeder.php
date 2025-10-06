<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'mobile' => '9876543210',
                'password' => 'admin123',
                'role' => 'super_admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'System Administrator',
                'email' => 'systemadmin@gmail.com',
                'mobile' => '9876543211',
                'password' => 'system123',
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Support Admin',
                'email' => 'supportadmin@gmail.com',
                'mobile' => '9876543212',
                'password' => 'support123',
                'role' => 'support_admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($admins as $admin) {
            Admin::create($admin);
        }
    }
}