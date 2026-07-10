<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['name' => 'Oral Health Consultation', 'description' => 'Complimentary new-patient oral health session with expert advice.', 'image' => 'serious-expert-expressing-support-colleague (1).jpg', 'price' => 0, 'duration_minutes' => 20, 'color' => '#16a34a'],
            ['name' => 'Teeth Cleaning', 'description' => 'Professional scaling and polishing for a healthier, brighter smile.', 'image' => '7678.jpg', 'price' => 50000, 'duration_minutes' => 45, 'color' => '#22c55e'],
            ['name' => 'Teeth Whitening', 'description' => 'Safe and effective professional whitening treatment.', 'image' => 'watoto.png', 'price' => 150000, 'duration_minutes' => 60, 'color' => '#a855f7'],
            ['name' => 'Root Canal', 'description' => 'Endodontic treatment to save infected teeth and relieve pain.', 'image' => '1411.jpg', 'price' => 250000, 'duration_minutes' => 90, 'color' => '#9333ea'],
            ['name' => 'Tooth Extraction', 'description' => 'Gentle and safe tooth removal procedure by experienced dentists.', 'image' => 'images.png', 'price' => 60000, 'duration_minutes' => 45, 'color' => '#ef4444'],
            ['name' => 'Dental Fillings', 'description' => 'Cavity filling with high-quality composite material.', 'image' => '7678.jpg', 'price' => 75000, 'duration_minutes' => 45, 'color' => '#f59e0b'],
            ['name' => 'Crowns & Bridges', 'description' => 'Custom prosthetics to restore function and appearance.', 'image' => 'serious-expert-expressing-support-colleague (1).jpg', 'price' => 400000, 'duration_minutes' => 60, 'color' => '#06b6d4'],
            ['name' => 'Orthodontic Treatment', 'description' => 'Braces and aligners to correct teeth alignment and bite issues.', 'image' => '1411.jpg', 'price' => 800000, 'duration_minutes' => 60, 'color' => '#ec4899'],
            ['name' => 'Prosthodontics', 'description' => 'Specialized restorations including dentures and implants.', 'image' => 'images.png', 'price' => 350000, 'duration_minutes' => 60, 'color' => '#6366f1'],
            ['name' => 'Sealants & Fluoride', 'description' => 'Preventive treatments to protect teeth from decay.', 'image' => 'watoto.png', 'price' => 30000, 'duration_minutes' => 30, 'color' => '#84cc16'],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['name' => $service['name']], $service);
        }
    }
}
