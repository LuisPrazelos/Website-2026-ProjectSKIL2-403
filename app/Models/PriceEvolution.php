<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceEvolution extends Model
{
    use HasFactory;

    protected $primaryKey = 'priceEvolutionId';

    protected $fillable = [
        'ingredientId',
        'price',
        'amount',
        'date',
        'source',
    ];

    protected $casts = [
        'date' => 'date',
        'price' => 'float',
        'amount' => 'float',
    ];
}
