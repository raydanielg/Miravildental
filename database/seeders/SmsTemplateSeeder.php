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
                'name' => 'Booking Confirmation',
                'trigger' => 'booking_confirmation',
                'body' => 'Hello {{name}}, your appointment at Miravil Dental is booked for {{date}} at {{time}}. See you then! -MIRAVIL',
                'is_active' => true,
            ],
            [
                'name' => '24h Reminder',
                'trigger' => 'reminder_24h',
                'body' => 'Reminder: You have an appointment at Miravil Dental tomorrow {{date}} at {{time}}. Please reply YES to confirm. -MIRAVIL',
                'is_active' => true,
                'send_before_hours' => 24,
            ],
            [
                'name' => '2h Reminder',
                'trigger' => 'reminder_2h',
                'body' => 'Hi {{name}}, your appointment at Miravil Dental is today at {{time}}. See you soon! -MIRAVIL',
                'is_active' => true,
                'send_before_hours' => 2,
            ],
            [
                'name' => 'Reschedule Notice',
                'trigger' => 'reschedule',
                'body' => 'Hello {{name}}, your Miravil Dental appointment has been rescheduled to {{date}} at {{time}}. -MIRAVIL',
                'is_active' => true,
            ],
            [
                'name' => 'Cancellation Notice',
                'trigger' => 'cancellation',
                'body' => 'Hello {{name}}, your Miravil Dental appointment on {{date}} at {{time}} has been cancelled. Call to rebook. -MIRAVIL',
                'is_active' => true,
            ],
            [
                'name' => 'Follow-up Care',
                'trigger' => 'follow_up',
                'body' => 'Hi {{name}}, we hope you are doing well after your visit. Remember to maintain oral hygiene and contact us for any concerns. -MIRAVIL',
                'is_active' => true,
                'send_after_days' => 1,
            ],
            [
                'name' => 'Recall Reminder',
                'trigger' => 'recall',
                'body' => 'Hi {{name}}, it is time for your dental check-up at Miravil Dental. Call or book online for your next visit. -MIRAVIL',
                'is_active' => true,
                'send_after_days' => 180,
            ],
        ];

        foreach ($templates as $template) {
            SmsTemplate::updateOrCreate(['trigger' => $template['trigger']], $template);
        }
    }
}
