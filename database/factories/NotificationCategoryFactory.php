<?php

namespace Database\Factories;

use App\Models\NotificationCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NotificationCategory>
 */
class NotificationCategoryFactory extends Factory
{
    protected $model = NotificationCategory::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(['comments', 'likes', 'follows']),
        ];
    }
}

