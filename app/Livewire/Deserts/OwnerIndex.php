<?php

namespace App\Livewire\Deserts;

use App\Models\Dessert;
use App\Models\Ingredient;
use App\Models\MeasurementUnit;
use App\Models\Picture;
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
    public $description;
    public $picture_id;
    public $ingredients = [];
    public $photo;

    protected $rules = [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'portion_size' => 'required|integer|min:0', // Integer validatie
        'description' => 'required|string',
        'picture_id' => 'nullable|exists:pictures,id',
        'ingredients' => 'required|array',
        'ingredients.*' => 'exists:ingredients,id',
        'photo' => 'nullable|image|max:1024',
    ];

    public function render()
    {
        $deserts = Dessert::with('picture', 'ingredients')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->paginate(10);

        $pictures = Picture::all();
        $measurementUnits = MeasurementUnit::all();
        $allIngredients = Ingredient::orderBy('name')->get();

        return view('deserts.owner-index-component', [
            'deserts' => $deserts,
            'pictures' => $pictures,
            'allIngredients' => $allIngredients,
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
            'portion_size' => (int) $this->portion_size, // Forceer cast naar int
            'description' => $this->description,
            'picture_id' => $this->picture_id,
        ]);

        if (!empty($this->ingredients)) {
            $dessert->ingredients()->sync($this->ingredients);
        }

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
        $this->description = $desert->description;
        $this->picture_id = $desert->picture_id;
        $this->ingredients = $desert->ingredients->pluck('id')->toArray();
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
            'portion_size' => (int) $this->portion_size, // Forceer cast naar int
            'description' => $this->description,
            'picture_id' => $this->picture_id,
        ]);

        if (!empty($this->ingredients)) {
            $this->editingDesert->ingredients()->sync($this->ingredients);
        } else {
            $this->editingDesert->ingredients()->detach();
        }

        $this->resetForm();
        $this->showEditModal = false;
        session()->flash('success', 'Dessert succesvol bijgewerkt.');
    }

    public function destroy(Dessert $desert)
    {
        $desert->ingredients()->detach();
        $desert->delete();
        session()->flash('success', 'Dessert succesvol verwijderd.');
    }

    public function resetForm()
    {
        $this->reset(['name', 'price', 'originalPrice', 'portion_size', 'description', 'picture_id', 'ingredients', 'editingDesert', 'photo']);
    }
}
