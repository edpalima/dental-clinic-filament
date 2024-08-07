<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Models\Appointment;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ListAppointments extends ListRecords
{
    // public Collection $orderByStatuses;

    // public function __construct()
    // {
    //     $this->orderByStatuses = Appointment::select('status', DB::raw('count(*) as total'))
    //         ->groupBy('status')
    //         ->pluck('total', 'status');
    // }

    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = ['all' => Tab::make('All')->badge($this->getModel()::count())];

        $appointments = Appointment::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderBy('status', 'asc')
            ->get();

        foreach ($appointments as $appointment) {
            $status = $appointment->status;
            $slug = $status;

            $tabs[$slug] = Tab::make($status)
                ->badge($appointment->total)
                ->modifyQueryUsing(function ($query) use ($appointment) {
                    return $query->where('status', $appointment->status);
                });
        }

        return $tabs;
    }
}
