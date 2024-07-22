<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\Pages\EditAppointment;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Procedure;
use App\Models\Time;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
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
use Illuminate\Support\Facades\Blade;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Appointments';

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

        return $form
            ->schema([
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
            ]);
    }

    public static function editForm(Form $form): Form
    {
        return static::form($form)->mutateFormDataUsing(function (array $data): array {
            $data['cancelled_reason_visible'] = $data['status'] === 'CANCELLED';
            return $data;
        });
    }

    public static function getEloquentQuery(): Builder
    {
        $query = static::getModel()::query();

        $user = Auth::user();

        if ($user->role === 'PATIENT') {
            $patient = $user->patient;

            if ($patient) {
                $query->where('patient_id', $patient->id);
            } else {
                // Handle case where the user is a PATIENT but has no associated patient record
                $query->whereNull('patient_id');
            }
        }

        return $query;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('patient.fullname')
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->orWhereHas('patient', function (Builder $query) use ($search) {
                            $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                        });
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('doctor.fullname')
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->orWhereHas('doctor', function (Builder $query) use ($search) {
                            $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                        });
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('procedure.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('procedure.cost')
                    ->label("Cost")
                    ->searchable()
                    ->sortable()
                    ->prefix('₱'),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('time.name'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'PENDING' => 'gray',
                        'CANCELLED' => 'warning',
                        'CONFIRMED' => 'success',
                        'REJECTED' => 'danger',
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                // Tables\Actions\Action::make('pdf')
                //     ->label('PDF')
                //     ->color('success')
                //     ->icon('heroicon-s-arrow-down-tray')
                //     ->action(function (Model $record) {
                //         return response()->streamDownload(function () use ($record) {
                //             echo Pdf::loadHtml(
                //                 Blade::render('pdf', ['record' => $record])
                //             )->stream();
                //         }, $record->number . '.pdf');
                //     }),
                Tables\Actions\Action::make('download')
                    ->label('Invoice')
                    ->url(fn (Appointment $record) => route('appointments.download-pdf', $record))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-arrow-down-tray'),
            ])
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
            //
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
