<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $primaryKey = 'ingredientId';

    protected $fillable = [
        'ingredientName',
        'standardUnitId',
        'minimumAmount',
    ];

    public function standardUnit()
    {
        return $this->belongsTo(Meeteenheid::class, 'standardUnitId');
    }

    public function ingredientAllergies()
    {
        return $this->hasMany(IngredientAllergie::class, 'ingredientId');
    }
}
