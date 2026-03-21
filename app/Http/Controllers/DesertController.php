<?php

namespace App\Http\Controllers;

use App\Models\Dessert;
use App\Models\Ingredient;
use App\Models\MeasurementUnit;
use App\Models\Picture;
use Illuminate\Http\Request;

class DesertController extends Controller
{
    /**
     * Display a listing of the resource for the owner.
     */
    public function ownerIndex(Request $request)
    {
        $search = $request->input('search');

        $deserts = Dessert::with('picture', 'ingredients')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(10);

        // Data for the 'add dessert' form
        $pictures = Picture::all();
        $measurementUnits = MeasurementUnit::all();
        $ingredients = Ingredient::orderBy('name')->get();

        return view('livewire.deserts.owner-index', compact('deserts', 'search', 'pictures', 'measurementUnits', 'ingredients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'portion_size' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'picture_id' => 'nullable|exists:pictures,id',
            'ingredients' => 'nullable|array',
            'ingredients.*' => 'exists:ingredients,id',
        ]);

        $dessert = Dessert::create($validated);

        if (isset($validated['ingredients'])) {
            $dessert->ingredients()->sync($validated['ingredients']);
        }

        return redirect()->route('owner.deserts.index')->with('success', 'Dessert succesvol toegevoegd.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dessert $desert)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'portion_size' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'picture_id' => 'nullable|exists:pictures,id',
            'ingredients' => 'nullable|array',
            'ingredients.*' => 'exists:ingredients,id',
        ]);

        $desert->update($validated);

        if (isset($validated['ingredients'])) {
            $desert->ingredients()->sync($validated['ingredients']);
        } else {
            $desert->ingredients()->detach();
        }

        return redirect()->route('owner.deserts.index')->with('success', 'Dessert succesvol bijgewerkt.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dessert $desert)
    {
        $desert->ingredients()->detach();
        $desert->delete();

        return redirect()->route('owner.deserts.index')->with('success', 'Dessert succesvol verwijderd.');
    }
}
