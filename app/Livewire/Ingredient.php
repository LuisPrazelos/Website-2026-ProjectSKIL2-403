<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ingredient as IngredientModel;
use App\Models\MeasurementUnit;
use App\Models\Allergy;
use App\Models\IngredientAllergy;
use Illuminate\Support\Facades\DB;

class Ingredient extends Component
{
    use WithPagination;

    public $name;
    public $unit_id;
    public $selectedAllergens = [];

    public $editingIngredientId = null;
    public $isEditMode = false;
    public $showModal = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'unit_id' => 'required|exists:measurement_units,id',
        'selectedAllergens' => 'nullable|array',
        'selectedAllergens.*' => 'exists:allergies,allergyId',
    ];

    public function openModal()
    {
        $this->resetInputFields();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->unit_id = MeasurementUnit::first()?->id;
        $this->selectedAllergens = [];
        $this->editingIngredientId = null;
        $this->isEditMode = false;
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            if ($this->isEditMode) {
                $ingredient = IngredientModel::findOrFail($this->editingIngredientId);
                $ingredient->update([
                    'name' => $this->name,
                    'measurement_unit_id' => $this->unit_id,
                ]);
                $ingredient->ingredientAllergies()->delete();
            } else {
                $ingredient = IngredientModel::create([
                    'name' => $this->name,
                    'measurement_unit_id' => $this->unit_id,
                ]);
            }

            if (!empty($this->selectedAllergens)) {
                foreach ($this->selectedAllergens as $allergyId) {
                    IngredientAllergy::create([
                        'ingredient_id' => $ingredient->id,
                        'allergyId' => $allergyId,
                    ]);
                }
            }
        });

        session()->flash('success', $this->isEditMode ? 'Ingredient updated successfully!' : 'Ingredient added successfully!');
        $this->closeModal();
    }

    public function edit($id)
    {
        $ingredient = IngredientModel::with('ingredientAllergies')->findOrFail($id);
        $this->editingIngredientId = $id;
        $this->name = $ingredient->name;
        $this->unit_id = $ingredient->measurement_unit_id;
        $this->selectedAllergens = $ingredient->ingredientAllergies->pluck('allergyId')->toArray();
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $ingredient = IngredientModel::findOrFail($id);
            $ingredient->ingredientAllergies()->delete();
            $ingredient->delete();
        });

        session()->flash('success', 'Ingredient deleted successfully!');
    }

    public function render()
    {
        return view('livewire.ingredient', [
            'ingredients' => IngredientModel::with('measurementUnit', 'ingredientAllergies.allergy')->paginate(10),
            'units' => MeasurementUnit::all(),
            'allergens' => Allergy::all(),
        ])->layout('components.layouts.app', ['title' => __('Ingrediënten beheren')]);
    }
}
