<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\ShoppingCart;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShoppingCartFactory extends Factory
{
    protected $model = ShoppingCart::class;

    public function definition(): array
    {
        return [
            'orderId' => Order::factory(),

            'dessertId' => $isDessert
                ? Dessert::factory()
                : null,

            'leftoverId' => !$isDessert
                ? Leftover::factory()
                : null,

            'amount' => $this->faker->numberBetween(1, 10),
        ];
    }
}
