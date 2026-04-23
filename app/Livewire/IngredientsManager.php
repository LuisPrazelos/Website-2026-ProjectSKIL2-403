<?php

namespace App\Livewire;

use App\Models\Ingredient;
use App\Models\MeasurementUnit;
use App\Models\Allergy;
use App\Models\IngredientAllergy;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class IngredientsManager extends Component
{
    use WithPagination;

    public $search = '';
    public $showAddModal = false;
    public $showEditModal = false;
    public $editingIngredient = null;

    // Form properties
    public $name;
    public $unit_id;
    public $selectedAllergens = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'unit_id' => 'required|exists:measurement_units,id',
        'selectedAllergens' => 'nullable|array',
        'selectedAllergens.*' => 'exists:allergies,allergyId',
    ];

    public function render()
    {
        $ingredients = Ingredient::with('measurementUnit', 'ingredientAllergies.allergy')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->paginate(10);

        $units = MeasurementUnit::all();
        $allergens = Allergy::all();

        return view('livewire.ingredients-manager', [
            'ingredients' => $ingredients,
            'units' => $units,
            'allergens' => $allergens,
        ])->layout('components.layouts.app', ['title' => 'Ingrediënten Beheren']);
    }

    public function store()
    {
        $this->validate();

        DB::transaction(function () {
            $ingredient = Ingredient::create([
                'name' => $this->name,
                'measurement_unit_id' => $this->unit_id,
            ]);

            if (!empty($this->selectedAllergens)) {
                foreach ($this->selectedAllergens as $allergyId) {
                    IngredientAllergy::create([
                        'ingredient_id' => $ingredient->id,
                        'allergyId' => $allergyId,
                    ]);
                }
            }
        });

        $this->resetForm();
        $this->showAddModal = false;
        session()->flash('success', 'Ingrediënt succesvol toegevoegd!');
    }

    public function edit(Ingredient $ingredient)
    {
        $this->editingIngredient = $ingredient;
        $this->name = $ingredient->name;
        $this->unit_id = $ingredient->measurement_unit_id;
        $this->selectedAllergens = $ingredient->ingredientAllergies->pluck('allergyId')->toArray();

        $this->showEditModal = true;
    }

    public function update()
    {
        $this->validate();

        DB::transaction(function () {
            $this->editingIngredient->update([
                'name' => $this->name,
                'measurement_unit_id' => $this->unit_id,
            ]);

            $this->editingIngredient->ingredientAllergies()->delete();
            if (!empty($this->selectedAllergens)) {
                foreach ($this->selectedAllergens as $allergyId) {
                    IngredientAllergy::create([
                        'ingredient_id' => $this->editingIngredient->id,
                        'allergyId' => $allergyId,
                    ]);
                }
            }
        });

        $this->resetForm();
        $this->showEditModal = false;
        session()->flash('success', 'Ingrediënt succesvol bijgewerkt!');
    }

    public function destroy(Ingredient $ingredient)
    {
        DB::transaction(function () use ($ingredient) {
            $ingredient->ingredientAllergies()->delete();
            $ingredient->delete();
        });
        session()->flash('success', 'Ingrediënt succesvol verwijderd!');
    }

    public function resetForm()
    {
        $this->reset(['name', 'unit_id', 'selectedAllergens', 'editingIngredient']);
    }
}
