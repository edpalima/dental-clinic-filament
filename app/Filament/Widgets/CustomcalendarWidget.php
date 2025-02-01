<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class CustomcalendarWidget extends Widget
{
    protected static string $view = 'filament.widgets.customcalendar-widget';
    protected static bool $isLazy = false;
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        if (Auth::user()->isPatient()) {
            return true;
        }

        return false;
    }
}
