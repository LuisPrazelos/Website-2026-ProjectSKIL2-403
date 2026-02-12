<?php

namespace Database\Factories;

use App\Models\Dessert;
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
            'dessertId' => Dessert::query()->inRandomOrder()->value('id') ?? Dessert::factory(),
            'ingredientId' => fake()->numberBetween(1, 100), // Assuming ingredientId refers to an external or not-yet-created Ingredient model
            'amount' => fake()->randomFloat(2, 1, 500),
        ];
    }
}
