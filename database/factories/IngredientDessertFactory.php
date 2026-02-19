<?php

namespace Database\Factories;

use App\Models\Dessert;
use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IngredientDessert>
 */
class IngredientDessertFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Pick an existing Dessert id or create one
            'dessertId' => Dessert::query()->inRandomOrder()->value('id') ?? Dessert::factory()->create()->id,
            // Pick an existing Ingredient id (uses ingredientId PK) or create one
            'ingredientId' => Ingredient::query()->inRandomOrder()->value('ingredientId') ?? Ingredient::factory()->create()->ingredientId,
            'amount' => fake()->randomFloat(2, 1, 500),
        ];
    }
}
