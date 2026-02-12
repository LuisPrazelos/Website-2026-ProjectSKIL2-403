<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    use HasFactory;

    // Omdat de primary key 'workshopId' heet in plaats van 'id'
    protected $primaryKey = 'workshopId';

    // English field names (translated from Dutch)
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
}
