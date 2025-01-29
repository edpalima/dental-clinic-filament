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
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
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
                    'status' => $appointment->status,
                    'end'   => $appointment->date,
                    // 'url' => AppointmentResource::getUrl(name: 'view', parameters: ['record' => $appointment]),
                    // 'shouldOpenUrlInNewTab' => true
                ];
            })
            ->toArray();
    }

    public function dayCellDidMount(): string
    {
        return <<<JS
            function({ date, el }) {
                const today = new Date();
                today.setHours(0, 0, 0, 0); // Reset today's time to midnight
                const cellDate = new Date(date).setHours(0, 0, 0, 0);

                if (cellDate < today) {
                    el.classList.add('fc-day-disabled'); // Add a custom CSS class
                    el.style.pointerEvents = 'none'; // Disable interaction
                    el.style.backgroundColor = '#f5f5f5'; // Gray out the background
                }
            }
        JS;
    }

    protected function getEventRender(): array
    {
        return [
            'eventRender' => 'function(event, element) {
                switch (event.status) {
                    case "CONFIRMED":
                        element.css("background-color", "#28a745"); // Green
                        break;
                    case "PENDING":
                        element.css("background-color", "#ffc107"); // Yellow
                        break;
                    case "REJECTED":
                        element.css("background-color", "#dc3545"); // Red
                        break;
                    case "REJECTED":
                        element.css("background-color", "#6c757d"); // Gray
                        break;
                    default:
                        element.css("background-color", "#007bff"); // Default Blue
                }
            }',
        ];
    }

    /**
     * Render hooks for FullCalendar
     */
    protected function getFullCalendarOptions(): array
    {
        return array_merge(parent::getFullCalendarOptions(), [
            'dayCellDidMount' => new HtmlString($this->dayCellDidMount()),
        ]);
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
            Section::make()
                ->schema([
                    // Details Section
                    Fieldset::make('SCHEDULE')
                        ->schema([
                            Forms\Components\DatePicker::make('date')
                                ->required()
                                // ->disabledDates(['2000-01-03', '2000-01-15', '2000-01-20'])
                                ->live()
                                ->minDate(now()->addDay()) // Ensure booking starts from tomorrow
                                ->afterStateUpdated(fn($state, callable $get, callable $set) => $set('time_id', null)),
                            Forms\Components\Select::make('time_id')
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
                                ->hidden(fn(callable $get) => !$get('date'))
                                ->required(fn(callable $get) => $get('date') !== null)
                                ->extraInputAttributes(['class' => 'select-time-disable']),

                        ]),

                    // Assign Section
                    Fieldset::make('DETAILS')
                        ->schema([
                            $user->role == 'ADMIN' ?
                                Forms\Components\Select::make('patient_id')
                                ->relationship('patient', 'first_name')
                                ->getOptionLabelFromRecordUsing(fn(Patient $record) => "{$record->first_name} {$record->last_name}")
                                ->required()
                                :
                                Forms\Components\Hidden::make('patient_id')
                                ->default($user->patient->id),

                            Forms\Components\Select::make('doctor_id')
                                ->relationship(name: 'doctor', titleAttribute: 'first_name')
                                ->getOptionLabelFromRecordUsing(fn(Doctor $record) => "{$record->first_name} {$record->last_name}")
                                ->required(),
                            Forms\Components\Select::make('procedure_id')
                                ->relationship(name: 'procedure', titleAttribute: 'name')
                                ->live()
                                ->afterStateUpdated(fn($state, callable $set) => $set('total_amount', Procedure::find($state)?->cost)),
                            Forms\Components\TextInput::make('total_amount')
                                ->live(),

                            Forms\Components\Textarea::make('notes')
                                ->columnSpanFull(),
                            $user->role == 'ADMIN'
                                ? Forms\Components\Select::make('status')
                                ->options([
                                    'PENDING' => 'PENDING',
                                    'CONFIRMED' => 'CONFIRMED',
                                    'CANCELLED' => 'CANCELLED',
                                    'REJECTED' => 'REJECTED',
                                    'COMPLETED' => 'COMPLETED',
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
                                ->default(fn($get) => $get('status') === 'CANCELLED'),
                            Forms\Components\Textarea::make('cancelled_reason')
                                ->label('Cancelled Reason')
                                ->visible(fn($get) => $get('status') === 'CANCELLED')
                                ->required(fn($record, $get) => $get('status') === 'CANCELLED')
                                ->columnSpanFull(),
                        ]),
                ])
        ];
    }

    protected function headerActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mountUsing(
                    function (Forms\Form $form, array $arguments) {
                        $form->fill([
                            'date' => $arguments['start'] ?? null,
                        ]);
                    }
                )
        ];
    }

    /**
     * Add Alpine.js tooltips to events.
     */
    public function eventDidMount(): string
    {
        return <<<JS
            function({ event, el }) {
                // Add Alpine.js attributes to each event
                el.setAttribute('x-data', '{ tooltip: false }');
                el.setAttribute('x-on:mouseenter', 'tooltip = true');
                el.setAttribute('x-on:mouseleave', 'tooltip = false');
                el.classList.add('relative');

                // Create a tooltip element
                const tooltipEl = document.createElement('div');
                tooltipEl.setAttribute('x-show', 'tooltip');
                tooltipEl.classList.add(
                    'absolute',
                    'bg-gray-700',
                    'text-white',
                    'text-sm',
                    'rounded',
                    'p-2',
                    'shadow-md',
                    'z-10',
                    'transform',
                    '-translate-x-1/2',
                    '-translate-y-full'
                );
                tooltipEl.style.top = '-5px'; // Positioning
                tooltipEl.style.left = '50%'; // Centered horizontally
                tooltipEl.textContent = event.title; // Tooltip content

                el.appendChild(tooltipEl); // Attach tooltip to the event
            }
        JS;
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
