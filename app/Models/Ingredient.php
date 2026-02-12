<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $primaryKey = 'ingredientId';

    protected $fillable = [
        'ingredientNaam',
        'standaardEenheidId',
        'minimumAantal',
    ];

    public function standaardEenheid()
    {
        return $this->belongsTo(StandaardEenheid::class, 'standaardEenheidId');
    }

    public function ingredientAllergies()
    {
        return $this->hasMany(IngredientAllergie::class, 'ingredientId');
    }
}
