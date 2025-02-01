<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class AppointmentsChart extends ChartWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Appointments Created by Months';

    protected function getData(): array
    {
        $data = $this->getAppointmentsPerMonth();

        return [
            'datasets' => [
                [
                    'label' => 'Appointments created',
                    'data' => $data['appointmentsPerMonth']
                ]
            ],
            'labels' => $data['months']
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getAppointmentsPerMonth(): array
    {
        $now = Carbon::now();

        $appointmentsPerMonth = [];

        $months = collect(range(1, 12))->map(function ($month) use ($now, &$appointmentsPerMonth) {
            $count = Appointment::whereMonth('date', Carbon::parse($now->month($month)
                ->format('Y-m')))->count();

            $appointmentsPerMonth[] = $count;

            return $now->month($month)->format('M');
        })->toArray();

        return [
            'appointmentsPerMonth' => $appointmentsPerMonth,
            'months' => $months
        ];
    }

    public static function canView(): bool
    {
        if (Auth::user()->isAdmin() || Auth::user()->isDoctor()) {
            return true;
        }

        return false;
    }
}
