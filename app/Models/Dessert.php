<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Surplus;

class Dessert extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'preparation_method',
        'notes',
        'picture_id',
        'portion_size',
        'measurement_unit_id',
    ];

    protected $casts = [
        'price' => 'float',
        'portion_size' => 'float',
    ];

    /**
     * One dessert can have many surpluses (leftovers).
     */
    public function surpluses()
    {
        return $this->hasMany(Surplus::class, 'dessert_id');
    }

    public function picture()
    {
        return $this->belongsTo(Picture::class, 'picture_id');
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'ingredient_desserts', 'dessert_id', 'ingredient_id')
                    ->withPivot('amount');
    }
}
