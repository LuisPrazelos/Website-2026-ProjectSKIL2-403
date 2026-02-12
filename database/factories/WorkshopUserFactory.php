<?php

namespace Database\Factories;

use App\Models\WorkshopUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkshopUser>
 */
class WorkshopUserFactory extends Factory
{
    protected $model = WorkshopUser::class;

    public function definition(): array
    {
        return [
            'workshop_id' => null, // set in seeder
            'user_id' => null, // set in seeder
            'registration_date' => fake()->date(),
            'total_adults' => fake()->numberBetween(0, 5),
            'total_children' => fake()->numberBetween(0, 5),
            'comment' => fake()->optional()->sentence(),
            'is_present' => fake()->boolean(50),
        ];
    }
}

