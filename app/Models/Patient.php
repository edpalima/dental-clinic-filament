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
        'is_good_health',
        'is_medical_treatment',
        'medical_condition',
        'is_illness_operated',
        'illness_operated_details',
        'is_hospitalized',
        'hospitalized_details',
        'is_has_medication',
        'medication_details',
        'is_using_tobacco',
        'is_has_vice',
        'allergies',
        'bleeding_time',
        'is_pregnant',
        'is_nursing',
        'is_taking_pills',
        'blood_type',
        'blood_pressure',
        'medical_conditions',
        'check_consent',
    ];

    protected $hidden = ['password'];
}
