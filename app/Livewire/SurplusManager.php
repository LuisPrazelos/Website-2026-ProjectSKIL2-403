<?php

namespace App\Livewire;

use App\Models\Dessert;
use App\Models\Surplus;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
<<<<<<< WB-Dessert-UurEnPortie
use Carbon\Carbon;
=======
use Illuminate\Support\Facades\Route;
>>>>>>> main

class SurplusManager extends Component
{
    use WithPagination;

    #[Url]
<<<<<<< WB-Dessert-UurEnPortie
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
=======
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
>>>>>>> main
    }

    public function render()
    {
<<<<<<< WB-Dessert-UurEnPortie
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
=======
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
>>>>>>> main
    }

    public function store()
    {
<<<<<<< WB-Dessert-UurEnPortie
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
=======
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
>>>>>>> main
    }

    public function edit(Surplus $surplus)
    {
        $this->editingSurplus = $surplus;
<<<<<<< WB-Dessert-UurEnPortie
        $this->dessert_id = $surplus->dessert_id;
        $this->date = $surplus->date->toDateString();
        $this->total_amount = $surplus->total_amount;
        $this->sale = $surplus->sale;
        $this->comment = $surplus->comment;
        $this->status = $surplus->status;

        $this->showEditModal = true;
=======
        $this->dessertInput = (string) $surplus->dessert_id;
        $this->dateInput = $surplus->date->format('Y-m-d');
        $this->quantityInput = (string) $surplus->total_amount;
        $this->discountInput = (string) $surplus->sale;
        $this->statusInput = $surplus->status;
        $this->commentInput = $surplus->comment ?? '';
>>>>>>> main
    }

    public function update()
    {
<<<<<<< WB-Dessert-UurEnPortie
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
=======
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
>>>>>>> main
    }

    public function resetForm()
    {
<<<<<<< WB-Dessert-UurEnPortie
        $this->reset(['dessert_id', 'date', 'total_amount', 'sale', 'comment', 'status', 'editingSurplus']);
        $this->date = now()->toDateString();
        $this->status = 'available';
    }
}
=======
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

>>>>>>> main
