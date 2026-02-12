<?php

namespace Database\Factories;

use App\Models\PriceEvolution;
use Illuminate\Database\Eloquent\Factories\Factory;

class PriceEvolutionFactory extends Factory
{
    protected $model = PriceEvolution::class;

    public function definition()
    {
        return [
            // Assuming the ingredients table is populated by someone else, we pick a random ID.
            'ingredientId' => $this->faker->numberBetween(1, 50),
            'price' => $this->faker->randomFloat(2, 0.5, 100),
            'amount' => $this->faker->randomFloat(2, 0.1, 10),
            'date' => $this->faker->date(),
            'source' => $this->faker->company,
        ];
    }
}
