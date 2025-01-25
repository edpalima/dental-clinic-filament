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
            ->when($request->has('status') && $request->status, function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->get()
            ->map(function (Appointment $appointment) {
                return [
                    'id'     => $appointment->id,
                    'title'  => $appointment->patient->fullName,
                    'time'   => \Carbon\Carbon::parse($appointment->time->time_start)->format('g:i A'),
                    'start'  => $appointment->date,
                    'status' => $appointment->status,
                    'time_id' => $appointment->time->id,
                    'end'    => $appointment->date, // Update if appointments have an end time
                ];
            })
            ->sortBy('time_id') // Sort appointments by 'time'
            ->values() // Reset the array keys
            ->toArray();

        return response()->json($appointments);
    }
}
