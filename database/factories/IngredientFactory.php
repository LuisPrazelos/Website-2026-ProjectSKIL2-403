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
        $kgUnitId = MeasurementUnit::firstOrCreate(['name' => 'kg'])->id;

        return [
            'name' => fake()->word(),
            'measurement_unit_id' => $kgUnitId,
            'minimumAmount' => fake()->randomFloat(2, 0, 100),
        ];
    }
}
