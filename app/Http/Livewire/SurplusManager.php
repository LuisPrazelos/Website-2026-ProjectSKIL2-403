<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Surplus;

class SurplusManager extends Component
{
    public $surpluses;

    public function mount()
    {
        $this->surpluses = Surplus::with('dessert')->get();
    }

    public function render()
    {
        return view('livewire.surplus-manager', [
            'surpluses' => $this->surpluses,
        ]);
    }

    // Voeg hier CRUD-methodes toe (create, update, delete)
}

