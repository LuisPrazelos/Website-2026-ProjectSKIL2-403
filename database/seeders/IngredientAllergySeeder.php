<?php

namespace Database\Seeders;

use App\Models\IngredientAllergy;
use Illuminate\Database\Seeder;
use App\Models\Ingredient;
use App\Models\Allergy;

class IngredientAllergySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some existing ingredients and allergies
        $ingredients = Ingredient::all();
        $allergies = Allergy::all();

        if ($ingredients->isEmpty() || $allergies->isEmpty()) {
            // Log or output a message if no ingredients or allergies are found
            // This might happen if IngredientSeeder or AllergySeeder haven't run or created data
            return;
        }

        // Example: Attach some allergies to the first few ingredients
        // You can customize this logic based on your desired test data
        if ($ingredients->count() >= 1 && $allergies->count() >= 1) {
            IngredientAllergy::create([
                'ingredient_id' => $ingredients->first()->id,
                'allergyId' => $allergies->first()->allergyId,
            ]);
        }

        if ($ingredients->count() >= 2 && $allergies->count() >= 2) {
            IngredientAllergy::create([
                'ingredient_id' => $ingredients->skip(1)->first()->id,
                'allergyId' => $allergies->skip(1)->first()->allergyId,
            ]);
        }

        // Add more seeding logic as needed
    }
}
