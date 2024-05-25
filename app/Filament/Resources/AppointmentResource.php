<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

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
                            Forms\Components\DatePicker::make('date')
                                ->required()
                                ->minDate(now()->addDay()) // Ensure booking starts from tomorrow
                                ->disabledDates($bookedDates), // Disable already booked dates
                            Forms\Components\Select::make('time')
                                ->options(self::getAvailableTimes($bookedTimes))
                                ->required(),
                        ]),
                    Wizard\Step::make('Assign People')
                        ->schema([
                            Forms\Components\Select::make('patient_id')
                                ->relationship(name: 'patient', titleAttribute: 'first_name')
                                ->getOptionLabelFromRecordUsing(fn (Patient $record) => "{$record->first_name} {$record->last_name}")
                                ->required()
                                ->visible(fn () => $user->role === 'ADMIN'),

                            Forms\Components\Hidden::make('patient_id')
                                ->default($user->role === 'PATIENT' ? $user->patient->id : null)
                                ->visible(fn () => $user->role === 'PATIENT'),

                            Forms\Components\Select::make('doctor_id')
                                ->relationship(name: 'doctor', titleAttribute: 'first_name')
                                ->getOptionLabelFromRecordUsing(fn (Doctor $record) => "{$record->first_name} {$record->last_name}")
                                ->required(),
                            Forms\Components\Select::make('procedure_id')
                                ->relationship(name: 'procedure', titleAttribute: 'name'),
                        ]),
                    Wizard\Step::make('Payments')
                        ->schema([
                            Forms\Components\Select::make('payment_options')
                                ->options([
                                    'otc' => 'Over The Counter',
                                    'gcash' => 'GCash',
                                ])
                                ->label('Payment Options')
                                ->required(),
                            Forms\Components\TextInput::make('amount')
                                ->prefix('₱'),
                            Forms\Components\TextInput::make('account_number')
                                ->numeric(),
                            Forms\Components\TextInput::make('reference_number')
                                ->numeric(),
                            Forms\Components\Textarea::make('notes')
                                ->columnSpanFull(),
                        ]),
                ]),


            ]);
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
                Tables\Columns\TextColumn::make('patient.fullname')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('doctor.fullname')
                    ->searchable()
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
                Tables\Columns\TextColumn::make('time'),
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
