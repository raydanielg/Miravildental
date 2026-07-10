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
                'sender_id' => env('NEXTSMS_FROM', 'MIRAVIL'),
                'sms_provider' => 'nextsms',
                'sms_api_url' => env('NEXTSMS_URL', 'https://messaging-service.co.tz/api/sms/v1/text/single'),
                'sms_api_username' => env('NEXTSMS_USERNAME'),
                'sms_api_password' => env('NEXTSMS_PASSWORD'),
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
