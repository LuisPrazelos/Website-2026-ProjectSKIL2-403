<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Dessert;

class DessertCard extends Component
{
    public $desert;
    public $quantity = 1;

    public function mount(Dessert $desert)
    {
        $this->desert = $desert;
    }

    public function increment()
    {
        $this->quantity++;
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
        $cartKey = 'dessert_' . $this->desert->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $this->quantity;
        } else {
            $cart[$cartKey] = [
                'type' => 'dessert',
                'id' => $this->desert->id,
                'name' => $this->desert->name,
                'quantity' => $this->quantity,
                'price' => $this->desert->price,
                'picture_hash' => $this->desert->picture?->hash,
                'portion_size' => $this->desert->portion_size,
                'measurement_unit' => $this->desert->measurementUnit?->name,
            ];
        }

        session()->put('cart', $cart);
        $this->dispatch('cart-updated');
        session()->flash('success', 'Dessert toegevoegd aan winkelwagen!');
    }

    public function render()
    {
        return view('livewire.dessert-card');
    }
}
