<?php

namespace App\Filament\Pages;

use App\Models\Patient;
use App\Models\User;
use Filament\Forms;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Gate;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Illuminate\Support\HtmlString;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Validation\ValidationException;
use Filament\Navigation\NavigationItem;

class MyProfile extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static string $view = 'filament.pages.my-profile';

    protected static ?string $navigationGroup = 'Account';

    protected static ?string $navigationLabel = 'My Profile';

    public Patient $patient;

    public function mount()
    {
        if (Auth::user()->role !== User::ROLE_PATIENT) {
            abort(403);
        }

        $this->form->fill(auth()->user()->patient->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')->schema([
                    Forms\Components\TextInput::make('first_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('middle_name')
                        ->maxLength(255)
                        ->default(null),
                    Forms\Components\TextInput::make('last_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('nickname')
                        ->maxLength(255)
                        ->default(null),
                    Forms\Components\TextInput::make('religion')
                        ->maxLength(255)
                        ->required(),
                    Forms\Components\TextInput::make('nationality')
                        ->maxLength(255)
                        ->required(),
                    Forms\Components\Select::make('gender')
                        ->options([
                            'male' => 'Male',
                            'female' => 'Female',
                        ])
                        ->label('Gender')
                        ->required(),
                    Forms\Components\TextInput::make('occupation')
                        ->maxLength(255)
                        ->required(),
                    Forms\Components\TextInput::make('contact_no')
                        ->integer()
                        ->maxLength(255)
                        ->default(null),

                    Forms\Components\TextInput::make('address')
                        ->maxLength(255)
                        ->default(null)
                        ->columnSpanFull(),
                ])->columns(3),

                Forms\Components\Section::make('Dental Insurance')->schema([
                    Forms\Components\TextInput::make('dental_insurance')
                        ->label('Insurance Name'),
                    Forms\Components\DatePicker::make('insurance_effective_date'),
                ])->columns(2),

                Forms\Components\Section::make('Guardian Information For Minors')->schema([
                    Forms\Components\TextInput::make('guardian_name')
                        ->maxLength(255)
                        ->default(null),
                    Forms\Components\TextInput::make('guardian_occupation')
                        ->maxLength(255)
                        ->default(null),
                ])->columns(),

                Forms\Components\Section::make('Referrer')->schema([
                    Forms\Components\TextInput::make('referrer')
                        ->label('Whom may we thank for referring you?')
                        ->maxLength(255)
                        ->default(null),
                    Forms\Components\TextInput::make('reason')
                        ->label('What is your reason for dental consultation?')
                        ->maxLength(255)
                        ->default(null),
                ])->columns(),

                Forms\Components\Section::make('Dental History')->schema([

                    Forms\Components\TextInput::make('previous_dentist')
                        ->maxLength(255)
                        ->default(null),
                    Forms\Components\DatePicker::make('last_dental_visit'),
                ])->columns(),

                Forms\Components\Section::make('Medical History')->schema([
                    Forms\Components\TextInput::make('physician_name')
                        ->label('Name of Physician')
                        ->maxLength(255)
                        ->default(null),
                    Forms\Components\TextInput::make('physician_specialty')
                        ->label('Specialty, if applicable')
                        ->maxLength(255)
                        ->default(null),
                    Forms\Components\TextInput::make('physician_office')
                        ->label('Office Address')
                        ->maxLength(255)
                        ->default(null),
                    Forms\Components\TextInput::make('physician_number')
                        ->label('Office Number')
                        ->maxLength(255)
                        ->default(null),

                    Forms\Components\Toggle::make('is_good_health')
                        ->label('1. Are you in good health?')
                        ->required(),
                    Forms\Components\Toggle::make('is_medical_treatment')
                        ->label('2. Are you under medical treatment now?')
                        ->required(),
                    Forms\Components\TextInput::make('is_medical_treatment_name')
                        ->label('If so, what is the condition being treated?')
                        ->maxLength(255)
                        ->default(null),
                    Forms\Components\Toggle::make('is_illness_operated')
                        ->label('3. Have you ever had a serious illness or surgical operation?')
                        ->required(),
                    Forms\Components\TextInput::make('is_illness_operated_name')
                        ->label('If so, when and why?')
                        ->maxLength(255)
                        ->default(null),
                    Forms\Components\Toggle::make('is_hospitalized')
                        ->label('4. Have you ever been hospitalized?')
                        ->required(),
                    Forms\Components\TextInput::make('is_hospitalized_name')
                        ->label('If so, when and why?')
                        ->maxLength(255)
                        ->default(null),
                    Forms\Components\Toggle::make('is_has_medication')
                        ->label('5. Are you taking any prescription/non-prescription medication?')
                        ->required(),
                    Forms\Components\TextInput::make('is_has_medication_name')
                        ->label('If so, please specify')
                        ->maxLength(255)
                        ->default(null),
                    Forms\Components\Toggle::make('is_using_tobacco')
                        ->label('6. Do you use tobacco products?')
                        ->required(),
                    Forms\Components\Toggle::make('is_has_vice')
                        ->label('7. Do you use alcohol, cocaine, or other dangerous drugs?')
                        ->required(),
                    Forms\Components\CheckboxList::make('check_allergies')
                        ->label('8. Are you allergic to any of the following?')
                        ->options([
                            'local_anesthetic' => 'Local Anesthetic',
                            'sulfa_drugs' => 'Sulfa drugs',
                            'aspirin' => 'Aspirin',
                            'latext' => 'Latext',
                            'others' => 'Others',
                        ])
                        ->default(null),
                    Forms\Components\TextInput::make('bleeding_time')
                        ->label('9. Bleeding Time')
                        ->default(null),

                    Forms\Components\Fieldset::make('10. For women only:')
                        ->schema([
                            Forms\Components\Toggle::make('is_pregnant')
                                ->label('Are you pregnant?'),
                            Forms\Components\Toggle::make('is_nursing')
                                ->label('For women only: Are you nursing?'),
                            Forms\Components\Toggle::make('is_taking_pills')
                                ->label('For women only: Are you taking birth control pills?'),
                        ])
                        ->columns(1),

                    Forms\Components\Select::make('blood_type')
                        ->label('11. Blood Type')->options([
                            'A+' => 'A+',
                            'A-' => 'A-',
                            'B+' => 'B+',
                            'B-' => 'B-',
                            'AB+' => 'AB+',
                            'AB-' => 'AB-',
                            'O+' => 'O+',
                            'O-' => 'O-',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('blood_pressure')
                        ->label('12. Blood Pressure')
                        ->maxLength(255)
                        ->default(null),
                    Forms\Components\CheckboxList::make('check_illness')
                        ->label('13. Do you have or have you had any of the following? Check which apply')
                        ->options([
                            'high_blood_pressure' => 'High Blood Pressure',
                            'low_blood_pressure' => 'Low Blood Pressure',
                            'epilepsy_convulsions' => 'Epilepsy/Convulsions',
                            'aids_hiv' => 'AIDS or HIV Infection',
                            'std' => 'Sexually Transmitted Disease',
                            'stomach_ulcers' => 'Stomach Troubles / Ulcers',
                            'fainting_seizure' => 'Fainting Seizure',
                            'rapid_weight_loss' => 'Rapid Weight Loss',
                            'radiation_therapy' => 'Radiation Therapy',
                            'joint_replacement_implant' => 'Joint Replacement / Implant',
                            'heart_surgery' => 'Heart Surgery',
                            'heart_attack' => 'Heart Attack',
                            'thyroid_problem' => 'Thyroid Problem',
                            'heart_disease' => 'Heart Disease',
                            'heart_murmur' => 'Heart Murmur',
                            'hepatitis_liver_disease' => 'Hepatitis/Liver Disease',
                            'rheumatic_fever' => 'Rheumatic Fever',
                            'hay_fever_allergies' => 'Hay Fever / Allergies',
                            'respiratory_problems' => 'Respiratory Problems',
                            'hepatitis_jaundice' => 'Hepatitis / Jaundice',
                            'tuberculosis' => 'Tuberculosis',
                            'swollen_ankles' => 'Swollen Ankles',
                            'kidney_disease' => 'Kidney Disease',
                            'diabetes' => 'Diabetes',
                            'chest_pain' => 'Chest Pain',
                            'stroke' => 'Stroke',
                            'cancer_tumors' => 'Cancer/Tumors',
                            'anemia' => 'Anemia',
                            'angina' => 'Angina',
                            'asthma' => 'Asthma',
                            'emphysema' => 'Emphysema',
                            'bleeding_problems' => 'Bleeding Problems',
                            'blood_diseases' => 'Blood Diseases',
                            'head_injuries' => 'Head Injuries',
                            'arthritis_rheumatism' => 'Arthritis / Rheumatism',
                            'other' => 'Other',
                        ])
                        ->columns(3),
                ])->columns(1),

                Forms\Components\Checkbox::make('check_consent')
                    ->label(fn () => new HtmlString('I accept the <a href="/consent-agreement" target="_blank" style="color: red">Consents Agreement</a>'))
                    ->accepted()
                    ->validationMessages([
                        'accepted' => 'You must accept the Consents Agreement.',
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Submit')
                ->submit('save'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->role === User::ROLE_PATIENT;
    }

    public function save()
    {
        try {
            $data = $this->form->getState();

            auth()->user()->patient->update($data);
        } catch (Halt $exception) {
            return;
        }

        Notification::make()
            ->success()
            ->title('Saved Successfully')
            ->send();
    }
}
