<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\MeasurementUnit;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $kgUnitId = MeasurementUnit::firstOrCreate(['name' => 'kg'])->id;

        // Create some specific ingredients
        Ingredient::create([
            'name' => 'Bloem',
            'measurement_unit_id' => $kgUnitId,
            'minimumAmount' => 100,
        ]);

        Ingredient::create([
            'name' => 'Suiker',
            'measurement_unit_id' => $kgUnitId,
            'minimumAmount' => 50,
        ]);

        // Create 10 random ingredients using the factory
        // Ingredient::factory(10)->create(); // Commented out for now
    }
}
