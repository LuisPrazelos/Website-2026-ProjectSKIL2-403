<?php

namespace Database\Factories;

use App\Models\NotificationChannel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NotificationChannel>
 */
class NotificationChannelFactory extends Factory
{
    protected $model = NotificationChannel::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(['email', 'sms', 'push']),
        ];
    }
}
