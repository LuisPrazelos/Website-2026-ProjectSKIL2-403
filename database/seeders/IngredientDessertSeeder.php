<?php

namespace Database\Seeders;

use App\Models\Dessert;
use App\Models\Ingredient;
use App\Models\IngredientDessert;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientDessertSeeder extends Seeder
{
    public function run(): void
    {
        // Zorg dat dessert 1 altijd ingrediënten heeft
        $dessert = Dessert::first();
        $ingredients = Ingredient::all();

        if ($dessert && $ingredients->isNotEmpty()) {
            foreach ($ingredients as $ingredient) {
                DB::table('ingredient_desserts')->insertOrIgnore([
                    'dessert_id'    => $dessert->id,
                    'ingredient_id' => $ingredient->id,
                    'amount'        => rand(50, 300),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        // Vul de rest aan met random koppelingen
        IngredientDessert::factory()
            ->count(10)
            ->create();
    }
}
