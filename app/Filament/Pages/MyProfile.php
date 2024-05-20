<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class MyProfile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static string $view = 'filament.pages.my-profile';

    protected static ?string $navigationGroup = 'Account';
}
