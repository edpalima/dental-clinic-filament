<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\Appointment;
use App\Models\Procedure;
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
        return Appointment::query()->withoutGlobalScope('userRoleFilter')
            ->where('archived', true)
            ->orderBy('id', 'desc');
    }

    public static function getNavigationBadge(): ?string
    {
        return Appointment::query()->withoutGlobalScope('userRoleFilter')
            ->where('archived', true)->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'gray';
    }

    protected function getTableColumns(): array
    {
        return [
            // Tables\Columns\TextColumn::make('id')
            //     ->searchable()
            //     ->sortable(),
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
            Tables\Actions\Action::make('archived')
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
