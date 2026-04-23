<?php

namespace App\Livewire;

use App\Models\Workshop;
use Livewire\Component;
use Livewire\WithPagination;

class WorkshopManager extends Component
{
    use WithPagination;

    public $search = '';
    public $showAddModal = false;
    public $showEditModal = false;
    public $editingWorkshop = null;

    // Properties for the Add/Edit form
    public $name;
    public $date;
    public $price_adults;
    public $price_children;
    public $description;
    public $location;
    public $duration_minutes;
    public $max_participants;

    protected $rules = [
        'name' => 'required|string|max:255',
        'date' => 'required',
        'price_adults' => 'required|numeric|min:0',
        'price_children' => 'required|numeric|min:0',
        'description' => 'required|string',
        'location' => 'required|string|max:255',
        'duration_minutes' => 'required|integer|min:1',
        'max_participants' => 'required|integer|min:1',
    ];

    public function render()
    {
        $workshops = Workshop::when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('location', 'like', "%{$this->search}%");
            })
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('livewire.workshop-manager', [
            'workshops' => $workshops,
        ])->layout('components.layouts.app', ['title' => __('Workshops beheren')]);
    }

    public function create()
    {
        $this->resetForm();
        $this->showAddModal = true;
    }

    public function store()
    {
        $this->validate();

        Workshop::create([
            'name' => $this->name,
            'date' => $this->date,
            'price_adults' => $this->price_adults,
            'price_children' => $this->price_children,
            'description' => $this->description,
            'location' => $this->location,
            'duration_minutes' => $this->duration_minutes,
            'max_participants' => $this->max_participants,
        ]);

        $this->resetForm();
        $this->showAddModal = false;
        session()->flash('success', 'Workshop succesvol toegevoegd.');
    }

    public function edit(Workshop $workshop)
    {
        $this->editingWorkshop = $workshop;
        $this->name = $workshop->name;
        // Format date for datetime-local input
        $this->date = \Carbon\Carbon::parse($workshop->date)->format('Y-m-d\TH:i');
        $this->price_adults = $workshop->price_adults;
        $this->price_children = $workshop->price_children;
        $this->description = $workshop->description;
        $this->location = $workshop->location;
        $this->duration_minutes = $workshop->duration_minutes;
        $this->max_participants = $workshop->max_participants;

        $this->showEditModal = true;
    }

    public function update()
    {
        $this->validate();

        $this->editingWorkshop->update([
            'name' => $this->name,
            'date' => $this->date,
            'price_adults' => $this->price_adults,
            'price_children' => $this->price_children,
            'description' => $this->description,
            'location' => $this->location,
            'duration_minutes' => $this->duration_minutes,
            'max_participants' => $this->max_participants,
        ]);

        $this->resetForm();
        $this->showEditModal = false;
        session()->flash('success', 'Workshop succesvol bijgewerkt.');
    }

    public function destroy(Workshop $workshop)
    {
        $workshop->delete();
        session()->flash('success', 'Workshop succesvol verwijderd.');
    }

    public function resetForm()
    {
        $this->reset([
            'name', 'date', 'price_adults', 'price_children', 'description',
            'location', 'duration_minutes', 'max_participants', 'editingWorkshop'
        ]);
        $this->showAddModal = false;
        $this->showEditModal = false;
    }
}
