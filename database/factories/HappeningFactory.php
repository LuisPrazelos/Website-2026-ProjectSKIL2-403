<?php

namespace Database\Factories;

use App\Models\Happening;
use App\Models\Package;
use App\Models\State;
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
        $onLocation = fake()->boolean();

        return [
            'message' => fake()->sentence(),
            'event_date' => fake()->dateTimeBetween('+1 week', '+1 year'),
            'person_count' => fake()->numberBetween(10, 200),
            'remarks' => null,
            'price_per_person' => 0,
            'user_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'theme_id' => Theme::query()->inRandomOrder()->value('id'),
            'package_id' => Package::query()->inRandomOrder()->value('id'),
            'status_id' => State::query()->inRandomOrder()->value('id'),
            'on_location' => $onLocation,
            'location' => $onLocation ? fake()->address() : null,
        ];
    }

    public function answered(): static
    {
        return $this->state(fn () => [
            'remarks' => fake()->sentence(),
            'price_per_person' => fake()->randomFloat(2, 25, 250),
        ]);
    }
}
