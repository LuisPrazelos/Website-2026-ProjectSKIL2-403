<?php

namespace App\Livewire\Surpluses;

use App\Models\Surplus;
use Livewire\Component;
use Livewire\WithPagination;

class SurplusList extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Surplus::with('dessert')->orderBy('date', 'desc');

        if (strlen($this->search) > 0) {
            $query->whereHas('dessert', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $surpluses = $query->paginate($this->perPage);

        return view('livewire.surpluses.surplus', [
            'surpluses' => $surpluses,
        ]);
    }
}

