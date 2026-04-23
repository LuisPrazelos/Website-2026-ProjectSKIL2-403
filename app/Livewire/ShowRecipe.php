<?php

namespace App\Livewire;

use App\Models\Recipe;
use App\Models\MeasurementUnit;
use Livewire\Component;

class ShowRecipe extends Component
{
    public Recipe $recipe;
    public array $units = [];

    public function mount(Recipe $recipe)
    {
        $this->recipe = $recipe;
        $this->units = MeasurementUnit::pluck('name', 'id')->toArray();
    }

    public function render()
    {
        $this->recipe->load(['category', 'portionUnit', 'ingredients.measurementUnit', 'ingredients.ingredientAllergies.allergy']);

        $allAllergies = $this->recipe->ingredients->flatMap(function ($ingredient) {
            return $ingredient->ingredientAllergies->map(fn($ia) => $ia->allergy->name);
        })->unique()->sort()->values()->all();

        return view('livewire.show-recipe', [
            'allAllergies' => $allAllergies,
            'units' => $this->units
        ]);
    }
}
