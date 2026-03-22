<?php

namespace App\Livewire\Orders;

use Livewire\Component;
use App\Models\Proposal;

class ViewOrderRequest extends Component
{
    public $proposal;

    public function mount($id)
    {
        $this->proposal = Proposal::with(['user', 'theme'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.orders.view-order-request', [
            'proposal' => $this->proposal
        ])->layout('components.layouts.app', ['title' => 'Voorstel Details']);
    }
}
