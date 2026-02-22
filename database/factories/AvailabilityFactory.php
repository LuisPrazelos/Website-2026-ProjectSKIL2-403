<?php

namespace Database\Factories;

use App\Models\Availability;
use Illuminate\Database\Eloquent\Factories\Factory;

class AvailabilityFactory extends Factory
{
    protected $model = Availability::class;

    public function definition(): array
    {
        // random date in the next 30 days
        $date = fake()->dateTimeBetween('now', '+30 days');

        // pickup window on that date
        $start = (clone $date)->setTime(
            fake()->numberBetween(8, 12),
            0
        );

        $stop = (clone $start)->modify('+2 hours');
        return [
            'date' => $date->format('Y-m-d'),

            // exception is optional
            'exceptionAvailabilityDate' => fake()->boolean(30)
                ? fake()->dateTimeBetween('now', '+30 days')
                : null,

            'pickUpTimeStart' => $start,
            'pickUpTimeStop' => $stop,
        ];
    }
}
