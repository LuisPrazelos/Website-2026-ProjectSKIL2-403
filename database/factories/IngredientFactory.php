<?php

namespace Database\Factories;

use App\Models\Ingredient;
use App\Models\Meeteenheid;
use Illuminate\Database\Eloquent\Factories\Factory;

class IngredientFactory extends Factory
{
    protected $model = Ingredient::class;

    public function definition(): array
    {
        return [
            'ingredientName' => fake()->word(),
            'standardUnitId' => Meeteenheid::factory(),
            'minimumAmount' => fake()->randomFloat(2, 0, 100),
        ];
    }
}
