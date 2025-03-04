<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Models\Appointment;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;

class EditAppointment extends EditRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        $appointment = $this->record;

        $this->sendStatusEmail($appointment);
    }
    protected static function sendStatusEmail(Appointment $appointment): void
    {
        try {
            $customerEmail = $appointment->patient->email;
            if (in_array($appointment->status, ['CONFIRMED', 'CANCELLED'])) {
                Mail::send('emails.status-send', ['appointment' => $appointment], function ($message) use ($customerEmail) {
                    $message->to($customerEmail);
                    $message->subject('Your Rental was Updated');
                });
            }
        } catch (\Exception $e) {
            // Nothing to do
        }
    }
}
