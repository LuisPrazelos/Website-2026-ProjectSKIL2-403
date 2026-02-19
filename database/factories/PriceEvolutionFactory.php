<?php

namespace Database\Factories;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PriceEvolution>
 */
class PriceEvolutionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ingredientId' => Ingredient::query()->inRandomOrder()->value('ingredientId') ?? Ingredient::factory()->create()->ingredientId,
            'price' => fake()->randomFloat(2, 0.50, 20.00),
            'amount' => fake()->randomFloat(2, 0.1, 5.0),
            'date' => fake()->dateTimeBetween('-1 year', 'now'),
            'source' => fake()->company(),
        ];
    }
}
