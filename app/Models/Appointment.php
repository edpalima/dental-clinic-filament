<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'procedure_id',
        'date',
        'time_id',
        'notes',
        'cancelled_reason',
        'payment_options',
        'amount',
        'account_number',
        'reference_number',
        'status',
        'approved_by',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }

    public function time()
    {
        return $this->belongsTo(Time::class);
    }

    public static function getBookedTimes($doctorId, $date)
    {
        return self::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->pluck('appointment_date')
            ->map(function ($date) {
                return Carbon::parse($date)->format('H:00');
            });
    }
}
