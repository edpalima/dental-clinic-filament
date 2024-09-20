<?php

namespace App\Filament\Resources\InactiveAnnouncementResource\Pages;

use App\Filament\Resources\InactiveAnnouncementResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInactiveAnnouncement extends CreateRecord
{
    protected static string $resource = InactiveAnnouncementResource::class;
}
