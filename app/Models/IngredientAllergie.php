<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientAllergie extends Model
{
    protected $primaryKey = ['ingredientId', 'allergie'];
    public $incrementing = false;

    protected $fillable = [
        'ingredientId',
        'allergie',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingredientId');
    }

    public function allergie()
    {
        return $this->belongsTo(Allergie::class, 'allergie');
    }
}
