<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = '15s';
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Appointments', Appointment::count())
                ->description('Total created appointments')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->url(route('filament.admin.resources.appointments.index'))
            // ->color('success')
            // ->chart([7, 3, 4, 5, 6, 3, 5, 3])
            ,

            Stat::make('Total Patients', Patient::count())
                ->description('Total registered Patients')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->url(route('filament.admin.resources.patients.index'))
            // ->color('danger')
            // ->chart([7, 3, 4, 5, 6, 3, 5, 3])
            ,

            Stat::make('Total Users', User::count())
                ->description('Total created users')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->url(route('filament.admin.resources.users.index'))
            // ->color('danger')
            // ->chart([7, 3, 4, 5, 6, 3, 5, 3])
        ];
    }

    public static function canView(): bool
    {
        if (Auth::user()->isAdmin() || Auth::user()->isStaff()) {
            return true;
        }

        return false;
    }
}
