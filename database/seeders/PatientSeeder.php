<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reception = User::where('role', 'reception')->first();
        $receptionId = $reception?->id ?? 1;

        $patients = [
            ['file_number' => 'MV-0001', 'name' => 'John Mwansa', 'phone' => '+255 711 111 111', 'email' => 'john@example.com', 'gender' => 'male', 'date_of_birth' => '1988-03-15', 'address' => 'Kinondoni, Dar es Salaam', 'medical_history' => 'Hypertension', 'dental_history' => 'Had extraction in 2020', 'new_patient' => false],
            ['file_number' => 'MV-0002', 'name' => 'Maria Joseph', 'phone' => '+255 712 222 222', 'email' => 'maria@example.com', 'gender' => 'female', 'date_of_birth' => '1995-07-22', 'address' => 'Ilala, Dar es Salaam', 'medical_history' => 'None', 'dental_history' => 'Regular cleanings', 'new_patient' => false],
            ['file_number' => 'MV-0003', 'name' => 'Peter Chen', 'phone' => '+255 713 333 333', 'email' => 'peter@example.com', 'gender' => 'male', 'date_of_birth' => '1979-11-05', 'address' => 'Upanga, Dar es Salaam', 'medical_history' => 'Diabetes type 2', 'dental_history' => 'Crown fitted 2021', 'new_patient' => false],
            ['file_number' => 'MV-0004', 'name' => 'Grace Ochieng', 'phone' => '+255 714 444 444', 'email' => 'grace@example.com', 'gender' => 'female', 'date_of_birth' => '1990-01-30', 'address' => 'Masaki, Dar es Salaam', 'medical_history' => 'None', 'dental_history' => 'Braces removed 2022', 'new_patient' => true],
            ['file_number' => 'MV-0005', 'name' => 'Michael Issa', 'phone' => '+255 715 555 555', 'email' => 'michael@example.com', 'gender' => 'male', 'date_of_birth' => '1983-09-12', 'address' => 'Mbezi, Dar es Salaam', 'medical_history' => 'Asthma', 'dental_history' => 'Whitening done 2023', 'new_patient' => true],
            ['file_number' => 'MV-0006', 'name' => 'Amina Salim', 'phone' => '+255 716 666 666', 'email' => 'amina@example.com', 'gender' => 'female', 'date_of_birth' => '1998-05-18', 'address' => 'Kariakoo, Dar es Salaam', 'medical_history' => 'None', 'dental_history' => 'First visit', 'new_patient' => true],
        ];

        foreach ($patients as $patient) {
            Patient::updateOrCreate(
                ['file_number' => $patient['file_number']],
                array_merge($patient, ['registered_by' => $receptionId])
            );
        }
    }
}
