<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Workshop>
 */
class WorkshopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3), // short title
            'date' => fake()->dateTimeBetween('now', '+1 year'), // future date
            'price_adults' => fake()->randomFloat(2, 10, 100), // price between 10.00 and 100.00
            'price_children' => fake()->randomFloat(2, 5, 50),  // price between 5.00 and 50.00
            'description' => fake()->paragraph(), // a paragraph
            'location' => fake()->address(), // fake address
            'duration_minutes' => fake()->numberBetween(60, 240), // between 1 and 4 hours (minutes)
            'max_participants' => fake()->numberBetween(5, 30), // between 5 and 30 participants
        ];
    }
}
