<?php

namespace App\Livewire\Deserts;

use App\Models\Dessert;
use App\Models\Ingredient;
use App\Models\MeasurementUnit;
use App\Models\Picture;
use App\Models\Recipe;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;

class OwnerIndex extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $showAddModal = false;
    public $showEditModal = false;
    public $editingDesert = null;

    // Properties for the Add/Edit form
    public $name;
    public $price;
    public $originalPrice; // Nieuwe eigenschap om de originele prijs te onthouden
    public $portion_size;
    public $measurement_unit_id; // Added measurement_unit_id
    public $description;
    public $picture_id;
    public $recipe_id; // Added property for the linked recipe
    public $is_available = true; // Added property for availability
    public $photo;

    protected $rules = [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'portion_size' => 'required|numeric|min:0', // Changed to numeric
        'measurement_unit_id' => 'required|exists:measurement_units,id', // Added validation rule
        'description' => 'required|string',
        'picture_id' => 'nullable|exists:pictures,id',
        'recipe_id' => 'required|exists:recipes,id',
        'is_available' => 'boolean',
        'photo' => 'nullable|image|max:1024',
    ];

    public function render()
    {
        $deserts = Dessert::with('picture', 'recipe', 'measurementUnit')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->paginate(10);

        $pictures = Picture::all();
        $recipes = Recipe::orderBy('name')->get(); // Fetch all recipes
        $measurementUnits = MeasurementUnit::orderBy('name')->get(); // Fetch all measurement units

        return view('deserts.owner-index-component', [
            'deserts' => $deserts,
            'pictures' => $pictures,
            'recipes' => $recipes,
            'measurementUnits' => $measurementUnits,
        ]);
    }

    public function store()
    {
        $this->validate();

        // Handle Photo Upload
        if ($this->photo) {
            $filename = $this->photo->getClientOriginalName();
            $this->photo->storeAs('public/pictures', $filename);

            $picture = Picture::create([
                'title' => $this->name,
                'hash' => $filename,
            ]);
            $this->picture_id = $picture->id;
        }

        $dessert = Dessert::create([
            'name' => $this->name,
            'price' => $this->price,
            'portion_size' => $this->portion_size,
            'measurement_unit_id' => $this->measurement_unit_id,
            'description' => $this->description,
            'picture_id' => $this->picture_id,
            'recipe_id' => $this->recipe_id, // Save the recipe_id
            'is_available' => $this->is_available,
        ]);

        $this->resetForm();
        $this->showAddModal = false;
        session()->flash('success', 'Dessert succesvol toegevoegd.');
    }

    public function edit(Dessert $desert)
    {
        $this->editingDesert = $desert;
        $this->name = $desert->name;
        $this->price = $desert->price;
        $this->originalPrice = $desert->price; // Sla originele prijs op
        $this->portion_size = $desert->portion_size;
        $this->measurement_unit_id = $desert->measurement_unit_id;
        $this->description = $desert->description;
        $this->picture_id = $desert->picture_id;
        $this->recipe_id = $desert->recipe_id; // Populate the recipe_id
        $this->is_available = $desert->is_available;
        $this->photo = null;

        $this->showEditModal = true;
    }

    public function update()
    {
        $this->validate();

        if ($this->photo) {
            $filename = $this->photo->getClientOriginalName();
            $this->photo->storeAs('public/pictures', $filename);

            $picture = Picture::create([
                'title' => $this->name,
                'hash' => $filename,
            ]);
            $this->picture_id = $picture->id;
        }

        $this->editingDesert->update([
            'name' => $this->name,
            'price' => $this->price,
            'portion_size' => $this->portion_size,
            'measurement_unit_id' => $this->measurement_unit_id,
            'description' => $this->description,
            'picture_id' => $this->picture_id,
            'recipe_id' => $this->recipe_id, // Update the recipe_id
            'is_available' => $this->is_available,
        ]);

        $this->resetForm();
        $this->showEditModal = false;
        session()->flash('success', 'Dessert succesvol bijgewerkt.');
    }

    public function toggleAvailability(Dessert $desert)
    {
        $desert->is_available = !$desert->is_available;
        $desert->save();
        session()->flash('success', 'Beschikbaarheid van dessert bijgewerkt.');
    }

    public function destroy(Dessert $desert)
    {
        $desert->delete();
        session()->flash('success', 'Dessert succesvol verwijderd.');
    }

    public function resetForm()
    {
        $this->reset(['name', 'price', 'originalPrice', 'portion_size', 'measurement_unit_id', 'description', 'picture_id', 'recipe_id', 'is_available', 'editingDesert', 'photo']);
        $this->is_available = true; // Default back to true
    }
}
