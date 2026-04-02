<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Surplus;
use App\Models\Ingredient;

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
        'recipe_id',
    ];

    protected $casts = [
        'price' => 'float',
        'portion_size' => 'float',
    ];

    /**
     * Get the calculated cost price.
     * Prioritizes the linked recipe, otherwise calculates from directly attached ingredients.
     */
    public function getCostPriceAttribute()
    {
        if ($this->recipe) {
            return $this->recipe->cost;
        }

        $totalCost = 0;

        foreach ($this->ingredients as $ingredient) {
            // Get the latest price for this ingredient from price_evolutions
            $latestPrice = $ingredient->priceEvolutions()
                ->orderBy('date', 'desc')
                ->first();

            if ($latestPrice && $latestPrice->amount > 0) {
                // Calculation: (pivot amount / price_evolution amount) * price_evolution price
                $ingredientCost = ($ingredient->pivot->amount / $latestPrice->amount) * $latestPrice->price;
                $totalCost += $ingredientCost;
            }
        }

        return $totalCost;
    }

    /**
     * A dessert can belong to a recipe.
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

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
                    ->withPivot('amount')
                    ->withTimestamps();
    }

    public function proposals()
    {
        return $this->belongsToMany(Proposal::class, 'proposal_desserts')
            ->withPivot(['quantity', 'allergies'])
            ->withTimestamps();
    }
}
