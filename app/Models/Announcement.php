<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'start_date',
        'end_date',
        'is_active',
        'archived'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeArchived($query)
    {
        return $query->where('archived', true);
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }

    protected static function booted()
    {
        static::addGlobalScope('userRoleFilter', function (Builder $builder) {
            $builder->where('archived', false);
        });
    }
}
