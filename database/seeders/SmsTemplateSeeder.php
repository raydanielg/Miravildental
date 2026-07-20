<?php

namespace Database\Seeders;

use App\Models\SmsTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SmsTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Patient Welcome Message',
                'trigger' => 'welcome',
                'category' => 'registration',
                'body' => 'Hello {{name}}, welcome to {{clinic_name}}. Your registration has been received (File No: {{file_number}}). For inquiries call {{clinic_phone}}. -MIRAVIL',
                'is_active' => true,
            ],
            [
                'name' => 'Appointment Confirmation',
                'trigger' => 'booking_confirmation',
                'category' => 'dental',
                'body' => 'Hello {{name}}, your appointment at Miravil Dental Clinic has been booked for {{date}} at {{time}}. See you soon! -MIRAVIL',
                'is_active' => true,
            ],
            [
                'name' => 'Appointment Approved',
                'trigger' => 'appointment_approved',
                'category' => 'dental',
                'body' => 'Hello {{name}}, your appointment on {{date}} at {{time}} at Miravil Dental Clinic has been approved. See you soon! -MIRAVIL',
                'is_active' => true,
            ],
            [
                'name' => '24h Appointment Reminder',
                'trigger' => 'reminder_24h',
                'category' => 'dental',
                'body' => 'Reminder: You have an appointment at Miravil Dental Clinic tomorrow {{date}} at {{time}}. Please reply YES to confirm. -MIRAVIL',
                'is_active' => true,
                'send_before_hours' => 24,
            ],
            [
                'name' => '2h Appointment Reminder',
                'trigger' => 'reminder_2h',
                'category' => 'dental',
                'body' => 'Hello {{name}}, you have an appointment at Miravil Dental Clinic today at {{time}}. See you shortly! -MIRAVIL',
                'is_active' => true,
                'send_before_hours' => 2,
            ],
            [
                'name' => 'Appointment Rescheduled',
                'trigger' => 'reschedule',
                'category' => 'dental',
                'body' => 'Hello {{name}}, your appointment at Miravil Dental Clinic has been rescheduled to {{date}} at {{time}}. -MIRAVIL',
                'is_active' => true,
            ],
            [
                'name' => 'Appointment Cancellation',
                'trigger' => 'cancellation',
                'category' => 'dental',
                'body' => 'Hello {{name}}, your appointment at Miravil Dental Clinic on {{date}} at {{time}} has been cancelled. Please call us to reschedule. -MIRAVIL',
                'is_active' => true,
            ],
            [
                'name' => 'Post-Treatment Follow-up',
                'trigger' => 'follow_up',
                'category' => 'dental',
                'body' => 'Hello {{name}}, we hope you are doing well after your visit to Miravil Dental Clinic. Keep up your oral hygiene and contact us if you have any concerns. -MIRAVIL DENTAL CLINIC',
                'is_active' => true,
                'send_after_days' => 1,
            ],
            [
                'name' => 'Recall Reminder',
                'trigger' => 'recall',
                'category' => 'dental',
                'body' => 'Hello {{name}}, it is time for your dental check-up at Miravil Dental Clinic. Call us or book online for your next visit. -MIRAVIL',
                'is_active' => true,
                'send_after_days' => 180,
            ],
            [
                'name' => 'Mawlid Holiday',
                'trigger' => 'holiday_mawlid',
                'category' => 'holiday',
                'body' => 'Happy Mawlid Holiday from Miravil Dental Clinic. Wishing you joy and peace. -MIRAVIL',
                'is_active' => false,
            ],
            [
                'name' => 'Eid-el-Fitr',
                'trigger' => 'holiday_eid_fitr',
                'category' => 'holiday',
                'body' => 'Eid Mubarak! Miravil Dental Clinic wishes you and your family a blessed and joyful Eid. -MIRAVIL',
                'is_active' => false,
            ],
            [
                'name' => 'Eid-el-Hajj',
                'trigger' => 'holiday_eid_hajj',
                'category' => 'holiday',
                'body' => 'Bakrid Mubarak from Miravil Dental Clinic. Wishing you a year of good health and blessings! -MIRAVIL',
                'is_active' => false,
            ],
            [
                'name' => 'Christmas',
                'trigger' => 'holiday_christmas',
                'category' => 'holiday',
                'body' => 'Merry Christmas and Happy New Year from Miravil Dental Clinic. Wishing you joyful celebrations and healthy smiles. -MIRAVIL',
                'is_active' => false,
            ],
            [
                'name' => 'New Year',
                'trigger' => 'holiday_new_year',
                'category' => 'holiday',
                'body' => 'Happy New Year from Miravil Dental Clinic! Wishing you a 2026 full of good health and beautiful smiles. -MIRAVIL',
                'is_active' => false,
            ],
            [
                'name' => 'Womens Day',
                'trigger' => 'holiday_women_day',
                'category' => 'holiday',
                'body' => 'Happy International Womens Day. Miravil Dental Clinic celebrates you and your great contributions. -MIRAVIL',
                'is_active' => false,
            ],
            [
                'name' => 'Labour Day',
                'trigger' => 'holiday_labour_day',
                'category' => 'holiday',
                'body' => 'Happy Labour Day. Miravil Dental Clinic wishes you a joyful and restful holiday. -MIRAVIL',
                'is_active' => false,
            ],
            [
                'name' => 'Union Day',
                'trigger' => 'holiday_union_day',
                'category' => 'holiday',
                'body' => 'Happy Union Day from Miravil Dental Clinic. Celebrating the union of Tanganyika and Zanzibar. -MIRAVIL',
                'is_active' => false,
            ],
            [
                'name' => 'Independence Day',
                'trigger' => 'holiday_independence',
                'category' => 'holiday',
                'body' => 'Happy Tanzania Independence Day. Miravil Dental Clinic wishes you a joyful celebration. -MIRAVIL',
                'is_active' => false,
            ],
            [
                'name' => 'Farmers Day (Nane Nane)',
                'trigger' => 'holiday_nane_nane',
                'category' => 'holiday',
                'body' => 'Happy Farmers Day (Nane Nane) from Miravil Dental Clinic. -MIRAVIL',
                'is_active' => false,
            ],
        ];

        foreach ($templates as $template) {
            SmsTemplate::updateOrCreate(['trigger' => $template['trigger']], $template);
        }
    }
}
