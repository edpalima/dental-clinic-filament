<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{
    public function downloadPDF(Appointment $appointment)
    {
        $pdf = Pdf::loadView('pdf.appointment', compact('appointment'));

        return $pdf->download('appointment_' . $appointment->id . '.pdf');
    }
}
