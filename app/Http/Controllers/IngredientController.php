<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\MeasurementUnit;
use App\Models\Allergy;
use App\Models\IngredientAllergy; // Import IngredientAllergy model
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB; // Import DB facade for transactions

class IngredientController extends Controller
{
    /**
     * Display a listing of the ingredients.
     */
    public function ownerIndex(): View
    {
        // Fetch ingredients with their measurement unit and allergies
        $ingredients = Ingredient::with('measurementUnit', 'ingredientAllergies.allergy')->paginate(10);

        // Fetch all measurement units and allergies for the form
        $units = MeasurementUnit::all();
        $allergens = Allergy::all();

        return view('owner.ingredients', compact('ingredients', 'units', 'allergens'));
    }

    /**
     * Store a newly created ingredient in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'unit_id' => 'required|exists:measurement_units,id', // Use id
            'allergens' => 'nullable|array',
            'allergens.*' => 'exists:allergies,id', // Use id
        ]);

        DB::transaction(function () use ($validatedData) {
            $ingredient = Ingredient::create([
                'name' => $validatedData['name'],
                'measurement_unit_id' => $validatedData['unit_id'],
                // 'minimumAmount' => 0, // Assuming a default or not required for now
            ]);

            if (isset($validatedData['allergens'])) {
                foreach ($validatedData['allergens'] as $allergyId) {
                    IngredientAllergy::create([
                        'ingredientId' => $ingredient->id,
                        'allergyId' => $allergyId,
                    ]);
                }
            }
        });

        return redirect()->route('owner.ingredients.index')->with('success', 'Ingredient added successfully!');
    }

    /**
     * Show the form for editing the specified ingredient.
     */
    public function edit(Ingredient $ingredient): View
    {
        $units = MeasurementUnit::all();
        $allergens = Allergy::all();
        $ingredient->load('ingredientAllergies'); // Eager load the current allergens

        return view('owner.ingredients.edit', compact('ingredient', 'units', 'allergens'));
    }

    /**
     * Update the specified ingredient in storage.
     */
    public function update(Request $request, Ingredient $ingredient): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'unit_id' => 'required|exists:measurement_units,id',
            'allergens' => 'nullable|array',
            'allergens.*' => 'exists:allergies,id',
        ]);

        DB::transaction(function () use ($ingredient, $validatedData) {
            $ingredient->update([
                'name' => $validatedData['name'],
                'measurement_unit_id' => $validatedData['unit_id'],
            ]);

            // Sync allergens
            $ingredient->ingredientAllergies()->delete(); // Remove old allergens
            if (isset($validatedData['allergens'])) {
                foreach ($validatedData['allergens'] as $allergyId) {
                    IngredientAllergy::create([
                        'ingredientId' => $ingredient->id,
                        'allergyId' => $allergyId,
                    ]);
                }
            }
        });

        return redirect()->route('owner.ingredients.index')->with('success', 'Ingredient updated successfully!');
    }

    /**
     * Remove the specified ingredient from storage.
     */
    public function destroy(Ingredient $ingredient): RedirectResponse
    {
        DB::transaction(function () use ($ingredient) {
            // Delete associated ingredient allergies first (though cascade delete might handle this if set up in DB)
            $ingredient->ingredientAllergies()->delete();
            $ingredient->delete();
        });

        return redirect()->route('owner.ingredients.index')->with('success', 'Ingredient deleted successfully!');
    }
}
