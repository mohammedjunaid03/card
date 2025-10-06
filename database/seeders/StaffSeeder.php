<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staffMembers = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@gmail.com',
                'mobile' => '9876543220',
                'password' => 'staff123',
                'role' => 'admin_staff',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@gmail.com',
                'mobile' => '9876543221',
                'password' => 'staff123',
                'role' => 'receptionist',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael.brown@gmail.com',
                'mobile' => '9876543222',
                'password' => 'staff123',
                'role' => 'admin_staff',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily.davis@gmail.com',
                'mobile' => '9876543223',
                'password' => 'staff123',
                'role' => 'admin_staff',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'David Wilson',
                'email' => 'david.wilson@gmail.com',
                'mobile' => '9876543224',
                'password' => 'staff123',
                'role' => 'admin_staff',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
        ];

        // Get the first hospital for staff assignment
        $hospital = \App\Models\Hospital::first();
        
        foreach ($staffMembers as $staff) {
            $staff['hospital_id'] = $hospital ? $hospital->id : 1;
            Staff::create($staff);
        }
    }
}