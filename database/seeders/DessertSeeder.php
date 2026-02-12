<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dessert;

class DessertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a single test dessert with explicit fields (similar to your User example)
        Dessert::factory()->create([
            'name' => 'Test Dessert',
            'price' => 4.99,
            'description' => 'Sample dessert created by DessertSeeder',
            'preparation_method' => 'Ready to serve',
            'notes' => 'Seed data',
            'image' => null,
            'portion_size' => 1.0,
            'measurement_unit_id' => 1,
        ]);
    }
}
