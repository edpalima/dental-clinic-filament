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
            ->with('time') // Include related Time model
            ->join('times', 'appointments.time_id', '=', 'times.id') // Join with times table
            ->orderBy('appointments.date') // Sort by date
            ->orderBy('times.time_start') // Sort by time_start in times table
            ->get(['appointments.*', 'times.time_start']) // Select needed columns
            ->map(function (Appointment $appointment) {
                return [
                    'id'     => $appointment->id,
                    'title'  => $appointment->patient->fullName,
                    'time'   => \Carbon\Carbon::parse($appointment->time->time_start)->format('g:i A'),
                    'time_id'   => $appointment->time->time_id,
                    'start'  => $appointment->date,
                    'status' => $appointment->status,
                    'end'    => $appointment->date, // Update if appointments have an end time
                ];
            })
            ->toArray();

        return response()->json($appointments);
    }
}
