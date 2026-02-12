<?php

namespace Database\Factories;

use App\Models\review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\review>
 */
class ReviewFactory extends Factory
{
    protected $model = review::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'score' => fake()->numberBetween(1, 10),
            'content' => fake()->paragraph(),
            'date' => fake()->date(),
            'userId' => User::factory(),
            'desssertId' => fake()->optional()->numberBetween(1, 100),
            'workshopId' => fake()->optional()->numberBetween(1, 100),
        ];
    }
}
