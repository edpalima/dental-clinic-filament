<?php

namespace App\Filament\Pages;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AppointmentReports extends Page
{
    
    protected static ?string $navigationGroup = 'Reports';
    protected static string $view = 'filament.resources.appointment-resource.pages.appointment-reports';
    protected static ?string $navigationIcon = 'heroicon-o-document';

    public ?string $startDate = null;
    public ?string $endDate = null;
    public ?string $status = 'ALL';
    public ?int $doctorId = null;
    public ?int $patientId = null;
    public $appointments;

    public int $confirmedCount = 0;
    public int $rejectedCount = 0;
    public int $completedCount = 0;
    public int $pendingCount = 0;
    public int $cancelledCount = 0;
    public int $totalCount = 0;

    public array $patients = [];

    public string $companyName = 'Almoro Santiago Dental Clinic';
    public string $companyAddress = 'Canlalay Biñan City of Laguna.';
    public string $companyContact = '0998993833 or email us at almorosantiago.dentalclinic@gmail.com';

    public function mount(): void
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
        $this->patients = $this->getPatients();
        $this->appointments = collect();
        $this->generateReport();
    }

    public function generateReport(): void
    {
        $query = Appointment::query();

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('date', [$this->startDate, $this->endDate]);
        }

        if ($this->status && $this->status !== 'ALL') {
            $query->where('status', $this->status);
        }

        if ($this->doctorId) {
            $query->where('doctor_id', $this->doctorId);
        }

        if ($this->patientId) {
            $query->where('patient_id', $this->patientId);
        }

        $this->appointments = $query->get();

        // Count appointments by status
        $this->confirmedCount = (clone $query)->where('status', 'CONFIRMED')->count();
        $this->pendingCount = (clone $query)->where('status', 'PENDING')->count();
        $this->cancelledCount = (clone $query)->where('status', 'CANCELLED')->count();
        $this->rejectedCount = (clone $query)->where('status', 'REJECTED')->count();
        $this->completedCount = (clone $query)->where('status', 'COMPLETED')->count();
        $this->totalCount = (clone $query)->count();
    }

    public function printPdf(): StreamedResponse
    {
        $preparedBy = auth()->user()->name;

        $pdf = Pdf::loadHTML(view('pdf.appointment-report', [
            'appointments' => $this->appointments,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'patient' => $this->patientId ? Patient::find($this->patientId) : null,
            'confirmedCount' => $this->confirmedCount,
            'pendingCount' => $this->pendingCount,
            'cancelledCount' => $this->cancelledCount,
            'rejectedCount' => $this->rejectedCount,
            'completedCount' => $this->completedCount,
            'totalCount' => $this->totalCount,
            'preparedBy' => $preparedBy,
            'companyName' => $this->companyName,
            'companyAddress' => $this->companyAddress,
            'companyContact' => $this->companyContact,
        ])->render());

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'appointment-report.pdf'
        );
    }

    public function getDoctors(): array
    {
        return Doctor::pluck('name', 'id')->toArray();
    }

    public function getPatients(): array
    {
        return Patient::selectRaw("id, CONCAT(first_name, ' ', last_name) as name")
            ->pluck('name', 'id')
            ->toArray();
    }
}
