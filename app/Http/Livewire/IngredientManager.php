<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Ingredient;

class IngredientManager extends Component
{
    public $ingredients;

    public function mount()
    {
        $this->ingredients = Ingredient::all();
    }

    public function render()
    {
        return view('livewire.ingredient-manager', [
            'ingredients' => $this->ingredients,
        ]);
    }

    // Voeg hier CRUD-methodes toe (create, update, delete)
}

