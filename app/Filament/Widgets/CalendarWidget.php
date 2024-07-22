<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Procedure;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Saade\FilamentFullCalendar\Actions;
use Filament\Forms;
use App\Models\Time;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class CalendarWidget extends FullCalendarWidget
{
    public Model | string | null $model = Appointment::class;

    public function fetchEvents(array $fetchInfo): array
    {
        return Appointment::where('date', '>=', $fetchInfo['start'])
            ->where('date', '<=', $fetchInfo['end'])
            ->get()
            ->map(function (Appointment $appointment) {
                return [
                    'id'    => $appointment->id,
                    'title' => $appointment->patient->fullName,
                    'start' => $appointment->date,
                    'end'   => $appointment->date,
                    // 'url' => AppointmentResource::getUrl(name: 'view', parameters: ['record' => $appointment]),
                    // 'shouldOpenUrlInNewTab' => true
                ];
            })
            ->toArray();
    }

    public function getFormSchema(): array
    {
        // Fetch existing appointments
        $existingAppointments = Appointment::all();

        // Get booked dates and times
        $bookedDates = $existingAppointments->pluck('appointment_date')->toArray();
        $bookedTimes = [];
        foreach ($existingAppointments as $appointment) {
            $bookedTimes[$appointment->appointment_date][] = $appointment->appointment_time;
        }

        $user = Auth::user();

        return [
            Wizard::make([
                Wizard\Step::make('Schedule')
                    ->schema([
                        // Forms\Components\DatePicker::make('date')
                        //     ->required()
                        //     ->minDate(now()->addDay()) // Ensure booking starts from tomorrow
                        //     ->disabledDates($bookedDates), // Disable already booked dates
                        // Forms\Components\Select::make('time')
                        //     ->options(self::getAvailableTimes($bookedTimes))
                        //     ->required(),

                        Forms\Components\DatePicker::make('date')
                            ->required()
                            // ->disabledDates(['2000-01-03', '2000-01-15', '2000-01-20'])
                            ->live()
                            ->minDate(now()->addDay()) // Ensure booking starts from tomorrow
                            ->afterStateUpdated(fn ($state, callable $get, callable $set) => $set('time_id', null)),
                        Forms\Components\Radio::make('time_id')
                            ->label('Appointment Time')
                            ->options(function (callable $get) {
                                $selectedDate = $get('date');
                                if (!$selectedDate) {
                                    return [];
                                }

                                // Get all booked time slots for the selected date
                                $bookedTimeIds = Appointment::whereDate('date', $selectedDate)
                                    ->pluck('time_id')
                                    ->toArray();

                                // Get all available time slots
                                $allTimeSlots = Time::pluck('name', 'id')->toArray();

                                return $allTimeSlots;
                            })
                            ->disableOptionWhen(function ($value, callable $get) {
                                $selectedDate = $get('date');
                                $currentAppointmentId = $get('id'); // Assuming 'id' is the appointment ID

                                if (!$selectedDate) {
                                    return false;
                                }

                                // Get all booked time slots for the selected date, excluding the current appointment's time
                                $bookedTimeIds = Appointment::whereDate('date', $selectedDate)
                                    ->when($currentAppointmentId, function ($query, $id) {
                                        return $query->where('id', '!=', $id);
                                    })
                                    ->pluck('time_id')
                                    ->toArray();

                                // Disable the option if it is in the booked time slots
                                return in_array($value, $bookedTimeIds);
                            })
                            ->hidden(fn (callable $get) => !$get('date'))
                            ->required(fn (callable $get) => $get('date') !== null)
                            ->extraInputAttributes(['class' => 'select-time-disable']),

                    ]),
                Wizard\Step::make('Assign')
                    ->schema([
                        $user->role == 'ADMIN' ?
                            Forms\Components\Select::make('patient_id')
                            ->relationship('patient', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn (Patient $record) => "{$record->first_name} {$record->last_name}")
                            ->required()
                            :
                            Forms\Components\Hidden::make('patient_id')
                            ->default($user->patient->id),

                        Forms\Components\Select::make('doctor_id')
                            ->relationship(name: 'doctor', titleAttribute: 'first_name')
                            ->getOptionLabelFromRecordUsing(fn (Doctor $record) => "{$record->first_name} {$record->last_name}")
                            ->required(),
                        Forms\Components\Select::make('procedure_id')
                            ->relationship(name: 'procedure', titleAttribute: 'name')
                            ->live()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('amount', Procedure::find($state)?->cost)),
                    ]),
                Wizard\Step::make('Payments')
                    ->schema([
                        Forms\Components\Select::make('payment_options')
                            ->options([
                                'otc' => 'Over The Counter',
                                'gcash' => 'GCash',
                            ])
                            ->label('Payment Options')
                            ->required()
                            ->live(),
                        Forms\Components\Placeholder::make('documentation')
                            ->label('Scan QR To Pay')
                            ->visible(fn ($get) => $get('payment_options') === 'gcash')
                            ->content(new HtmlString('<img src="/assets/img/gcash_qr.jpg"/>')),
                        Forms\Components\TextInput::make('amount')
                            ->default(fn ($record) => $record ? Procedure::find($record->procedure_id)?->cost : 0)
                            ->live()
                            ->disabledOn('edit')
                            ->prefix('₱')
                            ->dehydrateStateUsing(fn ($state, $get) => $state ? Procedure::find($get('procedure_id'))?->cost : 0),
                        Forms\Components\TextInput::make('account_number')
                            ->numeric()
                            ->visible(fn ($get) => $get('payment_options') === 'gcash')
                            ->required(fn ($get) => $get('payment_options') === 'gcash'),
                        Forms\Components\TextInput::make('reference_number')
                            ->numeric()
                            ->visible(fn ($get) => $get('payment_options') === 'gcash')
                            ->required(fn ($get) => $get('payment_options') === 'gcash'),
                        Forms\Components\Textarea::make('notes')
                            ->columnSpanFull(),
                        $user->role == 'ADMIN'
                            ? Forms\Components\Select::make('status')
                            ->options([
                                'PENDING' => 'PENDING',
                                'CONFIRMED' => 'CONFIRMED',
                                'CANCELLED' => 'CANCELLED',
                                'REJECTED' => 'REJECTED',
                            ])
                            ->required()
                            ->live()
                            :  Forms\Components\Select::make('status')
                            ->options([
                                'PENDING' => 'Pending',
                                'CANCELLED' => 'Cancelled',
                            ])
                            ->default('PENDING')
                            ->required()
                            ->live()
                            ->hiddenOn('create'), // Visible only on create

                        // Cancelled reason field
                        Forms\Components\Hidden::make('cancelled_reason_visible')
                            ->default(fn ($get) => $get('status') === 'CANCELLED'),
                        Forms\Components\Textarea::make('cancelled_reason')
                            ->label('Cancelled Reason')
                            ->visible(fn ($get) => $get('status') === 'CANCELLED')
                            ->required(fn ($record, $get) => $get('status') === 'CANCELLED')
                            ->columnSpanFull(),
                    ]),
            ]),
        ];
    }

    protected function headerActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function modalActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public static function canView(): bool
    {
        return false;
    }
}
