<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'nickname',
        'religion',
        'nationality',
        'gender',
        'occupation',
        'dental_insurance',
        'insurance_effective_date',
        'address',
        'contact_no',
        'email',
        'password',
        'guardian_name',
        'guardian_occupation',
        'referrer',
        'reason',
        'previous_dentist',
        'last_dental_visit',
        'physician_name',
        'physician_specialty',
        'physician_office',
        'physician_number',
        'is_good_health',
        'is_medical_treatment',
        'is_medical_treatmenint_name',
        'is_illness_operated',
        'is_illness_operated_name',
        'is_hospitalized',
        'is_hospitalized_name',
        'is_has_medication',
        'is_has_medication_name',
        'is_using_tobacco',
        'is_has_vice',
        'check_allergies',
        'bleeding_time',
        'is_pregnant',
        'is_nursing',
        'is_taking_pills',
        'blood_type',
        'blood_pressure',
        'medical_conditions',
        'check_illness',
        'check_consent',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'check_allergies' => 'array',
        'check_illness' => 'array',
    ];

    public function getFullnameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
