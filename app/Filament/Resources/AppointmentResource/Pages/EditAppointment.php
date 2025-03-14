<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Procedure;
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
        // try {
        $customerEmail = $appointment->patient->email;
        $procedureIds = $appointment->procedures ?? [];

        $procedures = Procedure::whereIn('id', $procedureIds)->pluck('name')->implode(', ');

        $data = [
            'date' => \Carbon\Carbon::createFromFormat('Y-m-d', $appointment->date)->format('F j, Y'),
            'dayOfWeek' => \Carbon\Carbon::createFromFormat('Y-m-d', $appointment->date)->format('l'),
            'time' => $appointment->time->name,
            'procedures' => $procedures ?: ' ',
            'status' => $appointment->status,
        ];

        if (in_array($appointment->status, ['CONFIRMED', 'CANCELLED'])) {
            // Mail::send('emails.appointment-notification', ['data' => $data], function ($message) use ($customerEmail) {
            //     $message->to($customerEmail);
            //     $message->subject('Your Appointment Confirmation');
            // });

            Mail::send('emails.status-send', ['data' => $data], function ($message) use ($customerEmail) {
                $message->to($customerEmail);
                $message->subject('Your Appointment was Updated');
            });
        }
        // } catch (\Exception $e) {
        //     // Handle email errors
        //     // \Log::error('Failed to send appointment email: ' . $e->getMessage());
        // }
    }
}
