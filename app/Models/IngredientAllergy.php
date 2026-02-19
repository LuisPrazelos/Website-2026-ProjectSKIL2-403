<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientAllergy extends Model
{
    protected $primaryKey = ['ingredientId', 'allergyId'];
    public $incrementing = false;

    protected $fillable = [
        'ingredientId',
        'allergyId',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingredientId');
    }

    public function allergy()
    {
        return $this->belongsTo(Allergy::class, 'allergyId');
    }
}
