<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'preparation_time',
        'description',
        'category_id',
        'portion_size', // This will store the quantity
        'portion_size_unit_id', // New field for the unit
        'selling_price', // New field
        'instructions', // New field
        'photo', // Added photo field
    ];

    /**
     * The ingredients that belong to the recipe.
     */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients')
                    ->withPivot('quantity', 'measurement_unit_id');
    }

    /**
     * Get the category that owns the recipe.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the measurement unit for the portion size.
     */
    public function portionUnit()
    {
        return $this->belongsTo(MeasurementUnit::class, 'portion_size_unit_id');
    }

    /**
     * Calculate the total cost of the recipe based on its ingredients.
     *
     * @return float
     */
    public function getCostAttribute()
    {
        return $this->ingredients->reduce(function ($carry, $ingredient) {
            $latestPrice = $ingredient->priceEvolutions()->latest('date')->first();

            if ($latestPrice && $latestPrice->amount > 0) {
                $costPerUnit = $latestPrice->price / $latestPrice->amount;
                $ingredientCost = $costPerUnit * $ingredient->pivot->quantity;
                return $carry + $ingredientCost;
            }

            return $carry;
        }, 0);
    }
}
