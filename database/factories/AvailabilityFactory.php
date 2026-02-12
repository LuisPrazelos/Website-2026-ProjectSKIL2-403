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
        $date = $this->faker->dateTimeBetween('now', '+30 days');

        // pickup window on that date
        $start = (clone $date)->setTime(
            $this->faker->numberBetween(8, 12),
            0
        );

        $stop = (clone $start)->modify('+2 hours');
        return [
            'date' => $date->format('Y-m-d'),

            // exception is optional
            'exceptionAvailabilityDate' => $this->faker->boolean(30)
                ? $this->faker->dateTimeBetween('now', '+30 days')
                : null,

            'pickUpTimeStart' => $start,
            'pickUpTimeStop' => $stop,
        ];
    }
}
