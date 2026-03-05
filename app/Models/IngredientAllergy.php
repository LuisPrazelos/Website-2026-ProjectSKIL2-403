<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientAllergy extends Model
{
    protected $primaryKey = ['ingredient_id', 'allergyId'];
    public $incrementing = false;

    protected $fillable = [
        'ingredient_id',
        'allergyId',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function allergy()
    {
        return $this->belongsTo(Allergy::class, 'allergyId');
    }
}
