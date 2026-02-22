<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $primaryKey = 'ingredientId';

    protected $fillable = [
        'ingredientName',
        'standardUnitId',
        'minimumAmount',
    ];

    public function standardUnit()
    {
        return $this->belongsTo(MeasurementUnit::class, 'standardUnitId');
    }

    public function ingredientAllergies()
    {
        return $this->hasMany(IngredientAllergy::class, 'ingredientId');
    }
}
