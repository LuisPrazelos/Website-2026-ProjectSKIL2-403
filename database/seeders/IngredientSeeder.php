<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        // Create some specific ingredients
        Ingredient::create([
            'name' => 'Bloem',
            'measurement_unit_id' => 1, // Assuming 1 is a valid MeasurementUnit ID
            'minimumAmount' => 100,
        ]);

        Ingredient::create([
            'name' => 'Suiker',
            'measurement_unit_id' => 1, // Assuming 1 is a valid MeasurementUnit ID
            'minimumAmount' => 50,
        ]);

        // Create 10 random ingredients using the factory
        Ingredient::factory(10)->create();
    }
}
