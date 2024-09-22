<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('Appointments'),
                NavigationGroup::make()
                    ->label('People'),
                NavigationGroup::make()
                    ->label('Announcements'),
                NavigationGroup::make()
                    ->label('Settings'),
                NavigationGroup::make()
                    ->label('Reports'),
                // ->collapsed(),
                NavigationGroup::make()
                    ->label('Accounts'),
            ]);
        });
    }
}
