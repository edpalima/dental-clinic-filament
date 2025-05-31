<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'cost',
        'short_description',
        'cant_combine',
        'duration'
    ];

    protected $casts = [
        'cant_combine' => 'array',
    ];
}
