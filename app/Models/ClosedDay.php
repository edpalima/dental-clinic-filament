<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClosedDay extends Model
{
    use HasFactory;

    protected $table = 'closed_days';

    protected $fillable = [
        'date',
        'repeat_day',
        'is_active',
        'reason',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
