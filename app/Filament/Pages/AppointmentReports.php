<?php

namespace App\Filament\Pages;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AppointmentReports extends Page
{
    protected static ?string $navigationGroup = 'Reports ';
    protected static ?string $navigationLabel = 'Appointments Reports';
    protected static string $view = 'filament.resources.appointment-resource.pages.appointment-reports';
    protected static ?string $navigationIcon = 'heroicon-o-document';

    public ?string $startDate = null;
    public ?string $endDate = null;
    public ?string $status = 'ALL';
    public ?int $doctorId = null;
    public ?int $patientId = null;
    public $appointments;
    public string $companyName = 'Almoro Santiago Dental Clinic';
    public string $companyAddress = 'Canlalay Biñan City of Laguna.';
    public string $companyContact = '0998993833 or email us at almorosantiago.dentalclinic@gmail.com';

    public function mount(): void
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
        $this->appointments = collect();
        $this->generateReport();
    }

    public function generateReport(): void
    {
        $query = Appointment::with(['patient', 'doctor']);

        // Filter by date range
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('date', [$this->startDate, $this->endDate]);
        } elseif ($this->startDate) {
            $query->where('date', '>=', $this->startDate);
        } elseif ($this->endDate) {
            $query->where('date', '<=', $this->endDate);
        }

        // Other filters
        $query->when($this->status && $this->status !== 'ALL', fn($q) => $q->where('status', $this->status));
        $query->when($this->doctorId, fn($q) => $q->where('doctor_id', $this->doctorId));
        $query->when($this->patientId, fn($q) => $q->where('patient_id', $this->patientId));

        // Retrieve results
        $this->appointments = $query->get();
    }

    public function printPdf(): StreamedResponse
    {
        $preparedBy = auth()->user()->name;

        $pdf = Pdf::loadView('pdf.appointment-report', [
            'appointments' => $this->appointments,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'patient' => $this->patientId ? Patient::find($this->patientId) : null,
            'preparedBy' => $preparedBy,
            'companyName' => $this->companyName,
            'companyAddress' => $this->companyAddress,
            'companyContact' => $this->companyContact,
        ])->output();

        return response()->streamDownload(fn() => print($pdf), 'appointment-report.pdf');
    }

    public static function canAccess(): bool
    {
        return Auth::user()->isAdmin();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->isAdmin();
    }
}
