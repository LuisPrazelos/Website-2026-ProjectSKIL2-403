<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceEvolution extends Model
{
    use HasFactory;

    protected $table = 'price_evolutions';
    protected $primaryKey = 'priceEvolutionId';
    public $timestamps = false;

    protected $fillable = [
        'priceEvolutionId',
        'ingredientId',
        'price',
        'amount',
        'date',
        'source',
    ];

    public function ingredient()
    {
        // Referencing the model by string to avoid direct dependency if the file is managed elsewhere
        return $this->belongsTo('App\Models\Ingredient', 'ingredientId');
    }
}
