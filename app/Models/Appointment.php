<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    const BOOKED = 0;

    const COMPLETED = 1;

    const CANCELLED = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'healthcare_professional_id',
        'appointment_start_time',
        'appointment_end_time',
        'status',
    ];

    protected $casts = [
        'appointment_start_time' => 'datetime',
        'appointment_end_time' => 'datetime',
    ];

    public function canBeCancelled()
    {
        return $this->status === self::BOOKED &&
        $this->appointment_start_time->greaterThan(Carbon::now()->addHours(24));
    }

    public function canBeCompleted()
    {
        return $this->status === self::BOOKED;
    }
}
