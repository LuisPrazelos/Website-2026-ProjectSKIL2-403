<?php

namespace App\Livewire\Orders;

use Livewire\Component;
use App\Models\Happening;

class ViewOrderRequest extends Component
{
    public $happening;

    public function mount($id)
    {
        $this->happening = Happening::with(['user', 'theme', 'desserts'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.orders.view-order-request', [
            'happening' => $this->happening
        ])->layout('components.layouts.app', ['title' => 'Evenement Details']);
    }
}
