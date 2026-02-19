<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{
    protected $primaryKey = 'allergyId';

    protected $fillable = [
        'name',
    ];

    public function ingredientAllergies()
    {
        return $this->hasMany(IngredientAllergy::class, 'allergyId');
    }

    public function workshopAllergies()
    {
        return $this->hasMany(WorkshopAllergie::class, 'allergyId');
    }
}
