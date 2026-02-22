<?php

namespace Database\Factories;

use App\Models\Dessert;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShoppingCart;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        // Decide if this cart item is a dessert or a leftover
        $isDessert = fake()->boolean();
        return [
            'orderId' => Order::factory(),

            'dessertId' => $isDessert
                ? Dessert::factory()
                : null,

            'leftoverId' => !$isDessert
                ? null // Leftover::factory()
                : null,

            'amount' => fake()->numberBetween(1, 10),
        ];
    }
}
