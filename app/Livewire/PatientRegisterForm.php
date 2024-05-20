<?php

namespace App\Livewire;

use App\Filament\Resources\PatientResource;
use App\Models\Patient;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Livewire\Component;

class PatientRegisterForm extends Component implements HasForms
{
    use InteractsWithForms;

    public function mount()
    {
        $this->form->fill([]);
    }

    public function submit()
    {
        $data = $this->form->getState();

        // Validate and save data
        Patient::create($data);

        session()->flash('message', 'Form submitted successfully.');
    }
    public function render()
    {
        return view('livewire.patient-register-form', [
            'form' => PatientResource::form(Form::make())->get(),
        ]);
    }
}
