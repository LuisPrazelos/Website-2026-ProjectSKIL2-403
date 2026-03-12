<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Dessert;
use Livewire\Attributes\On;

class ShoppingCart extends Component
{
    public $handleAdds = false;

    // Listen for the add event (conditional logic inside) and the update event (always refresh)
    protected $listeners = [
        'add-to-cart' => 'addToCart',
        'cart-updated' => '$refresh'
    ];

    public function addToCart($desertId, $quantity)
    {
        // Only the designated component handles the session modification
        if (! $this->handleAdds) {
            return;
        }

        $desert = Dessert::find($desertId);

        if (!$desert) {
            return;
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$desertId])) {
            $cart[$desertId]['quantity'] += $quantity;
        } else {
            $cart[$desertId] = [
                'id' => $desert->id,
                'name' => $desert->name,
                'price' => $desert->price,
                'picture_hash' => $desert->picture->hash ?? null,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

        // Notify all components (including this one and the other instance) to update
        $this->dispatch('cart-updated');
    }

    public function getTotalItemsProperty()
    {
        return array_sum(array_column(session()->get('cart', []), 'quantity'));
    }

    public function render()
    {
        return view('livewire.shopping-cart');
    }
}
