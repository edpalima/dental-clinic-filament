<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function generateAppointmentReportPdf()
    {
        $appointments = Appointment::selectRaw('date, COUNT(*) as count')
            ->groupBy('date')
            ->get();

        // Format the dates
        $appointments->transform(function ($item) {
            $item->date = Carbon::parse($item->date)->format('F j, Y');
            return $item;
        });

        $pdf = Pdf::loadView('reports.appointment-report', ['appointments' => $appointments]);
        return $pdf->download('appointment_report.pdf');
    }
}
