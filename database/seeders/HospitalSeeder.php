<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hospital;
use App\Models\Service;
use Illuminate\Support\Facades\Hash;

class HospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hospitalsData = [
            [
                'name' => 'Apollo Hospitals',
                'email' => 'apollo.hospital@gmail.com',
                'mobile' => '9876511111',
                'password' => 'apollo123',
                'address' => 'Bannerghatta Road',
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'pincode' => '560076',
                'status' => 'approved',
                'email_verified_at' => now(),
                'services' => [
                    ['name' => 'Cardiology', 'discount' => 15],
                    ['name' => 'Orthopedics', 'discount' => 10],
                    ['name' => 'Emergency Services', 'discount' => 5],
                ]
            ],
            [
                'name' => 'Manipal Hospital',
                'email' => 'manipal.hospital@gmail.com',
                'mobile' => '9876522222',
                'password' => 'manipal123',
                'address' => 'Old Airport Road',
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'pincode' => '560017',
                'status' => 'approved',
                'email_verified_at' => now(),
                'services' => [
                    ['name' => 'Neurology', 'discount' => 12],
                    ['name' => 'Gastroenterology', 'discount' => 8],
                    ['name' => 'Pathology Tests', 'discount' => 15],
                ]
            ],
            [
                'name' => 'Fortis Hospital',
                'email' => 'fortis.hospital@gmail.com',
                'mobile' => '9876533333',
                'password' => 'fortis123',
                'address' => 'Cunningham Road',
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'pincode' => '560052',
                'status' => 'approved',
                'email_verified_at' => now(),
                'services' => [
                    ['name' => 'Oncology', 'discount' => 10],
                    ['name' => 'Radiology (X-ray, MRI, CT)', 'discount' => 20],
                    ['name' => 'General Consultation', 'discount' => 10],
                ]
            ],
            [
                'name' => 'Aster CMI Hospital',
                'email' => 'aster.hospital@gmail.com',
                'mobile' => '9876544444',
                'password' => 'aster123',
                'address' => 'Sahakar Nagar',
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'pincode' => '560092',
                'status' => 'approved',
                'email_verified_at' => now(),
                'services' => [
                    ['name' => 'Pediatrics', 'discount' => 18],
                    ['name' => 'Maternity Services', 'discount' => 15],
                ]
            ],
            [
                'name' => 'Columbia Asia Hospital',
                'email' => 'columbia.hospital@gmail.com',
                'mobile' => '9876555555',
                'password' => 'columbia123',
                'address' => 'Sarjapur Road',
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'pincode' => '560103',
                'status' => 'approved',
                'email_verified_at' => now(),
                'services' => [
                    ['name' => 'Ophthalmology', 'discount' => 10],
                    ['name' => 'Dermatology', 'discount' => 12],
                ]
            ],
            [
                'name' => 'Narayana Health City',
                'email' => 'narayana.hospital@gmail.com',
                'mobile' => '9876566666',
                'password' => 'narayana123',
                'address' => 'Bommasandra',
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'pincode' => '560099',
                'status' => 'approved',
                'email_verified_at' => now(),
                'services' => [
                    ['name' => 'Cardiology', 'discount' => 20],
                    ['name' => 'Nephrology', 'discount' => 15],
                    ['name' => 'Oncology', 'discount' => 10],
                ]
            ],
            [
                'name' => 'Sagar Hospitals',
                'email' => 'sagar.hospital@gmail.com',
                'mobile' => '9876577777',
                'password' => 'sagar123',
                'address' => 'Kumarswamy Layout',
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'pincode' => '560078',
                'status' => 'approved',
                'email_verified_at' => now(),
                'services' => [
                    ['name' => 'General Consultation', 'discount' => 10],
                    ['name' => 'Pathology Tests', 'discount' => 12],
                ]
            ],
            [
                'name' => 'St. John\'s Medical College Hospital',
                'email' => 'stjohns.hospital@gmail.com',
                'mobile' => '9876588888',
                'password' => 'stjohns123',
                'address' => 'Sarjapur Road',
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'pincode' => '560034',
                'status' => 'approved',
                'email_verified_at' => now(),
                'services' => [
                    ['name' => 'Pediatrics', 'discount' => 10],
                    ['name' => 'Maternity Services', 'discount' => 8],
                    ['name' => 'Physiotherapy', 'discount' => 15],
                ]
            ],
            [
                'name' => 'BGS Gleneagles Global Hospital',
                'email' => 'bgs.hospital@gmail.com',
                'mobile' => '9876599999',
                'password' => 'bgs123',
                'address' => 'Kengeri',
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'pincode' => '560060',
                'status' => 'approved',
                'email_verified_at' => now(),
                'services' => [
                    ['name' => 'Orthopedics', 'discount' => 15],
                    ['name' => 'Neurology', 'discount' => 10],
                    ['name' => 'Urology', 'discount' => 12],
                ]
            ],
            [
                'name' => 'Sakura Hospital',
                'email' => 'sakura.hospital@gmail.com',
                'mobile' => '9876510101',
                'password' => 'sakura123',
                'address' => 'Jayanagar',
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'pincode' => '560041',
                'status' => 'approved',
                'email_verified_at' => now(),
                'services' => [
                    ['name' => 'Dentistry', 'discount' => 20],
                    ['name' => 'Dermatology', 'discount' => 15],
                ]
            ],
        ];

        foreach ($hospitalsData as $data) {
            $servicesToAttach = $data['services'];
            unset($data['services']); // Remove services array before creating hospital

            $hospital = Hospital::create($data);

            foreach ($servicesToAttach as $serviceData) {
                $service = Service::where('name', $serviceData['name'])->first();
                if ($service) {
                    $hospital->services()->syncWithoutDetaching([
                        $service->id => ['discount_percentage' => $serviceData['discount']]
                    ]);
                }
            }
        }
    }
}