<?php

namespace App\Livewire;

use App\Models\Patient;
use Livewire\Component;

class PatientRegistration extends Component
{
    public $first_name, $middle_name, $last_name, $nickname, $religion, $nationality;
    public $gender, $occupation, $contact_no, $address;
    public $dental_insurance, $insurance_effective_date;
    public $guardian_name, $guardian_occupation;
    public $referrer, $reason;
    public $is_good_health, $is_medical_treatment, $is_illness_operated, $is_hospitalized;
    public $is_has_medication, $is_using_tobacco, $is_has_vice;
    public $is_medical_treatment_name, $is_illness_operated_name, $is_hospitalized_name, $is_has_medication_name;

    public function submitForm()
    {
        $this->validate([
            // Validation rules for each field
        ]);
        
        // Save the data or perform other actions
        session()->flash('message', 'Form submitted successfully!');
        return redirect()->to('/thank-you');
    }

    public function create(): void
    {
        dd($this->form->getState());
    }

    public function render()
    {
        return view('livewire.patient-registration');
    }
}
