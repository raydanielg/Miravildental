<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_BOOKED = 'booked';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_ARRIVED = 'arrived';
    public const STATUS_IN_TREATMENT = 'in_treatment';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_NO_SHOW = 'no_show';

    public const STATUSES = [
        self::STATUS_BOOKED,
        self::STATUS_CONFIRMED,
        self::STATUS_ARRIVED,
        self::STATUS_IN_TREATMENT,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED,
        self::STATUS_NO_SHOW,
    ];

    public const STATUS_COLORS = [
        self::STATUS_BOOKED => 'gray',
        self::STATUS_CONFIRMED => 'blue',
        self::STATUS_ARRIVED => 'purple',
        self::STATUS_IN_TREATMENT => 'amber',
        self::STATUS_COMPLETED => 'emerald',
        self::STATUS_CANCELLED => 'red',
        self::STATUS_NO_SHOW => 'red',
    ];

    public const STATUS_LABELS = [
        self::STATUS_BOOKED => 'Booked',
        self::STATUS_CONFIRMED => 'Confirmed',
        self::STATUS_ARRIVED => 'Arrived',
        self::STATUS_IN_TREATMENT => 'In Treatment',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_CANCELLED => 'Cancelled',
        self::STATUS_NO_SHOW => 'No-show',
    ];

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'service_id',
        'room_id',
        'appointment_date',
        'start_time',
        'end_time',
        'status',
        'notes',
        'cost',
        'reminder_24h_sent',
        'reminder_2h_sent',
        'reminded_at',
        'booked_by',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'cost' => 'decimal:2',
        'reminder_24h_sent' => 'boolean',
        'reminder_2h_sent' => 'boolean',
        'reminded_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function bookedBy()
    {
        return $this->belongsTo(User::class, 'booked_by');
    }

    public function clinicalRecord()
    {
        return $this->hasOne(ClinicalRecord::class);
    }

    public function smsLogs()
    {
        return $this->hasMany(SmsLog::class);
    }

    public function statusColor(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'gray';
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }

    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('appointment_date', '>=', today())->whereNotIn('status', [self::STATUS_CANCELLED, self::STATUS_NO_SHOW]);
    }

    public function scopeForDoctor($query, int $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }
}
