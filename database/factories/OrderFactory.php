<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Availability;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            // match orders table: user_id (foreign key to users)
            'user_id' => User::factory(),

            // monetary and status fields
            'total_price' => $this->faker->randomFloat(2, 0, 500),
            'status' => $this->faker->randomElement(['pending', 'paid', 'cancelled', 'preparing', 'completed']),

            // availability foreign key (nullable)
            'availability_id' => $this->faker->boolean(70)
                ? Availability::factory()
                : null,

            // placed_at timestamp
            'placed_at' => $this->faker->optional()->dateTimeBetween('-2 years', 'now'),
        ];
    }
}
