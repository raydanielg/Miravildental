<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_name',
        'phone',
        'email',
        'address',
        'sender_id',
        'default_appointment_duration',
        'reminder_24h_before',
        'reminder_2h_before',
        'recall_after_days',
        'currency',
        'timezone',
    ];

    protected $casts = [
        'default_appointment_duration' => 'integer',
        'reminder_24h_before' => 'integer',
        'reminder_2h_before' => 'integer',
        'recall_after_days' => 'integer',
    ];

    public static function current(): ?self
    {
        return self::first();
    }
}
