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
            'dessert_id' => Dessert::query()->inRandomOrder()->value('id') ?? Dessert::factory()->create()->id,
            'ingredient_id' => Ingredient::query()->inRandomOrder()->value('id') ?? Ingredient::factory()->create()->id,
            'amount' => fake()->randomFloat(2, 1, 500),
        ];
    }
}
