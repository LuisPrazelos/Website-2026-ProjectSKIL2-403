<?php

namespace Database\Seeders;

use App\Models\IngredientDessert;
use Illuminate\Database\Seeder;

class IngredientDessertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        IngredientDessert::factory()
            ->count(10)
            ->create();
    }
}
