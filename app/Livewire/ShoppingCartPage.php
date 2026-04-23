<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Dessert;
use App\Models\Surplus;

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

    public function incrementQuantity($cartKey)
    {
        if (isset($this->cartItems[$cartKey])) {
            $this->cartItems[$cartKey]['quantity']++;
            session()->put('cart', $this->cartItems);
            $this->dispatch('cart-updated');
        }
    }

    public function decrementQuantity($cartKey)
    {
        if (isset($this->cartItems[$cartKey])) {
            if ($this->cartItems[$cartKey]['quantity'] > 1) {
                $this->cartItems[$cartKey]['quantity']--;
            } else {
                unset($this->cartItems[$cartKey]);
            }
            session()->put('cart', $this->cartItems);
            $this->dispatch('cart-updated');
        }
    }

    public function removeItem($cartKey)
    {
        if (isset($this->cartItems[$cartKey])) {
            unset($this->cartItems[$cartKey]);
            session()->put('cart', $this->cartItems);
            $this->dispatch('cart-updated');
            session()->flash('success', 'Product verwijderd uit winkelwagen!');
        }
    }

    /**
     * Helper to add a dessert to cart from other components via dispatch
     */
    public function addToCart($id, $type = 'dessert', $quantity = 1)
    {
        $cart = session()->get('cart', []);
        $cartKey = $type . '_' . $id;

        if ($type === 'dessert') {
            $dessert = Dessert::find($id);
            if (!$dessert) return;

            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['quantity'] += $quantity;
            } else {
                $cart[$cartKey] = [
                    'type' => 'dessert',
                    'id' => $dessert->id,
                    'name' => $dessert->name,
                    'quantity' => $quantity,
                    'price' => $dessert->price,
                    'picture_hash' => $dessert->picture?->hash,
                    'portion_size' => $dessert->portion_size,
                    'measurement_unit' => $dessert->measurementUnit?->name,
                ];
            }
        } elseif ($type === 'surplus') {
            $surplus = Surplus::find($id);
            if (!$surplus) return;

            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['quantity'] += $quantity;
            } else {
                $cart[$cartKey] = [
                    'type' => 'surplus',
                    'id' => $surplus->id,
                    'name' => $surplus->dessert->name . ' (Surplus)',
                    'quantity' => $quantity,
                    'price' => $surplus->dessert->price,
                    'discount' => $surplus->sale,
                    'picture_hash' => $surplus->dessert->picture?->hash,
                    'portion_size' => $surplus->dessert->portion_size,
                    'measurement_unit' => $surplus->dessert->measurementUnit?->name,
                ];
            }
        }

        session()->put('cart', $cart);
        $this->updateCart();
        $this->dispatch('cart-updated');
        session()->flash('success', 'Toegevoegd aan winkelwagen!');
    }

    public function getTotalPriceProperty()
    {
        return array_sum(array_map(function ($item) {
            $price = $item['price'];
            if (isset($item['discount']) && $item['discount'] > 0) {
                $price = $price * (1 - ($item['discount'] / 100));
            }
            return $price * $item['quantity'];
        }, $this->cartItems));
    }

    public function render()
    {
        return view('livewire.shopping-cart-page')
            ->layout('components.layouts.app', ['title' => 'Winkelwagen']);
    }
}
