<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allergie extends Model
{
    protected $primaryKey = 'allergyId';

    protected $fillable = [
        'name',
    ];

    public function ingredientAllergies()
    {
        return $this->hasMany(IngredientAllergie::class, 'allergyId');
    }

    public function workshopAllergies()
    {
        return $this->hasMany(WorkshopAllergie::class, 'allergyId');
    }
}
