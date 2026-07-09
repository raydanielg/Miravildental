<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'trigger',
        'body',
        'is_active',
        'send_before_hours',
        'send_after_days',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'send_before_hours' => 'integer',
        'send_after_days' => 'integer',
    ];

    public function logs()
    {
        return $this->hasMany(SmsLog::class, 'trigger', 'trigger');
    }
}
