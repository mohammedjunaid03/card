<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AuditLog;
use Carbon\Carbon;

class AuditLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $logs = [
            // Admin actions
            [
                'user_id' => 1,
                'user_type' => 'admin',
                'action' => 'GET admin.dashboard',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subMinutes(5),
            ],
            [
                'user_id' => 1,
                'user_type' => 'admin',
                'action' => 'GET admin.users.index',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subMinutes(10),
            ],
            [
                'user_id' => 1,
                'user_type' => 'admin',
                'action' => 'POST admin.users.store',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subMinutes(15),
            ],
            [
                'user_id' => 1,
                'user_type' => 'admin',
                'action' => 'GET admin.hospitals.index',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subMinutes(20),
            ],
            [
                'user_id' => 1,
                'user_type' => 'admin',
                'action' => 'PUT admin.hospitals.update',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subMinutes(25),
            ],
            
            // Staff actions
            [
                'user_id' => 2,
                'user_type' => 'staff',
                'action' => 'GET staff.dashboard',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subMinutes(30),
            ],
            [
                'user_id' => 2,
                'user_type' => 'staff',
                'action' => 'POST staff.users.store',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subMinutes(35),
            ],
            
            // Hospital actions
            [
                'user_id' => 1,
                'user_type' => 'hospital',
                'action' => 'GET hospital.dashboard',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subMinutes(40),
            ],
            [
                'user_id' => 1,
                'user_type' => 'hospital',
                'action' => 'POST hospital.verify-card',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subMinutes(45),
            ],
            
            // User actions
            [
                'user_id' => 1,
                'user_type' => 'user',
                'action' => 'GET user.dashboard',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subMinutes(50),
            ],
            [
                'user_id' => 1,
                'user_type' => 'user',
                'action' => 'GET user.health-card',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subMinutes(55),
            ],
            [
                'user_id' => 1,
                'user_type' => 'user',
                'action' => 'POST user.health-card.download',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subMinutes(60),
            ],
        ];

        // Add more logs with different timestamps
        for ($i = 0; $i < 50; $i++) {
            $userTypes = ['admin', 'staff', 'hospital', 'user'];
            $actions = [
                'GET admin.dashboard',
                'GET admin.users.index',
                'POST admin.users.store',
                'PUT admin.users.update',
                'DELETE admin.users.destroy',
                'GET admin.hospitals.index',
                'POST admin.hospitals.store',
                'GET staff.dashboard',
                'POST staff.users.store',
                'GET hospital.dashboard',
                'POST hospital.verify-card',
                'GET user.dashboard',
                'GET user.health-card',
                'POST user.health-card.download'
            ];
            
            $logs[] = [
                'user_id' => rand(1, 5),
                'user_type' => $userTypes[array_rand($userTypes)],
                'action' => $actions[array_rand($actions)],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subHours(rand(1, 72))->subMinutes(rand(0, 59)),
            ];
        }

        foreach ($logs as $log) {
            AuditLog::create($log);
        }
    }
}