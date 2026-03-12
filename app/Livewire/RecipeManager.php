<?php

namespace App\Livewire;

use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\MeasurementUnit;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;

class RecipeManager extends Component
{
    use WithPagination;
    use WithFileUploads;

    #[Url(except: '')]
    public $search = '';

    #[Url(except: '')]
    public $category = '';

    #[Url]
    public $edit_id = null;

    // Modal state
    public $showModal = false;
    public $isEditing = false;
    public $recipeId;

    // Form fields
    public $name;
    public $category_id;
    public $preparation_time;
    public $selling_price;
    public $portion_size_quantity;
    public $portion_size_unit_id;
    public $description;
    public $instructions;
    public $photo;
    public $existingPhoto;

    // Ingredients management
    public $ingredientsList = [];

    public function mount()
    {
        if ($this->edit_id) {
            $this->edit($this->edit_id);
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'preparation_time' => 'required|integer|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'portion_size_quantity' => 'required|numeric|min:0',
            'portion_size_unit_id' => 'required|exists:measurement_units,id',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'ingredientsList' => 'required|array',
            'ingredientsList.*.id' => 'required|exists:ingredients,id',
            'ingredientsList.*.quantity' => 'required|numeric|min:0',
            'ingredientsList.*.unit_id' => 'required|exists:measurement_units,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEditing = false;
        $this->ingredientsList = [['id' => '', 'quantity' => '', 'unit_id' => '']];
    }

    public function edit($id)
    {
        $this->resetForm();
        $this->isEditing = true;
        $this->recipeId = $id;

        $recipe = Recipe::with('ingredients')->findOrFail($id);

        $this->name = $recipe->name;
        $this->category_id = $recipe->category_id;
        $this->preparation_time = $recipe->preparation_time;
        $this->selling_price = $recipe->selling_price;
        $this->portion_size_quantity = $recipe->portion_size;
        $this->portion_size_unit_id = $recipe->portion_size_unit_id;
        $this->description = $recipe->description;
        $this->instructions = $recipe->instructions;
        $this->existingPhoto = $recipe->photo;

        $this->ingredientsList = $recipe->ingredients->map(function ($ingredient) {
            return [
                'id' => $ingredient->id,
                'quantity' => $ingredient->pivot->quantity,
                'unit_id' => $ingredient->pivot->measurement_unit_id
            ];
        })->toArray();

        if (empty($this->ingredientsList)) {
             $this->ingredientsList = [['id' => '', 'quantity' => '', 'unit_id' => '']];
        }

        $this->showModal = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'category_id' => $this->category_id,
            'preparation_time' => $this->preparation_time,
            'selling_price' => $this->selling_price,
            'portion_size' => $this->portion_size_quantity,
            'portion_size_unit_id' => $this->portion_size_unit_id,
            'description' => $this->description,
            'instructions' => $this->instructions,
        ];

        if ($this->photo) {
            $data['photo'] = $this->photo->store('recipes', 'public');
        }

        $recipe = Recipe::create($data);

        foreach ($this->ingredientsList as $item) {
            $recipe->ingredients()->attach($item['id'], [
                'quantity' => $item['quantity'],
                'measurement_unit_id' => $item['unit_id']
            ]);
        }

        $this->closeModal();
        session()->flash('success', 'Recipe added successfully!');
    }

    public function update()
    {
        $this->validate();

        $recipe = Recipe::findOrFail($this->recipeId);

        $data = [
            'name' => $this->name,
            'category_id' => $this->category_id,
            'preparation_time' => $this->preparation_time,
            'selling_price' => $this->selling_price,
            'portion_size' => $this->portion_size_quantity,
            'portion_size_unit_id' => $this->portion_size_unit_id,
            'description' => $this->description,
            'instructions' => $this->instructions,
        ];

        if ($this->photo) {
            if ($recipe->photo) {
                Storage::disk('public')->delete($recipe->photo);
            }
            $data['photo'] = $this->photo->store('recipes', 'public');
        }

        $recipe->update($data);

        $syncData = [];
        foreach ($this->ingredientsList as $item) {
            $syncData[$item['id']] = [
                'quantity' => $item['quantity'],
                'measurement_unit_id' => $item['unit_id']
            ];
        }
        $recipe->ingredients()->sync($syncData);

        $this->closeModal();
        session()->flash('success', 'Recipe updated successfully!');
    }

    public function delete($id)
    {
        $recipe = Recipe::findOrFail($id);
        if ($recipe->photo) {
            Storage::disk('public')->delete($recipe->photo);
        }
        $recipe->delete();
        session()->flash('success', 'Recipe deleted successfully!');
    }

    public function addIngredient()
    {
        $this->ingredientsList[] = ['id' => '', 'quantity' => '', 'unit_id' => ''];
    }

    public function removeIngredient($index)
    {
        unset($this->ingredientsList[$index]);
        $this->ingredientsList = array_values($this->ingredientsList);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['name', 'category_id', 'preparation_time', 'selling_price', 'portion_size_quantity', 'portion_size_unit_id', 'description', 'instructions', 'photo', 'existingPhoto', 'ingredientsList', 'recipeId', 'edit_id']);
        $this->ingredientsList = [];
    }

    public function render()
    {
        $recipes = Recipe::query()
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($this->category, function ($query, $category) {
                $query->where('category_id', $category);
            })
            ->with('category')
            ->paginate(10);

        $allCategories = Category::all()->mapWithKeys(function ($category) {
            return [$category->id => $category->name];
        })->toArray();

        $allIngredients = Ingredient::all()->mapWithKeys(function ($ingredient) {
            return [$ingredient->id => $ingredient->name];
        })->toArray();

        $allUnits = MeasurementUnit::all()->mapWithKeys(function ($unit) {
            return [$unit->id => $unit->name];
        })->toArray();

        return view('livewire.recipe-manager', [
            'recipes' => $recipes,
            'allCategories' => $allCategories,
            'allIngredients' => $allIngredients,
            'allUnits' => $allUnits,
        ]);
    }
}
