<?php

namespace App\Filament\Pages;

use App\Models\Appointment;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.reports';

    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigation = 'Appointments';
    protected static ?int $navigationSort = 2;

    public $appointments;
    public function mount()
    {
        // Retrieve appointment data for reports
        $this->appointments = Appointment::selectRaw('date, COUNT(*) as count')
            ->groupBy('date')
            ->get();

            $this->appointments->transform(function ($item) {
                $item->date = Carbon::parse($item->date)->format('F j, Y');
                return $item;
            });
    }

    public static function canAccess(): bool
    {
        return Auth::user()->isAdmin();
    }
}
