<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allergie extends Model
{
    protected $primaryKey = 'allergieId';

    protected $fillable = [
        'naam',
    ];

    public function ingredientAllergies()
    {
        return $this->hasMany(IngredientAllergie::class, 'allergie');
    }

    public function workshopAllergies()
    {
        return $this->hasMany(WorkshopAllergie::class, 'allergieId');
    }
}
