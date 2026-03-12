<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\MeasurementUnit;
use App\Models\Category;
use App\Models\PriceEvolution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    public function ownerIndex(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');

        $recipes = Recipe::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($category, function ($query, $category) {
                $query->where('category_id', $category);
            })
            ->with('ingredients')
            ->paginate(10);

        $ingredients = Ingredient::all()->mapWithKeys(function ($ingredient) {
            return [$ingredient->id => $ingredient->name];
        })->toArray();

        $units = MeasurementUnit::all()->mapWithKeys(function ($unit) {
            return [$unit->id => $unit->name];
        })->toArray();

        $categories = Category::all()->mapWithKeys(function ($category) {
            return [$category->id => $category->name];
        })->toArray();

        $portionUnits = $units;

        return view('recipes.owner', compact('recipes', 'search', 'category', 'ingredients', 'units', 'categories', 'portionUnits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'preparation_time' => 'required|integer|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'portion_size_quantity' => 'required|numeric|min:0',
            'portion_size_unit_id' => 'required|exists:measurement_units,id',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'ingredients' => 'required|array',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0',
            'ingredients.*.unit_id' => 'required|exists:measurement_units,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except(['photo', 'ingredients']);
        $data['portion_size'] = $request->portion_size_quantity;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('recipes', 'public');
            $data['photo'] = $path;
        }

        $recipe = Recipe::create($data);

        foreach ($request->ingredients as $ingredient) {
            $recipe->ingredients()->attach($ingredient['id'], [
                'quantity' => $ingredient['quantity'],
                'measurement_unit_id' => $ingredient['unit_id']
            ]);
        }

        return redirect()->route('owner.recipes.index')->with('success', 'Recipe added successfully!');
    }

    public function show(Recipe $recipe)
    {
        $recipe->load(['category', 'portionUnit', 'ingredients.measurementUnit', 'ingredients.ingredientAllergies.allergy']);

        $allAllergies = $recipe->ingredients->flatMap(function ($ingredient) {
            return $ingredient->ingredientAllergies->map(fn($ia) => $ia->allergy->name);
        })->unique()->sort()->values()->all();

        return view('recipes.show', compact('recipe', 'allAllergies'));
    }

    public function edit(Recipe $recipe)
    {
        $recipe->load('ingredients');

        $ingredients = Ingredient::all()->mapWithKeys(function ($ingredient) {
            return [$ingredient->id => $ingredient->name];
        })->toArray();

        $units = MeasurementUnit::all()->mapWithKeys(function ($unit) {
            return [$unit->id => $unit->name];
        })->toArray();

        $categories = Category::all()->mapWithKeys(function ($category) {
            return [$category->id => $category->name];
        })->toArray();

        $portionUnits = $units;

        return view('recipes.edit', compact('recipe', 'ingredients', 'units', 'categories', 'portionUnits'));
    }

    public function update(Request $request, Recipe $recipe)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'preparation_time' => 'required|integer|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'portion_size_quantity' => 'required|numeric|min:0',
            'portion_size_unit_id' => 'required|exists:measurement_units,id',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'ingredients' => 'required|array',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0',
            'ingredients.*.unit_id' => 'required|exists:measurement_units,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except(['photo', 'ingredients']);
        $data['portion_size'] = $request->portion_size_quantity;

        if ($request->hasFile('photo')) {
            // Delete old photo if it exists
            if ($recipe->photo) {
                Storage::disk('public')->delete($recipe->photo);
            }
            $path = $request->file('photo')->store('recipes', 'public');
            $data['photo'] = $path;
        }

        $recipe->update($data);

        // Sync ingredients
        $ingredientData = [];
        foreach ($request->ingredients as $ingredient) {
            $ingredientData[$ingredient['id']] = [
                'quantity' => $ingredient['quantity'],
                'measurement_unit_id' => $ingredient['unit_id']
            ];
        }
        $recipe->ingredients()->sync($ingredientData);

        return redirect()->route('owner.recipes.show', $recipe)->with('success', 'Recept succesvol bijgewerkt.');
    }

    public function destroy(Recipe $recipe)
    {
        // Delete photo if it exists
        if ($recipe->photo) {
            Storage::disk('public')->delete($recipe->photo);
        }
        $recipe->delete();

        return redirect()->route('owner.recipes.index')->with('success', 'Recept succesvol verwijderd.');
    }
}
