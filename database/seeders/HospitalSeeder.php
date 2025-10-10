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
            // Bangalore Hospitals
            [
                'name' => 'Apollo Hospitals',
                'email' => 'apollo.hospital@gmail.com',
                'mobile' => '9876511111',
                'password' => Hash::make('apollo123'),
                'address' => 'Bannerghatta Road',
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'pincode' => '560076',
                'status' => 'active',
                'approved_at' => now(),
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
                'password' => Hash::make('manipal123'),
                'address' => 'Old Airport Road',
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'pincode' => '560017',
                'status' => 'active',
                'approved_at' => now(),
                'email_verified_at' => now(),
                'services' => [
                    ['name' => 'Neurology', 'discount' => 12],
                    ['name' => 'Gastroenterology', 'discount' => 8],
                    ['name' => 'Pathology Tests', 'discount' => 15],
                ]
            ],
            [
                'name' => 'Aster CMI Hospital',
                'email' => 'aster.hospital@gmail.com',
                'mobile' => '9876544444',
                'password' => Hash::make('aster123'),
                'address' => 'Sahakar Nagar',
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'pincode' => '560092',
                'status' => 'active',
                'approved_at' => now(),
                'email_verified_at' => now(),
                'services' => [
                    ['name' => 'Pediatrics', 'discount' => 18],
                    ['name' => 'Maternity Services', 'discount' => 15],
                ]
            ],
            [
                'name' => 'BGS Gleneagles Global Hospital',
                'email' => 'bgs.hospital@gmail.com',
                'mobile' => '9876599999',
                'password' => Hash::make('bgs123'),
                'address' => 'Kengeri',
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'pincode' => '560060',
                'status' => 'active',
                'approved_at' => now(),
                'email_verified_at' => now(),
                'services' => [
                    ['name' => 'Orthopedics', 'discount' => 15],
                    ['name' => 'Neurology', 'discount' => 10],
                    ['name' => 'Urology', 'discount' => 12],
                ]
            ],
            
            // Mumbai Hospitals
            [
                'name' => 'Fortis Hospital',
                'email' => 'fortis.mumbai@gmail.com',
                'mobile' => '9876533333',
                'password' => Hash::make('fortis123'),
                'address' => 'Mulund West',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400080',
                'status' => 'active',
                'approved_at' => now(),
                'email_verified_at' => now(),
                'services' => [
                    ['name' => 'Oncology', 'discount' => 10],
                    ['name' => 'Radiology (X-ray, MRI, CT)', 'discount' => 20],
                    ['name' => 'General Consultation', 'discount' => 10],
                ]
            ],
            [
                'name' => 'Kokilaben Dhirubhai Ambani Hospital',
                'email' => 'kokilaben.hospital@gmail.com',
                'mobile' => '9876544445',
                'password' => Hash::make('kokilaben123'),
                'address' => 'Four Bungalows',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400053',
                'status' => 'active',
                'approved_at' => now(),
                'email_verified_at' => now(),
                'services' => [
                    ['name' => 'Cardiology', 'discount' => 25],
                    ['name' => 'Neurology', 'discount' => 20],
                    ['name' => 'Oncology', 'discount' => 15],
                ]
            ],
            
            // Delhi Hospitals
            [
                'name' => 'Max Super Speciality Hospital',
                'email' => 'max.delhi@gmail.com',
                'mobile' => '9876555555',
                'password' => Hash::make('max123'),
                'address' => 'Saket',
                'city' => 'Delhi',
                'state' => 'Delhi',
                'pincode' => '110017',
                'status' => 'active',
                'approved_at' => now(),
                'email_verified_at' => now(),
                'services' => [
                    ['name' => 'Ophthalmology', 'discount' => 10],
                    ['name' => 'Dermatology', 'discount' => 12],
                    ['name' => 'ENT', 'discount' => 8],
                ]
            ],
            [
                'name' => 'AIIMS Delhi',
                'email' => 'aiims.delhi@gmail.com',
                'mobile' => '9876566666',
                'password' => Hash::make('aiims123'),
                'address' => 'Ansari Nagar',
                'city' => 'Delhi',
                'state' => 'Delhi',
                'pincode' => '110029',
                'status' => 'active',
                'approved_at' => now(),
                'email_verified_at' => now(),
                'services' => [
                    ['name' => 'Cardiology', 'discount' => 30],
                    ['name' => 'Nephrology', 'discount' => 25],
                    ['name' => 'Oncology', 'discount' => 20],
                ]
            ],
            
            // Chennai Hospitals
            [
                'name' => 'Apollo Hospitals Chennai',
                'email' => 'apollo.chennai@gmail.com',
                'mobile' => '9876577777',
                'password' => Hash::make('apollo123'),
                'address' => 'Greams Road',
                'city' => 'Chennai',
                'state' => 'Tamil Nadu',
                'pincode' => '600006',
                'status' => 'active',
                'approved_at' => now(),
                'email_verified_at' => now(),
                'services' => [
                    ['name' => 'General Consultation', 'discount' => 10],
                    ['name' => 'Pathology Tests', 'discount' => 12],
                    ['name' => 'Emergency Services', 'discount' => 5],
                ]
            ],
            [
                'name' => 'Fortis Malar Hospital',
                'email' => 'fortis.chennai@gmail.com',
                'mobile' => '9876588888',
                'password' => Hash::make('fortis123'),
                'address' => 'Adyar',
                'city' => 'Chennai',
                'state' => 'Tamil Nadu',
                'pincode' => '600020',
                'status' => 'active',
                'approved_at' => now(),
                'email_verified_at' => now(),
                'services' => [
                    ['name' => 'Pediatrics', 'discount' => 10],
                    ['name' => 'Maternity Services', 'discount' => 8],
                    ['name' => 'Physiotherapy', 'discount' => 15],
                ]
            ],
            
            // Hyderabad Hospitals
            [
                'name' => 'Continental Hospitals',
                'email' => 'continental.hyderabad@gmail.com',
                'mobile' => '9876599999',
                'password' => Hash::make('continental123'),
                'address' => 'Gachibowli',
                'city' => 'Hyderabad',
                'state' => 'Telangana',
                'pincode' => '500032',
                'status' => 'active',
                'approved_at' => now(),
                'email_verified_at' => now(),
                'services' => [
                    ['name' => 'Dentistry', 'discount' => 20],
                    ['name' => 'Dermatology', 'discount' => 15],
                    ['name' => 'Orthopedics', 'discount' => 12],
                ]
            ],
        ];

        foreach ($hospitalsData as $data) {
            $servicesToAttach = $data['services'];
            unset($data['services']); // Remove services array before creating hospital

            $hospital = Hospital::create($data);

            foreach ($servicesToAttach as $serviceData) {
                // Create service if it doesn't exist
                $service = Service::firstOrCreate(
                    ['name' => $serviceData['name']],
                    [
                        'description' => $serviceData['name'] . ' services',
                        'category' => $this->getServiceCategory($serviceData['name']),
                        'is_active' => true,
                        'hospital_id' => $hospital->id,
                        'discount_percentage' => $serviceData['discount'],
                        'price' => 1000, // Default price
                        'status' => 'active'
                    ]
                );
            }
        }
    }

    private function getServiceCategory($serviceName)
    {
        $categories = [
            'Cardiology' => 'cardiology',
            'Orthopedics' => 'orthopedics',
            'Neurology' => 'neurology',
            'Oncology' => 'oncology',
            'Pediatrics' => 'pediatrics',
            'Maternity Services' => 'gynecology',
            'Emergency Services' => 'emergency',
            'General Consultation' => 'general',
            'Pathology Tests' => 'pathology',
            'Radiology' => 'radiology',
            'Ophthalmology' => 'ophthalmology',
            'Dermatology' => 'dermatology',
            'Dentistry' => 'dentistry',
            'ENT' => 'ent',
            'Physiotherapy' => 'physiotherapy',
            'Gastroenterology' => 'gastroenterology',
            'Nephrology' => 'nephrology',
            'Urology' => 'urology',
        ];

        return $categories[$serviceName] ?? 'general';
    }
}