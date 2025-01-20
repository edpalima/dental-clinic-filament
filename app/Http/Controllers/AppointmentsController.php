<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentsController extends Controller
{
    public function fetch(Request $request)
    {
        $fetchInfo = $request->all();

        $appointments = Appointment::where('date', '>=', $fetchInfo['start'])
            ->where('date', '<=', $fetchInfo['end'])
            ->get()
            ->map(function (Appointment $appointment) {
                return [
                    'id'     => $appointment->id,
                    'title'  => $appointment->patient->fullName,
                    'start'  => $appointment->date,
                    'status' => $appointment->status,
                    'end'    => $appointment->date, // Update if appointments have an end time
                ];
            })
            ->toArray();

        return response()->json($appointments);
    }
}
