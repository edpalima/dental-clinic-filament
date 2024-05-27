<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PatientsChart extends ChartWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Total Appointments By Status';

    protected function getData(): array
    {
        $statuses = ['PENDING', 'CONFIRMED', 'CANCELLED', 'REJECTED'];

        $data = Appointment::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    
        // Ensure all statuses are included in the data with a count of 0 if not present
        $data = array_merge(array_fill_keys($statuses, 0), $data);
    
        return [
            'datasets' => [
                [
                    'label' => 'Appointments',
                    'data' => array_values($data)
                ]
            ],
            'labels' => array_keys($data)
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public static function canView(): bool
    {
        if (Auth::user()->isAdmin()) {
            return true;
        }

        return false;
    }
}
