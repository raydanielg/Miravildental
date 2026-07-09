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
            ['name' => 'Consultation', 'description' => 'Initial dental check-up and consultation.', 'price' => 20000, 'duration_minutes' => 30, 'color' => '#3b82f6'],
            ['name' => 'Teeth Cleaning', 'description' => 'Professional scaling and polishing.', 'price' => 50000, 'duration_minutes' => 45, 'color' => '#10b981'],
            ['name' => 'Filling', 'description' => 'Cavity filling with composite material.', 'price' => 75000, 'duration_minutes' => 45, 'color' => '#f59e0b'],
            ['name' => 'Root Canal', 'description' => 'Endodontic treatment to save a tooth.', 'price' => 250000, 'duration_minutes' => 90, 'color' => '#8b5cf6'],
            ['name' => 'Extraction', 'description' => 'Tooth removal procedure.', 'price' => 60000, 'duration_minutes' => 45, 'color' => '#ef4444'],
            ['name' => 'Crowns & Bridges', 'description' => 'Restorative dental prosthetics.', 'price' => 400000, 'duration_minutes' => 60, 'color' => '#06b6d4'],
            ['name' => 'Whitening', 'description' => 'Professional teeth whitening session.', 'price' => 150000, 'duration_minutes' => 60, 'color' => '#ec4899'],
            ['name' => 'Oral Health Consultation', 'description' => 'Complimentary new-patient oral health session.', 'price' => 0, 'duration_minutes' => 20, 'color' => '#84cc16'],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['name' => $service['name']], $service);
        }
    }
}
