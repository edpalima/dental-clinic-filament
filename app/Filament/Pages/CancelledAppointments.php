<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;

class CancelledAppointments extends Page implements Tables\Contracts\HasTable
{
    use InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-x-circle';
    protected static string $view = 'filament.pages.cancelled-appointments';
    protected static ?string $navigationGroup = 'Appointments';
    protected static ?string $navigationLabel = 'Cancelled';
    protected static ?int $navigationSort = 4;

    public function getTableQuery(): Builder
    {
        return Appointment::query()->cancelled();
    }

    public static function getNavigationBadge(): ?string
    {
        return Appointment::query()->cancelled()->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    protected function getTableColumns(): array
    {
        return [
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
                ->sortable()
                ->searchable()
                ->badge()
                ->color(fn(string $state): string => match ($state) {
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
            // Add other columns as needed
        ];
    }
}
