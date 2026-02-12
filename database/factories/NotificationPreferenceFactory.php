<?php

namespace Database\Factories;

use App\Models\NotificationPreference;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NotificationPreference>
 */
class NotificationPreferenceFactory extends Factory
{
    protected $model = NotificationPreference::class;

    public function definition(): array
    {
        return [
            'user_id' => null, // set in seeder
            'notification_category_id' => null, // set in seeder
            'notification_channel_id' => null, // set in seeder
            'received' => fake()->boolean(80),
        ];
    }
}

