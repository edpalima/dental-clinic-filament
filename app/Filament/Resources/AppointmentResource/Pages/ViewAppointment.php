<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAppointment extends ViewRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Calendar')
                ->url(route('filament.admin.pages.calendar')) // Redirect to the appointments index page
                ->icon('heroicon-o-calendar'), // Optional: Add an icon
                Actions\Action::make('back')
                    ->label('Table')
                    ->url(route('filament.admin.resources.appointments.index')) // Redirect to the appointments index page
                    ->icon('heroicon-o-list-bullet'), // Optional: Add an icon
            Actions\EditAction::make()
                ->color('danger'),
        ];
    }
}
