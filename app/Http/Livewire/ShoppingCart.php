<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Dessert;
use App\Models\Surplus;

class ShoppingCart extends Component
{
    public $cart = [];

    public function mount()
    {
        $this->cart = session()->get('cart', []);
    }

    public function render()
    {
        return view('livewire.shopping-cart', ['cart' => $this->cartItems]);
    }

    public function addDessert($dessertId, $quantity = 1)
    {
        $dessert = Dessert::with('measurementUnit')->find($dessertId);
        if (!$dessert) return;

        $cartKey = 'dessert_' . $dessert->id;
        $quantity = max(1, (int)$quantity);

        if (isset($this->cart[$cartKey])) {
            $this->cart[$cartKey]['quantity'] += $quantity;
        } else {
            $this->cart[$cartKey] = [
                'type' => 'dessert',
                'id' => $dessert->id,
                'quantity' => $quantity,
            ];
        }
        session()->put('cart', $this->cart);
    }

    public function addSurplus($surplusId, $quantity = 1)
    {
        $surplus = Surplus::with('dessert.measurementUnit')->find($surplusId);
        if (!$surplus) return;

        $cartKey = 'surplus_' . $surplus->id;
        $quantity = max(1, min((int)$quantity, $surplus->total_amount));

        if (isset($this->cart[$cartKey])) {
            $this->cart[$cartKey]['quantity'] += $quantity;
            $this->cart[$cartKey]['quantity'] = min($this->cart[$cartKey]['quantity'], $surplus->total_amount);
        } else {
            $this->cart[$cartKey] = [
                'type' => 'surplus',
                'id' => $surplus->id,
                'quantity' => $quantity,
            ];
        }
        session()->put('cart', $this->cart);
    }

    public function remove($cartKey)
    {
        unset($this->cart[$cartKey]);
        session()->put('cart', $this->cart);
    }

    public function update($cartKey, $quantity)
    {
        $quantity = (int)$quantity;
        if ($quantity <= 0) {
            $this->remove($cartKey);
            return;
        }
        if (isset($this->cart[$cartKey])) {
            if (str_starts_with($cartKey, 'surplus_')) {
                $surplusId = (int) str_replace('surplus_', '', $cartKey);
                $surplus = Surplus::find($surplusId);
                if ($surplus) {
                    $quantity = min($quantity, $surplus->total_amount);
                }
            }
            $this->cart[$cartKey]['quantity'] = $quantity;
            session()->put('cart', $this->cart);
        }
    }

    public function getCartItemsProperty()
    {
        $detailedCart = [];
        foreach ($this->cart as $cartKey => $item) {
            if ($item['type'] === 'dessert') {
                $dessert = Dessert::with('measurementUnit')->find($item['id']);
                if ($dessert) {
                    $detailedCart[$cartKey] = [
                        'type' => 'dessert',
                        'id' => $dessert->id,
                        'name' => $dessert->name,
                        'quantity' => $item['quantity'],
                        'price' => $dessert->price,
                        'portion_size' => $dessert->portion_size,
                        'measurement_unit' => $dessert->measurementUnit?->name,
                        'picture_hash' => $dessert->picture_hash ?? null,
                    ];
                }
            } elseif ($item['type'] === 'surplus') {
                $surplus = Surplus::with('dessert.measurementUnit')->find($item['id']);
                if ($surplus) {
                    $detailedCart[$cartKey] = [
                        'type' => 'surplus',
                        'id' => $surplus->id,
                        'name' => $surplus->dessert->name . ' (Surplus)',
                        'quantity' => $item['quantity'],
                        'price' => $surplus->dessert->price,
                        'discount' => $surplus->sale,
                        'available_amount' => $surplus->total_amount,
                        'portion_size' => $surplus->dessert->portion_size,
                        'measurement_unit' => $surplus->dessert->measurementUnit?->name,
                        'picture_hash' => $surplus->dessert->picture_hash ?? null,
                    ];
                }
            }
        }
        return $detailedCart;
    }

    public function getTotalPriceProperty()
    {
        $total = 0;
        foreach ($this->cartItems as $item) {
            $price = $item['price'];
            if (isset($item['discount']) && $item['discount'] > 0) {
                $price = $price * (1 - $item['discount'] / 100);
            }
            $total += $price * $item['quantity'];
        }
        return $total;
    }

    public function incrementQuantity($cartKey)
    {
        if (isset($this->cart[$cartKey])) {
            $quantity = $this->cart[$cartKey]['quantity'] + 1;
            $this->update($cartKey, $quantity);
        }
    }

    public function decrementQuantity($cartKey)
    {
        if (isset($this->cart[$cartKey])) {
            $quantity = $this->cart[$cartKey]['quantity'] - 1;
            $this->update($cartKey, $quantity);
        }
    }

    public function removeItem($cartKey)
    {
        $this->remove($cartKey);
    }
}
