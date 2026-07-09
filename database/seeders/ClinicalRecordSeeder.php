<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\ClinicalRecord;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClinicalRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctor = User::where('role', 'doctor')->first();
        if (!$doctor) return;

        $completedAppointments = Appointment::where('status', Appointment::STATUS_COMPLETED)->get();

        foreach ($completedAppointments as $appointment) {
            ClinicalRecord::updateOrCreate(
                ['appointment_id' => $appointment->id],
                [
                    'patient_id' => $appointment->patient_id,
                    'doctor_id' => $doctor->id,
                    'service_id' => $appointment->service_id,
                    'record_date' => $appointment->appointment_date,
                    'chief_complaint' => 'Routine care as per appointment.',
                    'clinical_notes' => 'Procedure completed successfully. Patient tolerated well.',
                    'diagnosis' => $appointment->service?->name ?? 'General',
                    'prescription' => 'Paracetamol 500mg if pain; maintain oral hygiene.',
                    'treatment_plan' => 'Follow-up as needed.',
                    'cost' => $appointment->cost,
                    'notes' => 'Seeded record',
                ]
            );
        }
    }
}
