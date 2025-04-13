<?php

namespace App\Filament\Resources\ClosedDayResource\Pages;

use App\Filament\Resources\ClosedDayResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClosedDays extends ListRecords
{
    protected static string $resource = ClosedDayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
