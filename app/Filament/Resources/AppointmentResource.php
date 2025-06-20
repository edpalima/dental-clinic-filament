<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\Pages\EditAppointment;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Filament\Resources\AppointmentResource\RelationManagers\ItemsRelationManager;
// use App\Filament\Resources\Filters\AppointmentStatusFilter;
use App\Models\Appointment;
use App\Models\ClosedDay;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Procedure;
use App\Models\Time;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\HtmlString;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Barryvdh\DomPDF\Facade\Pdf;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Blade;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationGroup = 'Appointments';
    protected static ?string $navigationLabel = 'Table';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
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
        $defaultStatus = [
            'PENDING' => 'PENDING',
            'CANCELLED' => 'CANCELLED',
        ];

        if (
            $user->role != User::ROLE_PATIENT
            || ($user->role != User::ROLE_PATIENT && fn($get) => $get('id') !== null)
            || request()->routeIs('filament.admin.resources.appointments.view')
        ) {
            $defaultStatus['CONFIRMED'] = 'CONFIRMED';
            $defaultStatus['REJECTED'] = 'REJECTED';
            $defaultStatus['COMPLETED'] = 'COMPLETED';
        }

        // Get the selected date from the query parameter
        $selectedDate = request()->query('date'); // Fetch 'selected_date' from URL query parameters

        // Get the closed days
        $closedDays = ClosedDay::where('is_active', true)->get();
        $specificClosedDates = $closedDays->where('repeat_day', null)
            ->pluck('date')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->toArray();

        $repeatClosedDays = $closedDays->where('repeat_day', '!=', null)
            ->pluck('repeat_day')
            ->toArray();

        // Get repeat closed dates
        $repeatClosedDates = self::getRepeatClosedDates($repeatClosedDays);

        // Define the helper closure inside the form method
        $updateTimeEnd = function (callable $get, callable $set) {
            $procedureIds = $get('procedures') ?? [];
            $timeId = $get('time_id');

            if (!$procedureIds || !$timeId) {
                $set('time_end', null);
                return;
            }

            $startTime = \App\Models\Time::find($timeId);
            if (!$startTime || !$startTime->time_start) {
                $set('time_end', null);
                return;
            }

            $totalHours = \App\Models\Procedure::whereIn('id', $procedureIds)->sum('duration');

            $startCarbon = \Carbon\Carbon::createFromFormat('H:i:s', $startTime->time_start);
            $endCarbon = $startCarbon->copy()->addHours($totalHours);

            $set('time_end', $endCarbon->format('H:i'));
        };

        return $form
            ->schema([
                Section::make()
                    ->schema([
                        // Details Section
                        Fieldset::make('SCHEDULE')
                            ->columns(3)
                            ->schema([
                                Forms\Components\DatePicker::make('date')
                                    ->required()
                                    ->live()
                                    ->native(false)
                                    ->minDate(fn($get) => $get('id') === null ? today()->addDay() : null) // Ensure booking starts from tomorrow only on create forms
                                    ->default($selectedDate) // Set the default date if available in the query
                                    ->afterStateUpdated(fn($state, callable $get, callable $set) => $set('time_id', null))
                                    ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                        if ($state && $get('id') === null) {
                                            $set('time_id', null);
                                        }
                                    })
                                    ->disabled(fn(string $operation) => in_array($operation, ['edit']) || $selectedDate !== null)
                                    ->disabledDates(function () use ($specificClosedDates, $repeatClosedDates) {
                                        // Merge known closed dates
                                        $disabledDates = array_merge($specificClosedDates, $repeatClosedDates);

                                        // Add days where no time is available (check next 60 days from today)
                                        $datesToCheck = collect(range(0, 60))
                                            ->map(fn($i) => now()->addDays($i)->toDateString());

                                        $fullyUnavailableDates = $datesToCheck->filter(function ($date) {
                                            return \App\Models\Time::all()->every(fn($time) => !$time->isAvailableOnDate($date));
                                        })->values()->all();

                                        return array_merge($disabledDates, $fullyUnavailableDates);
                                    }),

                                Forms\Components\Select::make('time_id')
                                    ->label('Start Time')
                                    ->options(function (callable $get) {
                                        $selectedDate = $get('date');
                                        if (!$selectedDate) {
                                            return [];
                                        }

                                        // Return all time slot names with availability info
                                        $getTime = Time::all()->mapWithKeys(function ($time) use ($selectedDate, $get) {
                                            $label = $time->name;

                                            if ($get('id') == null) {
                                                if (!$time->isAvailableOnDate($selectedDate)) {
                                                    $label .= ' (Not Available)';
                                                }
                                            }


                                            return [$time->id => $label];
                                        })->toArray();

                                        return $getTime;
                                    })
                                    ->disableOptionWhen(function ($value, callable $get) {
                                        $selectedDate = $get('date');
                                        if (!$selectedDate || !$value) {
                                            return false;
                                        }

                                        $time = \App\Models\Time::find($value);
                                        return $time && !$time->isAvailableOnDate($selectedDate);
                                    })
                                    ->hidden(fn(callable $get) => !$get('date'))
                                    ->disabled(fn($get) => $get('id') !== null) // Disable if editing
                                    ->required(fn(callable $get) => $get('date') !== null)
                                    ->extraInputAttributes(['class' => 'select-time-disable'])
                                    ->validationAttribute('appointment time')
                                    ->reactive()
                                    ->rules([
                                        fn(callable $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                            $selectedDate = $get('date');
                                            $currentAppointmentId = $get('id'); // Get the current appointment ID

                                            if (!$selectedDate || !$value) {
                                                return; // Skip validation if no date or time selected
                                            }

                                            // Get all booked time slots for the selected date, excluding the current appointment
                                            $bookedTimeIds = Appointment::withoutGlobalScope('userRoleFilter')
                                                ->whereDate('date', $selectedDate)
                                                ->when($currentAppointmentId, function ($query, $id) {
                                                    return $query->where('id', '!=', $id);
                                                })
                                                ->pluck('time_id')
                                                ->toArray();

                                            // If the selected time is already booked, fail validation
                                            if (in_array($value, $bookedTimeIds)) {
                                                $fail('The selected time is already booked.');
                                            }
                                        },
                                    ])
                                    ->afterStateUpdated(fn($state, $get, $set) => $updateTimeEnd($get, $set)),

                                Forms\Components\TimePicker::make('time_end')
                                    ->label('End Time')
                                    ->hidden(fn(callable $get) => !$get('date'))
                                    ->required()
                                    ->disabled()
                                    ->reactive()
                                    ->dehydrated(),
                            ]),

                        // Assign Section
                        Fieldset::make('DETAILS')
                            ->schema([
                                $user->role != User::ROLE_PATIENT ?
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

                                // Procedure Multiple Selection
                                Forms\Components\Select::make('procedures')
                                    ->label('Procedure(s)')
                                    ->options(function ($get) {
                                        // Get the currently selected procedure IDs
                                        $selectedProcedureIds = $get('procedures') ?? [];

                                        // Get all procedures
                                        $allProcedures = Procedure::pluck('name', 'id')->toArray();

                                        // If no procedures are selected, return all procedures
                                        if (empty($selectedProcedureIds)) {
                                            return $allProcedures;
                                        }

                                        // Get all procedure IDs that can't be combined with the selected procedures
                                        $cantCombineIds = Procedure::whereIn('id', $selectedProcedureIds)
                                            ->pluck('cant_combine')
                                            ->flatten()
                                            ->filter()
                                            ->unique()
                                            ->toArray();

                                        // Include the selected procedures in the options to prevent overwriting
                                        $allowedProcedures = array_diff_key($allProcedures, array_flip($cantCombineIds));

                                        // Merge the selected procedures back into the options to ensure they remain selectable
                                        $selectedProcedures = Procedure::whereIn('id', $selectedProcedureIds)
                                            ->pluck('name', 'id')
                                            ->toArray();

                                        return $selectedProcedures + $allowedProcedures;
                                    })
                                    ->afterStateUpdated(fn($state, $get, $set) => $updateTimeEnd($get, $set))
                                    ->multiple()
                                    ->reactive()
                                    ->required()
                                    ->disabled(fn($get) => $get('id') !== null)
                                // ->disabled(fn(callable $get) => !$get('time_id'))
                                ,

                                $user->role != User::ROLE_PATIENT
                                    ? Forms\Components\Select::make('status')
                                    ->options([
                                        'PENDING' => 'PENDING',
                                        'CONFIRMED' => 'CONFIRMED',
                                        'CANCELLED' => 'CANCELLED',
                                        'REJECTED' => 'REJECTED',
                                        'COMPLETED' => 'COMPLETED', // Added Completed status
                                    ])
                                    ->required()
                                    ->live()
                                    :  Forms\Components\Select::make('status')
                                    ->options($defaultStatus)
                                    ->default('PENDING')
                                    ->required()
                                    ->live()
                                    ->hiddenOn('create'), // Visible only on create

                                Forms\Components\Textarea::make('notes')
                                    ->columnSpanFull(),

                                // Cancelled reason field
                                Forms\Components\Hidden::make('cancelled_reason_visible')
                                    ->default(fn($get) => $get('status') === 'CANCELLED'),
                                Forms\Components\Textarea::make('cancelled_reason')
                                    ->label('Cancelled Reason')
                                    ->visible(fn($get) => $get('status') === 'CANCELLED')
                                    ->required(fn($record, $get) => $get('status') === 'CANCELLED')
                                    ->columnSpanFull(),

                                // No-show Checkbox for Confirmed Status
                                Forms\Components\Checkbox::make('no_show')
                                    ->label('No Show')
                                    ->hidden(fn($get) => $get('status') !== 'CONFIRMED' || $user->role == User::ROLE_PATIENT)
                                    ->columnSpanFull(),

                                // // Checkbox for 'agreement_accepted'
                                // Forms\Components\Checkbox::make('agreement_accepted')
                                //     ->label('Agreement Accepted')
                                //     ->default(true), // Default value

                                // Checkbox for 'archived'
                                // Forms\Components\Checkbox::make('archived')
                                //     ->label('Archived')
                                //     ->default(false) // Default value
                                // ->hidden(true),
                            ]),

                        Fieldset::make('PROCEDURE DATA')
                            ->schema([
                                Forms\Components\Grid::make(3) // Split layout: Left (Repeater), Right (Tooth Chart)
                                    ->schema([

                                        Forms\Components\Placeholder::make('documentation')
                                            ->label('Chart')
                                            ->content(new HtmlString('<img src="/assets/img/tooth-chart.png"/>'))
                                            ->columnSpan(1), // Span only one column width

                                        Forms\Components\Grid::make(1)
                                            ->schema([
                                                Forms\Components\Repeater::make('items')
                                                    ->label('Procedures / Treatments')
                                                    ->relationship()
                                                    ->schema([
                                                        Forms\Components\Select::make('procedure_id')
                                                            ->label('Procedure')
                                                            ->helperText(fn($get) => Procedure::where('id', $get('procedure_id'))->value('short_description') ?? '')
                                                            ->options(fn() => Procedure::pluck('name', 'id'))
                                                            ->placeholder('Select')
                                                            ->required()
                                                            ->searchable()
                                                            ->reactive()
                                                            ->disabled($user->role != 'ADMIN')
                                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                                // Get procedure cost
                                                                $cost = Procedure::where('id', $state)->value('cost') ?? 0;

                                                                // Trigger amount field change
                                                                $set('amount', null); // Set to null first to force update
                                                                $set('amount', $cost); // Then set to actual cost
                                                                $set('total_amount', $cost); // Then set to actual cost

                                                                // Update total amount
                                                                self::updateTotalAmount($set, get: $get);
                                                            }),

                                                        Forms\Components\Select::make('tooth_number')
                                                            ->label('Tooth Number')
                                                            ->options(array_merge(range(1, 32), ['All', 'None']))
                                                            ->multiple()
                                                            ->required()
                                                            ->placeholder('Select')
                                                            ->reactive()
                                                            ->disabled($user->role != 'ADMIN'),

                                                        Forms\Components\TextInput::make('amount')
                                                            ->label('Amount')
                                                            ->numeric()
                                                            ->required()
                                                            ->prefix('₱')
                                                            ->reactive()
                                                            ->disabled($user->role != 'ADMIN')
                                                            ->afterStateUpdated(
                                                                fn($state, callable $set, callable $get) =>
                                                                self::updateTotalAmount($set, $get)
                                                            ),
                                                    ])
                                                    ->collapsible()
                                                    ->addActionLabel('Add Procedure')
                                                    ->defaultItems(0)
                                                    ->columns(3)
                                                    ->afterStateUpdated(fn($state, callable $set, callable $get) => self::updateTotalAmount($set, $get)),

                                                // Total Amount Field
                                                Forms\Components\TextInput::make('total_amount')
                                                    ->label('Total Amount')
                                                    ->disabled() // UI is disabled, so users can't edit
                                                    ->dehydrated(true) // Ensures the value is still saved
                                                    ->helperText('Price may vary')
                                                    ->reactive()
                                                    ->live()
                                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                        // Calculate total_amount by summing all 'amount' values in the repeater
                                                        $totalAmount = collect($get('items') ?? [])
                                                            ->sum(fn($item) => (float) ($item['amount'] ?? 0));

                                                        // Update total_amount
                                                        $set('total_amount', $totalAmount);
                                                    }),
                                            ])
                                            ->columnSpan(2),
                                    ]),
                            ])
                            ->hidden(
                                fn(string $operation) =>
                                Filament::auth()->user()->role == User::ROLE_PATIENT && in_array($operation, ['create', 'edit'])
                            ),

                        Forms\Components\Checkbox::make('agreement_accepted')
                            ->label(fn() => new HtmlString('I accept the <a href="/consent-agreement" target="_blank" style="color:red">Consent Agreement</a> and <a href="/terms-and-conditions" target="_blank" style="color:red">Terms and Conditions</a>'))
                            ->required()
                            ->disabled(fn($get) => $get('id') !== null) // Disable if editing
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function editForm(Form $form): Form
    {
        return static::form($form)->mutateFormDataUsing(function (array $data): array {
            $data['cancelled_reason_visible'] = $data['status'] === 'CANCELLED';
            return $data;
        });
    }

    /**
     * Function to update the total amount dynamically
     */
    private static function updateTotalAmount(callable $set, callable $get)
    {
        $totalAmount = collect($get('items') ?? [])->sum(fn($item) => (float) ($item['amount'] ?? 0));
        $set('total_amount', $totalAmount);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.fullname')
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->orWhereHas('patient', function (Builder $query) use ($search) {
                            $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                        });
                    })
                    ->sortable(query: function (Builder $query, string $direction) {
                        $query->orWhereHas('patient', function (Builder $query) use ($direction) {
                            $query->orderByRaw("CONCAT(first_name, ' ', last_name) $direction");
                        });
                    }),
                Tables\Columns\TextColumn::make('doctor.fullname')
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->orWhereHas('doctor', function (Builder $query) use ($search) {
                            $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                        });
                    })
                    ->sortable(query: function (Builder $query, string $direction) {
                        $query->orWhereHas('doctor', function (Builder $query) use ($direction) {
                            $query->orderByRaw("CONCAT(first_name, ' ', last_name) $direction");
                        });
                    }),
                Tables\Columns\TextColumn::make('procedures')
                    ->getStateUsing(function ($record) {
                        if (is_array($record->procedures)) {
                            // Get procedure names and join them with a comma
                            $procedureNames = Procedure::whereIn('id', $record->procedures)->pluck('name')->toArray();
                            $namesString = implode(', ', $procedureNames);

                            // Truncate to 20 characters
                            return strlen($namesString) > 15 ? substr($namesString, 0, 15) . '...' : $namesString;
                        }
                        return '';
                    })
                    ->label('Procedures')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label("Amount")
                    ->formatStateUsing(fn($state) => number_format($state, 2))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('time.time_start')
                    ->label('Time')
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('h:i A')),
                Tables\Columns\TextColumn::make('status')
                    ->visible(
                        true
                    )
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'PENDING' => 'gray',
                        'CANCELLED' => 'warning',
                        'CONFIRMED' => 'info',
                        'REJECTED' => 'danger',
                        'COMPLETED' => 'success',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // AppointmentStatusFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->tooltip('View Appointment'),
                Tables\Actions\EditAction::make()
                    ->label('')
                    ->icon('heroicon-o-pencil')
                    ->tooltip('Edit Appointment'),
                Tables\Actions\Action::make('download')
                    ->label('')
                    ->url(fn(Appointment $record) => route('appointments.download-pdf', $record))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-arrow-down-tray')
                    ->tooltip('Download PDF'),
                Tables\Actions\Action::make('archived')
                    ->label('') // Set label for the action button
                    ->icon('heroicon-o-archive-box') // Optional: icon for the action
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(function (Appointment $record) {
                        $record->update(['archived' => true]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Archive')
                    ->tooltip('Archived'),
            ])
            ->defaultSort('id', 'desc')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // ItemsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'view' => Pages\ViewAppointment::route('/{record}'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
    protected static function getAvailableTimes(array $bookedTimes): array
    {
        $times = [];
        $start = Carbon::createFromTime(8, 0);
        $end = Carbon::createFromTime(17, 0);

        while ($start->lessThanOrEqualTo($end)) {
            $formattedTime = $start->format('H:i:s');
            $displayTime = $start->format('g:i A');

            // Check if the time is booked for the selected date
            if (!in_array($formattedTime, $bookedTimes[request()->input('appointment_date')] ?? [])) {
                $times[$formattedTime] = $displayTime;
            }

            $start->addHour();
        }

        return $times;
    }

    private static function getRepeatClosedDates(array $repeatDays)
    {
        $repeatClosedDates = [];
        $today = Carbon::today();
        $period = CarbonPeriod::create($today, $today->copy()->addYears(5)); // Next 5 years

        foreach ($period as $date) {
            // Get the current day name, formatted as "Monday", "Tuesday", etc.
            $dayName = $date->format('l'); // This will return "Monday", "Tuesday", etc.

            // Check if the day is in the repeat days list (case-insensitive)
            if (in_array(strtolower($dayName), array_map('strtolower', $repeatDays))) {
                // If the day matches, add the formatted date to the array
                $repeatClosedDates[] = $date->format('Y-m-d'); // "2025-04-15", etc.
            }
        }

        // Return the array of repeat closed dates
        return $repeatClosedDates;
    }
}
