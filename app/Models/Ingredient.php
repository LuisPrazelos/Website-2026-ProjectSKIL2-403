<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'measurement_unit_id',
        'minimumAmount',
    ];

    public function measurementUnit()
    {
        return $this->belongsTo(MeasurementUnit::class);
    }

    public function ingredientAllergies()
    {
        return $this->hasMany(IngredientAllergy::class, 'ingredient_id');
    }

    public function priceEvolutions()
    {
        return $this->hasMany(PriceEvolution::class, 'ingredientId', 'id');
    }

    /**
     * Haal de meest recente prijsontwikkeling op voor dit ingrediënt.
     */
    public function latestPriceEvolution()
    {
        return $this->hasOne(PriceEvolution::class, 'ingredientId', 'id')->latestOfMany('date');
    }
}
