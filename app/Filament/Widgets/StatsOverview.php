<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Patient;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

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
                // ->color('success')
                // ->chart([7, 3, 4, 5, 6, 3, 5, 3])
                ,

            Stat::make('Total Patients', Patient::count())
                ->description('Total registered Patients')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                // ->color('danger')
                // ->chart([7, 3, 4, 5, 6, 3, 5, 3])
                ,

            Stat::make('Total Users', Patient::count())
                ->description('Total created users')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                // ->color('danger')
                // ->chart([7, 3, 4, 5, 6, 3, 5, 3])

        ];
    }
}
