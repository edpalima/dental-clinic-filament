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
        'procedures',
        'date',
        'time_id',
        'time_end',
        'notes',
        'cancelled_reason',
        'payment_options',
        'total_amount',
        'account_number',
        'reference_number',
        'status',
        'approved_by',
        'agreement_accepted',
        'archived',
        'no_show',
    ];

    protected $casts = [
        'procedures' => 'array',
        'procedures_data' => 'array',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // public function procedure()
    // {
    //     return $this->belongsTo(Procedure::class);
    // }

    public function procedures()
    {
        return $this->belongsToMany(Procedure::class, 'appointment_procedure');
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

    public function scopeCompleted($query)
    {
        return $query->where('status', 'COMPLETED');
    }

    public function scopeArchived($query)
    {
        return $query->where('archived', true);
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

            $builder->where('archived', false);
        });

        static::saving(function ($model) {
            // Calculate total_amount before saving
            $totalAmount = collect($model->items ?? [])
                ->sum(fn($item) => (float) ($item['amount'] ?? 0));

            $model->total_amount = $totalAmount; // Set the total_amount
        });
    }
}
