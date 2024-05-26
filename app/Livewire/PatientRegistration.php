<?php

namespace App\Livewire;

use App\Models\Patient;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Filament\Notifications\Notification;

class PatientRegistration extends Component
{
    // Patient Information
    public $first_name;
    public $middle_name;
    public $last_name;
    public $nickname;
    public $religion;
    public $nationality;
    public $gender;
    public $occupation;
    public $contact_no;
    public $address;
    public $email;
    public $password;
    public $passwordConfirmation;

    // Dental Insurance
    public $dental_insurance;
    public $insurance_effective_date;

    // Guardian
    public $guardian_name;
    public $guardian_occupation;
    public $referrer;
    public $reason;
    public $previous_dentist;
    public $last_dental_visit;


    // // Medical Information
    public $physician_name;
    public $physician_specialty;
    public $physician_office;
    public $physician_number;
    public $is_good_health;
    public $is_medical_treatment;
    public $is_medical_treatment_name;
    public $is_illness_operated;
    public $is_illness_operated_name;
    public $is_hospitalized;
    public $is_hospitalized_name;
    public $is_has_medication;
    public $is_has_medication_name;
    public $is_using_tobacco;
    public $is_has_vice;
    public $check_allergies = [];
    public $bleeding_time;
    public $is_pregnant;
    public $is_nursing;
    public $is_taking_pills;
    public $blood_type;
    public $blood_pressure;
    public $check_illness = [];
    public $check_consent;

    public function submitForm()
    {
        // dd($this->check_allergies);
        // dd($this->all());
        // ($this->first_name);
        $this->validate([
            'first_name'                 => 'required|string|max:255',
            'middle_name'                => 'nullable|string|max:255',
            'last_name'                  => 'required|string|max:255',
            'nickname'                   => 'nullable|string|max:255',
            'religion'                   => 'required|string|max:255',
            'nationality'                => 'required|string|max:255',
            'gender'                     => 'required|in:male,female',
            'occupation'                 => 'required|string|max:255',
            'contact_no'                 => 'required|string|max:255',
            'address'                    => 'required|string|max:255',
            'email'                      => 'required|string|email|max:255|unique:users|unique:patients',
            'password'                   => 'required|string|min:8',
            'passwordConfirmation'       => 'required|same:password',
            'check_consent'              => 'required|boolean|accepted',
            'dental_insurance'           => 'nullable|string|max:255',
            'insurance_effective_date'   => [
                'nullable',
                'date',
                'date_format:Y-m-d',
                'regex:/^(19|20)\d\d-[01]\d-[0-3]\d$/'
            ],
            'guardian_name'              => 'nullable|string|max:255',
            'guardian_occupation'        => 'nullable|string|max:255',
            'referrer'                   => 'nullable|string|max:255',
            'reason'                     => 'nullable|string|max:255',
            'previous_dentist'           => 'nullable|string|max:255',
            'last_dental_visit'          => [
                'nullable',
                'date',
                'date_format:Y-m-d',
                'regex:/^(19|20)\d\d-[01]\d-[0-3]\d$/'
            ],
            'physician_name'             => 'nullable|string|max:255',
            'physician_specialty'        => 'nullable|string|max:255',
            'physician_office'           => 'nullable|string|max:255',
            'physician_number'           => 'nullable|string|max:255',
            'is_good_health'             => 'required|boolean',
            'is_medical_treatment'       => 'required|boolean',
            'is_medical_treatment_name'  => 'nullable|string|max:255',
            'is_illness_operated'        => 'required|boolean',
            'is_illness_operated_name'   => 'nullable|string|max:255',
            'is_hospitalized'            => 'required|boolean',
            'is_hospitalized_name'       => 'nullable|string|max:255',
            'is_has_medication'          => 'required|boolean',
            'is_has_medication_name'     => 'nullable|string|max:255',
            'is_using_tobacco'           => 'required|boolean',
            'is_has_vice'                => 'required|boolean',
            'check_allergies'            => 'nullable|array',
            'bleeding_time'              => 'nullable|string|max:255',
            'is_pregnant'                => 'required|boolean',
            'is_nursing'                 => 'required|boolean',
            'is_taking_pills'            => 'required|boolean',
            'blood_type'                 => 'nullable|string|max:255',
            'blood_pressure'             => 'nullable|string|max:255',
            'check_illness'              => 'nullable|array',
        ]);

        if ($this->insurance_effective_date == "") {
            $this->insurance_effective_date = null;
        }

        if ($this->last_dental_visit == "") {
            $this->last_dental_visit = null;
        }

        $patient = new Patient([
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'nickname' => $this->nickname,
            'religion' => $this->religion,
            'nationality' => $this->nationality,
            'gender' => $this->gender,
            'occupation' => $this->occupation,
            'contact_no' => $this->contact_no,
            'address' => $this->address,
            'dental_insurance' => $this->dental_insurance,
            'insurance_effective_date' => $this->insurance_effective_date,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'guardian_name' => $this->guardian_name,
            'guardian_occupation' => $this->guardian_occupation,
            'referrer' => $this->referrer,
            'reason' => $this->reason,
            'previous_dentist' => $this->previous_dentist,
            'last_dental_visit' => $this->last_dental_visit,
            'physician_name' => $this->physician_name,
            'physician_specialty' => $this->physician_specialty,
            'physician_office' => $this->physician_office,
            'physician_number' => $this->physician_number,
            'is_good_health' => $this->is_good_health,
            'is_medical_treatment' => $this->is_medical_treatment,
            'is_medical_treatment_name' => $this->is_medical_treatment_name,
            'is_illness_operated' => $this->is_illness_operated,
            'is_illness_operated_name' => $this->is_illness_operated_name,
            'is_hospitalized' => $this->is_hospitalized,
            'is_hospitalized_name' => $this->is_hospitalized_name,
            'is_has_medication' => $this->is_has_medication,
            'is_has_medication_name' => $this->is_has_medication_name,
            'is_using_tobacco' => $this->is_using_tobacco,
            'is_has_vice' => $this->is_has_vice,
            'check_allergies' => $this->check_allergies,
            'bleeding_time' => $this->bleeding_time,
            'is_pregnant' => $this->is_pregnant,
            'is_nursing' => $this->is_nursing,
            'is_taking_pills' => $this->is_taking_pills,
            'blood_type' => $this->blood_type,
            'blood_pressure' => $this->blood_pressure,
            'check_illness' => $this->check_illness,
            'check_consent' => $this->check_consent,
        ]);

        try {
            if ($patient->save()) {
                User::create([
                    'name' => $this->first_name . ' ' . $this->last_name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'role' => User::ROLE_PATIENT,
                ]);
            }
        } catch (Exception $e) {
            // Handle the exception if save throws an error
            // throw new Exception('Error saving patient: ' . $e->getMessage());
            dd($e->getMessage());
        }

        $patient->save();

        Notification::make()
            ->title('Registered successfully')
            ->success()
            ->send();

        return redirect('/admin/login');
    }

    public function render()
    {
        return view('livewire.patient-registration');
    }
}
