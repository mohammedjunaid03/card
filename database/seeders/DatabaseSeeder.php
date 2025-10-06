<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ServiceSeeder::class,
            AdminSeeder::class,
            HospitalSeeder::class,
            StaffSeeder::class,
        ]);

        // Create test users
        User::create([
            'name' => 'Test User',
            'email' => 'testuser@gmail.com',
            'mobile' => '9876543299',
            'password' => bcrypt('password'),
            'status' => 'active',
            'email_verified' => true,
            'mobile_verified' => true,
            'role' => 'user',
            'date_of_birth' => '1990-01-01',
            'age' => 34,
            'gender' => 'Male',
            'blood_group' => 'O+',
            'address' => '123 Test Street, Bangalore',
        ]);

        User::create([
            'name' => 'Demo Patient',
            'email' => 'demopatient@gmail.com',
            'mobile' => '9876543298',
            'password' => bcrypt('password'),
            'status' => 'active',
            'email_verified' => true,
            'mobile_verified' => true,
            'role' => 'user',
            'date_of_birth' => '1985-05-15',
            'age' => 39,
            'gender' => 'Female',
            'blood_group' => 'A+',
            'address' => '456 Demo Avenue, Bangalore',
        ]);
    }
}