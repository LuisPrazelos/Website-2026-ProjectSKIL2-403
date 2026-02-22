<?php

namespace Database\Factories;

use App\Models\Surplus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Surplus>
 */
class SurplusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Surplus::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'total_amount' => fake()->numberBetween(1, 200),
            'sale' => fake()->randomFloat(2, 0, 100),
            'status' => fake()->randomElement(['available', 'reserved', 'picked_up']),
            'expiration_date' => fake()->optional()->date(),
            'dessert_id' => 1,
            'comment' => fake()->optional()->sentence(),
        ];
    }
}
