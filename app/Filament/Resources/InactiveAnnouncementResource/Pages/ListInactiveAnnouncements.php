<?php

namespace App\Filament\Resources\InactiveAnnouncementResource\Pages;

use App\Filament\Resources\InactiveAnnouncementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInactiveAnnouncements extends ListRecords
{
    protected static string $resource = InactiveAnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Archived Announcements'; // Your custom title
    }
}
