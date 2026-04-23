<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Surplus;

class SurplusCard extends Component
{
    public $surplus;
    public $quantity = 1;

    public function mount(Surplus $surplus)
    {
        $this->surplus = $surplus;
    }

    public function increment()
    {
        if ($this->quantity < $this->surplus->total_amount) {
            $this->quantity++;
        }
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        $cart = session()->get('cart', []);
        $cartKey = 'surplus_' . $this->surplus->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $this->quantity;
            $cart[$cartKey]['quantity'] = min($cart[$cartKey]['quantity'], $this->surplus->total_amount);
        } else {
            $cart[$cartKey] = [
                'type' => 'surplus',
                'id' => $this->surplus->id,
                'name' => $this->surplus->dessert->name . ' (Surplus)',
                'quantity' => $this->quantity,
                'price' => $this->surplus->dessert->price,
                'discount' => $this->surplus->sale,
                'picture_hash' => $this->surplus->dessert->picture?->hash,
                'portion_size' => $this->surplus->dessert->portion_size,
                'measurement_unit' => $this->surplus->dessert->measurementUnit?->name,
            ];
        }

        session()->put('cart', $cart);
        $this->dispatch('cart-updated');
        session()->flash('success', 'Overschot toegevoegd aan winkelwagen!');
    }

    public function render()
    {
        return view('livewire.surplus-card');
    }
}
