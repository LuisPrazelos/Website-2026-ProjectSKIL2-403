<?php

namespace App\Livewire;

use App\Models\Dessert;
use App\Models\Surplus;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Carbon\Carbon;

class SurplusManager extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    public $showAddModal = false;
    public $showEditModal = false;
    public $editingSurplus = null;

    // Form properties
    public $dessert_id;
    public $date;
    public $total_amount;
    public $sale;
    public $comment;
    public $status;

    protected $rules = [
        'dessert_id' => 'required|exists:desserts,id',
        'date' => 'required|date|after_or_equal:today',
        'total_amount' => 'required|integer|min:1',
        'sale' => 'required|numeric|min:0|max:100',
        'comment' => 'nullable|string',
        'status' => 'required|string',
    ];

    public function mount()
    {
        $this->date = now()->toDateString();
        $this->status = 'available';
    }

    public function render()
    {
        $surpluses = Surplus::with('dessert')
            ->when($this->search, function ($query) {
                $query->whereHas('dessert', function ($q) {
                    $q->where('name', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('date', 'desc')
            ->paginate(10);

        $desserts = Dessert::orderBy('name')->get();

        return view('livewire.surplus-manager', [
            'surpluses' => $surpluses,
            'desserts' => $desserts,
        ])->layout('components.layouts.app', ['title' => 'Overschotten Beheren']);
    }

    public function store()
    {
        $this->validate([
            'dessert_id' => 'required|exists:desserts,id',
            'date' => 'required|date|after_or_equal:today',
            'total_amount' => 'required|integer|min:1',
            'sale' => 'required|numeric|min:0|max:100',
            'comment' => 'nullable|string',
        ]);

        Surplus::create([
            'dessert_id' => $this->dessert_id,
            'date' => $this->date,
            'expiration_date' => $this->date,
            'total_amount' => $this->total_amount,
            'sale' => $this->sale,
            'status' => 'available',
            'comment' => $this->comment,
        ]);

        $this->resetForm();
        $this->showAddModal = false;
        session()->flash('success', 'Overschot succesvol toegevoegd!');
    }

    public function edit(Surplus $surplus)
    {
        $this->editingSurplus = $surplus;
        $this->dessert_id = $surplus->dessert_id;
        $this->date = $surplus->date->toDateString();
        $this->total_amount = $surplus->total_amount;
        $this->sale = $surplus->sale;
        $this->comment = $surplus->comment;
        $this->status = $surplus->status;

        $this->showEditModal = true;
    }

    public function update()
    {
        $this->validate();

        $this->editingSurplus->update([
            'dessert_id' => $this->dessert_id,
            'date' => $this->date,
            'expiration_date' => $this->date,
            'total_amount' => $this->total_amount,
            'sale' => $this->sale,
            'status' => $this->status,
            'comment' => $this->comment,
        ]);

        $this->resetForm();
        $this->showEditModal = false;
        session()->flash('success', 'Overschot succesvol bijgewerkt!');
    }

    public function destroy(Surplus $surplus)
    {
        $surplus->delete();
        session()->flash('success', 'Overschot succesvol verwijderd!');
    }

    public function resetForm()
    {
        $this->reset(['dessert_id', 'date', 'total_amount', 'sale', 'comment', 'status', 'editingSurplus']);
        $this->date = now()->toDateString();
        $this->status = 'available';
    }
}
