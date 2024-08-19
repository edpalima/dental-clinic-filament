<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public function items()
    {
        return $this->hasMany(Item::class);
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

    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'CONFIRMED');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'CANCELLED');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'REJECTED');
    }

    protected static function booted()
    {
        static::addGlobalScope('userRoleFilter', function (Builder $builder) {
            if (Auth::check()) {
                $user = Auth::user();

                if ($user->role === 'PATIENT') {
                    $patient = $user->patient;

                    if ($patient) {
                        $builder->where('patient_id', $patient->id);
                    }
                    // If the user is ADMIN, no filtering is applied, so they see all appointments.
                }
            }
        });
    }
}
