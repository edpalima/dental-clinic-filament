<?php

namespace App\Filament\Resources\TimeResource\Pages;

use App\Filament\Resources\TimeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTimes extends ListRecords
{
    protected static string $resource = TimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Available Time';
    }
}
