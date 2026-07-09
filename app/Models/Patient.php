<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'file_number',
        'name',
        'phone',
        'email',
        'gender',
        'date_of_birth',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'medical_history',
        'dental_history',
        'allergies',
        'conditions',
        'notes',
        'new_patient',
        'registered_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'new_patient' => 'boolean',
    ];

    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function clinicalRecords()
    {
        return $this->hasMany(ClinicalRecord::class);
    }

    public function documents()
    {
        return $this->hasMany(PatientDocument::class);
    }

    public function latestAppointment()
    {
        return $this->hasOne(Appointment::class)->latest('appointment_date');
    }
}
