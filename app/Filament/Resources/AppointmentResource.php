<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\Pages\EditAppointment;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Filament\Resources\AppointmentResource\RelationManagers\ItemsRelationManager;
// use App\Filament\Resources\Filters\AppointmentStatusFilter;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Procedure;
use App\Models\Time;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Barryvdh\DomPDF\Facade\Pdf;
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

        // Get the selected date from the query parameter
        $selectedDate = request()->query('date'); // Fetch 'selected_date' from URL query parameters

        return $form
            ->schema([
                Section::make()
                    ->schema([
                        // Details Section
                        Fieldset::make('SCHEDULE')
                            ->schema([
                                Forms\Components\DatePicker::make('date')
                                    ->required()
                                    ->live()
                                    ->minDate(today()->addDay()) // Ensure booking starts from tomorrow
                                    ->default($selectedDate) // Set the default date if available in the query
                                    ->afterStateUpdated(fn($state, callable $get, callable $set) => $set('time_id', null))
                                    ->disabled(fn($get) => $get('id') !== null), // Disable if editing

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

                                        // Add labels for booked time slots
                                        if ($get('id') == null) {
                                            foreach ($allTimeSlots as $id => $name) {
                                                if (in_array($id, $bookedTimeIds)) {
                                                    $allTimeSlots[$id] = $name . ' (Not Available)'; // Add "Not Available" label
                                                }
                                            }
                                        }

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
                                    ->disabled(fn($get) => $get('id') !== null) // Disable if editing
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

                                // Procedure Multiple Selection
                                Forms\Components\Select::make('procedures')
                                    ->label('Procedure(s)')
                                    ->multiple() // Allow multiple selection
                                    ->reactive() // Make it reactive
                                    ->live() // Make it live-updating
                                    ->options(function (callable $get) {
                                        // Show all procedures in a list, ordered by name
                                        return Procedure::orderBy('name')->pluck('name', 'id');
                                    })
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        // Calculate the total amount based on the selected procedures
                                        if ($state) {
                                            // Get the price of the selected procedures
                                            $totalAmount = Procedure::whereIn('id', $state)->sum('cost');
                                            // Set the total amount in the 'amount' field
                                            $set('amount', $totalAmount);
                                        } else {
                                            // If no procedures are selected, set amount to 0
                                            $set('amount', 0);
                                        }
                                    })
                                    ->required(),

                                // Amount with notice message
                                Forms\Components\TextInput::make('amount')
                                    ->disabled(true)
                                    ->dehydrated(true)
                                    ->helperText('Price may vary')
                                    ->live(),

                                $user->role == 'ADMIN'
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
                                    ->options([
                                        'PENDING' => 'Pending',
                                        'CANCELLED' => 'Cancelled',
                                    ])
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
                                    ->hidden(fn($get) => $get('status') !== 'CONFIRMED')
                                    ->columnSpanFull(),

                                Forms\Components\Checkbox::make('agreement_accepted')
                                    ->label('I agree to the terms and conditions')
                                    ->required()
                                    ->disabled(fn($get) => $get('id') !== null) // Disable if editing
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
                Tables\Columns\TextColumn::make('amount')
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
            ItemsRelationManager::class
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
}
