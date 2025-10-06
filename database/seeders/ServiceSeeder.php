<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'General Medicine',
                'description' => 'Primary healthcare services including consultations, diagnosis, and treatment of common illnesses.',
                'category' => 'Primary Care',
                'is_active' => true,
            ],
            [
                'name' => 'Cardiology',
                'description' => 'Heart and cardiovascular system diagnosis, treatment, and surgery.',
                'category' => 'Specialty Care',
                'is_active' => true,
            ],
            [
                'name' => 'Orthopedics',
                'description' => 'Bone, joint, and muscle related treatments and surgeries.',
                'category' => 'Specialty Care',
                'is_active' => true,
            ],
            [
                'name' => 'Pediatrics',
                'description' => 'Medical care for infants, children, and adolescents.',
                'category' => 'Specialty Care',
                'is_active' => true,
            ],
            [
                'name' => 'Gynecology',
                'description' => 'Women\'s reproductive health and related medical services.',
                'category' => 'Specialty Care',
                'is_active' => true,
            ],
            [
                'name' => 'Dermatology',
                'description' => 'Skin, hair, and nail related medical treatments.',
                'category' => 'Specialty Care',
                'is_active' => true,
            ],
            [
                'name' => 'Ophthalmology',
                'description' => 'Eye and vision related medical care and surgery.',
                'category' => 'Specialty Care',
                'is_active' => true,
            ],
            [
                'name' => 'Dentistry',
                'description' => 'Oral and dental health services including cleaning, treatment, and surgery.',
                'category' => 'Specialty Care',
                'is_active' => true,
            ],
            [
                'name' => 'ENT (Ear, Nose, Throat)',
                'description' => 'Ear, nose, and throat related medical treatments and surgeries.',
                'category' => 'Specialty Care',
                'is_active' => true,
            ],
            [
                'name' => 'Neurology',
                'description' => 'Brain and nervous system related medical care.',
                'category' => 'Specialty Care',
                'is_active' => true,
            ],
            [
                'name' => 'Oncology',
                'description' => 'Cancer diagnosis, treatment, and care services.',
                'category' => 'Specialty Care',
                'is_active' => true,
            ],
            [
                'name' => 'Urology',
                'description' => 'Urinary system and male reproductive health services.',
                'category' => 'Specialty Care',
                'is_active' => true,
            ],
            [
                'name' => 'Gastroenterology',
                'description' => 'Digestive system related medical treatments and procedures.',
                'category' => 'Specialty Care',
                'is_active' => true,
            ],
            [
                'name' => 'Emergency Medicine',
                'description' => '24/7 emergency medical care and trauma treatment.',
                'category' => 'Emergency Care',
                'is_active' => true,
            ],
            [
                'name' => 'Diagnostic Services',
                'description' => 'Laboratory tests, imaging, and diagnostic procedures.',
                'category' => 'Diagnostic',
                'is_active' => true,
            ],
            [
                'name' => 'Physiotherapy',
                'description' => 'Physical therapy and rehabilitation services.',
                'category' => 'Rehabilitation',
                'is_active' => true,
            ],
            [
                'name' => 'Psychiatry',
                'description' => 'Mental health and psychological treatment services.',
                'category' => 'Mental Health',
                'is_active' => true,
            ],
            [
                'name' => 'Radiology',
                'description' => 'Medical imaging services including X-ray, MRI, CT scan.',
                'category' => 'Diagnostic',
                'is_active' => true,
            ],
            [
                'name' => 'Anesthesiology',
                'description' => 'Anesthesia services for surgeries and procedures.',
                'category' => 'Support Services',
                'is_active' => true,
            ],
            [
                'name' => 'Pathology',
                'description' => 'Laboratory testing and disease diagnosis services.',
                'category' => 'Diagnostic',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
