<?php

namespace Database\Factories;

use App\Models\Ingredient;
use App\Models\MeasurementUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

class IngredientFactory extends Factory
{
    protected $model = Ingredient::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'measurement_unit_id' => MeasurementUnit::factory(),
            'minimumAmount' => fake()->randomFloat(2, 0, 100),
        ];
    }
}
