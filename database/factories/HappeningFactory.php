<?php

namespace Database\Factories;

use App\Models\Happening;
use App\Models\Status;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Happening>
 */
class HappeningFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'message' => fake()->sentence(),
            'event_date' => fake()->dateTimeBetween('+1 week', '+1 year'),
            'person_count' => fake()->numberBetween(10, 200),
            'price_per_person' => fake()->randomFloat(2, 25, 250),
            'user_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'theme_id' => Theme::query()->inRandomOrder()->value('id'),
            'status_id' => Status::query()->inRandomOrder()->value('id'),
        ];
    }
}
