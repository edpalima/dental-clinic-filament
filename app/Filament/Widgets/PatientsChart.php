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
        $data = Appointment::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();


        return [
            'datasets' => [
                [
                    'label' => 'Appointments',
                    'data' => array_values($data)
                ]
            ],
            'labels' => ['PENDING', 'CONFIRMED', 'CANCELLED', 'REJECTED']
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
