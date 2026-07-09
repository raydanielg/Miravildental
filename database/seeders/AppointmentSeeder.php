<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Room;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctor = User::where('role', 'doctor')->first();
        $reception = User::where('role', 'reception')->first();
        $patients = Patient::all();
        $services = Service::all();
        $rooms = Room::all();

        if (!$doctor || $patients->isEmpty() || $services->isEmpty()) {
            return;
        }

        $appointments = [
            // Today
            ['patient_id' => $patients[0]->id, 'status' => Appointment::STATUS_ARRIVED, 'date' => today(), 'time' => '09:00', 'service' => 'Consultation'],
            ['patient_id' => $patients[1]->id, 'status' => Appointment::STATUS_CONFIRMED, 'date' => today(), 'time' => '10:30', 'service' => 'Teeth Cleaning'],
            ['patient_id' => $patients[2]->id, 'status' => Appointment::STATUS_BOOKED, 'date' => today(), 'time' => '11:15', 'service' => 'Filling'],
            ['patient_id' => $patients[3]->id, 'status' => Appointment::STATUS_COMPLETED, 'date' => today(), 'time' => '14:00', 'service' => 'Root Canal'],

            // Past week
            ['patient_id' => $patients[1]->id, 'status' => Appointment::STATUS_COMPLETED, 'date' => today()->subDays(1), 'time' => '09:00', 'service' => 'Teeth Cleaning'],
            ['patient_id' => $patients[4]->id, 'status' => Appointment::STATUS_COMPLETED, 'date' => today()->subDays(2), 'time' => '10:00', 'service' => 'Whitening'],
            ['patient_id' => $patients[5]->id, 'status' => Appointment::STATUS_COMPLETED, 'date' => today()->subDays(3), 'time' => '11:00', 'service' => 'Oral Health Consultation'],
            ['patient_id' => $patients[0]->id, 'status' => Appointment::STATUS_NO_SHOW, 'date' => today()->subDays(4), 'time' => '09:00', 'service' => 'Consultation'],
            ['patient_id' => $patients[2]->id, 'status' => Appointment::STATUS_CANCELLED, 'date' => today()->subDays(5), 'time' => '14:00', 'service' => 'Extraction'],
            ['patient_id' => $patients[3]->id, 'status' => Appointment::STATUS_COMPLETED, 'date' => today()->subDays(6), 'time' => '08:00', 'service' => 'Filling'],
        ];

        foreach ($appointments as $index => $data) {
            $service = $services->firstWhere('name', $data['service']) ?? $services->first();
            $room = $rooms[$index % $rooms->count()] ?? $rooms->first();
            $duration = $service->duration_minutes;
            $start = Carbon::parse($data['time']);
            $end = $start->copy()->addMinutes($duration);

            Appointment::updateOrCreate(
                [
                    'patient_id' => $data['patient_id'],
                    'appointment_date' => $data['date'],
                    'start_time' => $data['time'],
                ],
                [
                    'doctor_id' => $doctor->id,
                    'service_id' => $service->id,
                    'room_id' => $room->id,
                    'end_time' => $end->format('H:i:s'),
                    'status' => $data['status'],
                    'cost' => $service->price,
                    'notes' => 'Seeded appointment',
                    'booked_by' => $reception?->id ?? 1,
                    'reminder_24h_sent' => in_array($data['status'], [Appointment::STATUS_COMPLETED, Appointment::STATUS_ARRIVED, Appointment::STATUS_CONFIRMED]),
                ]
            );
        }
    }
}
