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
        $tabs = [
            'all' => Tab::make('All')
                ->badge($this->getModel()::count())
                ->modifyQueryUsing(function ($query) {
                    // No need to modify the query, it will show all appointments
                    return $query;
                }),
        ];
    
        $statuses = ['PENDING', 'CONFIRMED', 'CANCELLED', 'REJECTED', 'COMPLETED'];
        $appointmentCounts = Appointment::select('status', DB::raw('count(*) as total'))
            ->whereIn('status', $statuses)
            ->groupBy('status')
            ->pluck('total', 'status');
    
        foreach ($statuses as $status) {
            $tabs[strtolower($status)] = Tab::make($status)
                ->badge($appointmentCounts->get($status, 0))
                ->modifyQueryUsing(function ($query) use ($status) {
                    return $query->where('status', $status);
                });
        }
    
        return $tabs;
    }
}
