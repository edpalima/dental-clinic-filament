<?php

namespace App\Filament\Resources\InactiveAnnouncementResource\Pages;

use App\Filament\Resources\InactiveAnnouncementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInactiveAnnouncement extends EditRecord
{
    protected static string $resource = InactiveAnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
