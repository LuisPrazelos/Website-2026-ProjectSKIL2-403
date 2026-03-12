<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Dessert;

class ShoppingCartPage extends Component
{
    public $cartItems = [];

    protected $listeners = ['cart-updated' => 'updateCart'];

    public function mount()
    {
        $this->updateCart();
    }

    public function updateCart()
    {
        $this->cartItems = session()->get('cart', []);
    }

    public function incrementQuantity($desertId)
    {
        if (isset($this->cartItems[$desertId])) {
            $this->cartItems[$desertId]['quantity']++;
            session()->put('cart', $this->cartItems);
            $this->dispatch('cart-updated');
        }
    }

    public function decrementQuantity($desertId)
    {
        if (isset($this->cartItems[$desertId])) {
            if ($this->cartItems[$desertId]['quantity'] > 1) {
                $this->cartItems[$desertId]['quantity']--;
            } else {
                unset($this->cartItems[$desertId]);
            }
            session()->put('cart', $this->cartItems);
            $this->dispatch('cart-updated');
        }
    }

    public function removeItem($desertId)
    {
        if (isset($this->cartItems[$desertId])) {
            unset($this->cartItems[$desertId]);
            session()->put('cart', $this->cartItems);
            $this->dispatch('cart-updated');
        }
    }

    public function getTotalItemsProperty()
    {
        return array_sum(array_column($this->cartItems, 'quantity'));
    }

    public function getTotalPriceProperty()
    {
        return array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $this->cartItems));
    }

    public function render()
    {
        return view('livewire.shopping-cart-page')
            ->layout('components.layouts.app', ['title' => 'Shopping Cart']);
    }
}
