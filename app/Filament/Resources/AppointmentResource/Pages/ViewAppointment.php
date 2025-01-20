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
                ->label('Back')
                ->url(route('filament.admin.resources.appointments.index')) // Redirect to the appointments index page
                ->icon('heroicon-o-arrow-left'), // Optional: Add an icon
            Actions\EditAction::make(),
        ];
    }
}
