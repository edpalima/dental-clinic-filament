<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;

class ArchivedAppointments extends Page implements Tables\Contracts\HasTable
{
    use InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static string $view = 'filament.pages.archived-appointments';
    protected static ?string $navigationGroup = 'Appointments';
    protected static ?string $navigationLabel = 'Archived';
    protected static ?int $navigationSort = 8;

    public function getTableQuery(): Builder
    {
        return Appointment::query()->withoutGlobalScope('userRoleFilter')->orderBy('id', 'desc');
    }

    public static function getNavigationBadge(): ?string
    {
        return Appointment::query()->withoutGlobalScope('userRoleFilter')->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'gray';
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
            Tables\Columns\TextColumn::make('procedure.name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('procedure.cost')
                ->label("Cost")
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
            // Add other columns as needed
        ];
    }
protected function getTableActions(): array
{
    return [
        Tables\Actions\Action::make('Unarchived')
            ->label('') // Set label for the action button
            ->icon('heroicon-o-archive-box-x-mark') // Optional: icon for the action
            ->requiresConfirmation()
            ->color('success')
            ->action(function (Appointment $record) {
                $record->update(['archived' => false]);
            })
            ->requiresConfirmation()
            ->modalHeading('Confirm Unarchive')
            ->tooltip('Unarchive'),
    ];
}
}
