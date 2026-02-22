<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use App\Models\Dessert;
use App\Models\Workshop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get existing dessert and workshop IDs, or null if they don't exist
        $dessertId = Dessert::inRandomOrder()->first()?->id;
        $workshopId = Workshop::inRandomOrder()->first()?->workshopId;

        return [
            'score' => fake()->numberBetween(1, 10),
            'content' => fake()->paragraph(),
            'date' => fake()->date(),
            'userId' => User::factory(),
            'dessertId' => fake()->optional()->passthrough($dessertId),
            'workshopId' => fake()->optional()->passthrough($workshopId),
        ];
    }
}
