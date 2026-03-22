<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Surplus;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Checkout extends Component
{
    public $cart = [];
    public $totalPrice = 0;
    public $pickupDate;

    public function mount()
    {
        $this->cart = session()->get('cart', []);
        $this->pickupDate = now()->toDateString();
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->totalPrice = 0;
        foreach ($this->cart as $item) {
            $this->totalPrice += $item['price'] * $item['quantity'];
        }
    }

    public function placeOrder()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Je winkelwagen is leeg!');
            return;
        }

        // Maak de order aan
        $order = Order::create([
            'user_id'     => Auth::id(),
            'total_price' => $this->totalPrice,
            'status'      => 'pending',
            'placed_at'   => Carbon::now(),
        ]);

        // Sla elk cart item op
        foreach ($this->cart as $surplusId => $item) {
            $surplus = Surplus::find($surplusId);

            OrderItem::create([
                'orderId'   => $order->id,
                'dessertId' => $surplus ? $surplus->dessert_id : null,
                'amount'    => $item['quantity'],
            ]);
        }

        // Leeg de winkelwagen
        session()->forget('cart');
        $this->cart = [];
        $this->totalPrice = 0;

        return redirect()->route('payment.page')->with('success', 'Bestelling geplaatst!');
    }

    public function render()
    {
        return view('livewire.checkout')
            ->layout('components.layouts.app', ['title' => 'Checkout']);
    }
}
