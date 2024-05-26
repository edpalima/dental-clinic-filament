<?php

namespace App\Filament\Pages;

use App\Models\Appointment;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.reports';

    protected static ?string $navigationGroup = 'Appointments';
    protected static ?int $navigationSort = 2;

    public $appointments;
    public function mount()
    {
        // Retrieve appointment data for reports
        $this->appointments = Appointment::selectRaw('date, COUNT(*) as count')
            ->groupBy('date')
            ->get();
    }

    public static function canAccess(): bool
    {
        return Auth::user()->isAdmin();
    }
}
