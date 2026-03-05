<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    // Reverted primary key to default 'id'
    // protected $primaryKey = 'ingredientId';

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

    /**
     * Define the relationship with PriceEvolution.
     * The foreign key on the price_evolutions table is 'ingredient_id'.
     * The local key on the ingredients table is 'id'.
     */
    public function priceEvolutions()
    {
        return $this->hasMany(PriceEvolution::class, 'ingredient_id', 'id');
    }
}
