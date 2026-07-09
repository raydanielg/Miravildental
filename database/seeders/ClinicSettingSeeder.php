<?php

namespace Database\Seeders;

use App\Models\ClinicSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClinicSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClinicSetting::updateOrCreate(
            ['id' => 1],
            [
                'clinic_name' => 'Miravil Specialised Dental Centre',
                'phone' => '+255 700 000 000',
                'email' => 'info@miravil.co.tz',
                'address' => 'Dar es Salaam, Tanzania',
                'sender_id' => 'MIRAVIL',
                'default_appointment_duration' => 30,
                'reminder_24h_before' => 24,
                'reminder_2h_before' => 2,
                'recall_after_days' => 180,
                'currency' => 'TZS',
                'timezone' => 'Africa/Dar_es_Salaam',
            ]
        );
    }
}
