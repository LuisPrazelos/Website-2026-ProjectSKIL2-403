<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    use HasFactory;

    // Omdat de primary key 'workshopId' heet in plaats van 'id'
    protected $primaryKey = 'workshopId';

    protected $fillable = [
        'naam',
        'datum',
        'prijsVolwassenen',
        'prijsKinderen',
        'beschrijving',
        'locatie',
        'tijdsduur',
        'maxDeelnemers',
    ];
}
