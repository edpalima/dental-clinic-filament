<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AppointmentResource;
use App\Models\Procedure;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class LatestAppointments extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Appointments';

    public function table(Table $table): Table
    {
        $query = AppointmentResource::getEloquentQuery();

        if (Auth::user()->isDoctor() && Auth::user()->doctor->id) {
            $query->where('doctor_id', Auth::user()->doctor->id);
        }

        return $table
            ->query($query)
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns(array_filter([
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
                            $procedureNames = Procedure::whereIn('id', $record->procedures)->pluck('name')->toArray();
                            $namesString = implode(', ', $procedureNames);
                            return strlen($namesString) > 15 ? substr($namesString, 0, 15) . '...' : $namesString;
                        }
                        return '';
                    })
                    ->label('Procedures')
                    ->searchable()
                    ->sortable(),

                // ✅ Conditionally include Amount column
                !Auth::user()->isDoctor() ? Tables\Columns\TextColumn::make('total_amount')
                    ->label("Amount")
                    ->formatStateUsing(fn($state) => number_format($state, 2))
                    ->searchable()
                    ->sortable() : null,

                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('time.name'),

                Tables\Columns\TextColumn::make('status')
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
            ]))
            ->recordUrl(fn($record) => AppointmentResource::getUrl('view', ['record' => $record]));
    }

    public static function canView(): bool
    {
        return true;
    }
}
