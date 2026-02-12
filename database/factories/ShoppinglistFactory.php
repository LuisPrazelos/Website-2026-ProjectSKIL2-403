<?php

namespace Database\Factories;

use App\Models\ShoppingList;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShoppinglistFactory extends Factory
{
    protected $model = ShoppingList::class;

    public function definition(): array
    {
        return [
            'personId' => null, // This should be set when creating a shopping list
            'name' => fake()->sentence(3),
            'isCompleted' => fake()->boolean(),
            'internalComment' => fake()->paragraph(),
        ];
    }
}
