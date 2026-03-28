<?php

namespace App\Livewire;

use App\Models\Dessert;
use App\Models\Surplus;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Route;

class SurplusManager extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public string $mode = 'shop';
    public ?Surplus $editingSurplus = null;
    public array $desserts = [];

    public string $dessertInput = '';
    public string $dateInput = '';
    public string $quantityInput = '';
    public string $discountInput = '';
    public string $notesInput = '';
    public string $statusInput = 'available';
    public string $commentInput = '';

    public function mount()
    {
        // Determine mode based on the current route
        $routeName = Route::currentRouteName();
        $this->mode = ($routeName === 'owner.surpluses.index') ? 'owner' : 'shop';
        $this->desserts = Dessert::orderBy('name')->get()->toArray();
    }

    public function render()
    {
        if ($this->mode === 'shop') {
            $surpluses = Surplus::with('dessert')
                ->where('expiration_date', '>=', now())
                ->where('total_amount', '>', 0)
                ->orderBy('date', 'asc')
                ->get();

            return view('livewire.surplus-manager', [
                'surpluses' => $surpluses,
            ]);
        } else {
            $surpluses = Surplus::with('dessert')
                ->when($this->search, function ($query) {
                    $query->whereHas('dessert', function ($q) {
                        $q->where('name', 'like', "%{$this->search}%");
                    });
                })
                ->orderBy('date', 'desc')
                ->paginate(10);

            return view('livewire.surplus-manager', [
                'surpluses' => $surpluses,
            ]);
        }
    }

    public function store()
    {
        $validated = $this->validate([
            'dessertInput' => 'required|exists:desserts,id',
            'dateInput' => 'required|date|after_or_equal:today',
            'quantityInput' => 'required|integer|min:1',
            'discountInput' => 'required|numeric|min:0|max:100',
            'notesInput' => 'nullable|string',
        ]);

        Surplus::create([
            'dessert_id' => $validated['dessertInput'],
            'date' => $validated['dateInput'],
            'expiration_date' => $validated['dateInput'],
            'total_amount' => $validated['quantityInput'],
            'sale' => $validated['discountInput'],
            'status' => 'available',
            'comment' => $validated['notesInput'],
        ]);

        $this->resetForm();
        session()->flash('success', 'Overschot succesvol toegevoegd.');
    }

    public function edit(Surplus $surplus)
    {
        $this->editingSurplus = $surplus;
        $this->dessertInput = (string) $surplus->dessert_id;
        $this->dateInput = $surplus->date->format('Y-m-d');
        $this->quantityInput = (string) $surplus->total_amount;
        $this->discountInput = (string) $surplus->sale;
        $this->statusInput = $surplus->status;
        $this->commentInput = $surplus->comment ?? '';
    }

    public function update()
    {
        if (!$this->editingSurplus) {
            return;
        }

        $validated = $this->validate([
            'dessertInput' => 'required|exists:desserts,id',
            'dateInput' => 'required|date|after_or_equal:today',
            'quantityInput' => 'required|integer|min:1',
            'discountInput' => 'required|numeric|min:0|max:100',
            'statusInput' => 'required|string',
            'commentInput' => 'nullable|string',
        ]);

        $this->editingSurplus->update([
            'dessert_id' => $validated['dessertInput'],
            'date' => $validated['dateInput'],
            'total_amount' => $validated['quantityInput'],
            'sale' => $validated['discountInput'],
            'status' => $validated['statusInput'],
            'comment' => $validated['commentInput'],
        ]);

        $this->resetForm();
        session()->flash('success', 'Overschot succesvol bijgewerkt.');
    }

    public function delete(Surplus $surplus)
    {
        $surplus->delete();
        session()->flash('success', 'Overschot succesvol verwijderd.');
    }

    public function resetForm()
    {
        $this->dessertInput = '';
        $this->dateInput = '';
        $this->quantityInput = '';
        $this->discountInput = '';
        $this->notesInput = '';
        $this->statusInput = 'available';
        $this->commentInput = '';
        $this->editingSurplus = null;
    }
}

