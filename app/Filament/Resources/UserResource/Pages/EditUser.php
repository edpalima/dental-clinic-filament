<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function mutateFormDataBeforeSave(array $data): array
    {
        // Check if a new password is provided
        if (!empty($data['new_password'])) {
            $data['password'] = $data['new_password']; // Use the new password
        }

        unset($data['new_password']); // Remove new_password to avoid errors
        unset($data['new_password_confirmation']); // Remove confirmation field

        return $data;
    }
}
