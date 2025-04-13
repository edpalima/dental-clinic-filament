<?php

namespace App\Filament\Resources\ClosedDayResource\Pages;

use App\Filament\Resources\ClosedDayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClosedDay extends EditRecord
{
    protected static string $resource = ClosedDayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
