<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Proposal;
use App\Models\User;
use App\Models\Theme;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proposal>
 */
class ProposalFactory extends Factory
{
    protected $model = Proposal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'message' => $this->faker->paragraph(),
            'person_count' => $this->faker->numberBetween(10, 200),
            'delivery_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'on_location' => $this->faker->boolean(30), // 30% kans op true
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'theme_id' => Theme::inRandomOrder()->first()->id ?? Theme::factory(),
        ];
    }
}
