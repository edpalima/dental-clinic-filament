<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Calendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static string $view = 'filament.pages.calendar';
    
    protected static ?string $navigationGroup = 'Appointments';
    
    protected static ?string $navigationLabel = 'Calendar';


    public static function canAccess(): bool
    {
        return Auth::user()->role === User::ROLE_ADMIN || Auth::user()->role === User::ROLE_DOCTOR;
    }

    public static function canView(): bool
    {
        return Auth::user()->role === User::ROLE_ADMIN || Auth::user()->role === User::ROLE_DOCTOR;
    }
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->role === User::ROLE_ADMIN || Auth::user()->role === User::ROLE_DOCTOR;
    }
}
