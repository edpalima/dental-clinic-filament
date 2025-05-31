<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Time extends Model
{
    protected $fillable = [
        'name',
        'time_start',
        'time_end',
        'sort',
        'is_active',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function isAvailableOnDate($date)
    {
        $slotStart = $this->time_start;
        $slotEnd = $this->time_end;

        $hasMatching = Appointment::withoutGlobalScope('userRoleFilter')
            ->whereDate('date', $date)
            ->whereHas('time', function ($query) use ($slotStart, $slotEnd) {
                $query->whereRaw('TIME(?) >= time_start', [$slotStart]);
            })
            ->whereRaw('TIME(?) <= time_end', [$slotEnd])
            ->exists();

        return !$hasMatching;
    }
}
