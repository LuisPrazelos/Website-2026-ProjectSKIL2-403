<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    use HasFactory;

    protected $primaryKey = 'workshopId';

    protected $fillable = [
        'name',
        'date',
        'price_adults',
        'price_children',
        'description',
        'location',
        'duration_minutes',
        'max_participants',
    ];

    public function registrations()
    {
        return $this->hasMany(WorkshopUser::class, 'workshop_id', 'workshopId');
    }

    public function getParticipantCountAttribute()
    {
        return $this->registrations()->sum('total_adults') + $this->registrations()->sum('total_children');
    }
}
