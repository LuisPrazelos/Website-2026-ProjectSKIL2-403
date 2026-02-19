<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrijsEvolutie extends Model
{
    use HasFactory;

    // Omdat de primary key 'prijsEvolutieId' heet
    protected $primaryKey = 'prijsEvolutieId';

    protected $fillable = [
        'ingredientId',
        'prijs',
        'hoeveelheid',
        'datum',
        'bron',
    ];

    protected $casts = [
        'datum' => 'date',
        'prijs' => 'float',
        'hoeveelheid' => 'float',
    ];
}
