<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Surplus;
use App\Models\Dessert;
use App\Models\Theme;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Checkout extends Component
{
    public $cart = [];
    public $totalPrice = 0;
    public $pickupDate;
    public $pickupTime; // Added pickupTime property
    public $theme_id;

    protected $rules = [
        'pickupDate' => 'required|date|after_or_equal:today',
        'pickupTime' => 'required', // Validation for pickupTime
        'theme_id' => 'required|exists:themes,id',
    ];

    public function mount()
    {
        $this->cart = session()->get('cart', []);
        $this->pickupDate = now()->toDateString();
        $this->pickupTime = now()->addHour()->format('H:00'); // Default to 1 hour from now, rounded
        $this->theme_id = Theme::query()->orderBy('name')->value('id');
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->totalPrice = 0;
        foreach ($this->cart as $item) {
            $price = $item['price'];
            if (isset($item['discount']) && $item['discount'] > 0) {
                $price = $price * (1 - ($item['discount'] / 100));
            }
            $this->totalPrice += $price * $item['quantity'];
        }
    }

    public function placeOrder()
    {
        $this->validate();

        if (empty($this->cart)) {
            session()->flash('error', 'Je winkelwagen is leeg!');
            return;
        }

        // Combine date and time
        try {
            $pickupAt = Carbon::parse($this->pickupDate . ' ' . $this->pickupTime);
        } catch (\Exception $e) {
            session()->flash('error', 'Ongeldige ophaaldatum of -tijd.');
            return;
        }

        // Maak de order aan
        $order = Order::create([
            'user_id'     => Auth::id(),
            'theme_id'    => $this->theme_id,
            'total_price' => $this->totalPrice,
            'status'      => 'pending',
            'placed_at'   => Carbon::now(),
            'pickup_at'   => $pickupAt, // Save the combined pickup datetime
        ]);

        // Sla elk cart item op
        foreach ($this->cart as $cartKey => $item) {
            $dessertId = null;

            if ($item['type'] === 'dessert') {
                $dessertId = $item['id'];
            } elseif ($item['type'] === 'surplus') {
                $surplus = Surplus::find($item['id']);
                if ($surplus) {
                    $dessertId = $surplus->dessert_id;
                    // Reduction logic for surplus
                    $surplus->total_amount -= $item['quantity'];
                    $surplus->save();
                }
            }

            OrderItem::create([
                'orderId'   => $order->id,
                'dessertId' => $dessertId,
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
        return view('livewire.checkout', [
            'themes' => Theme::orderBy('name')->get(),
        ])
            ->layout('components.layouts.app', ['title' => 'Checkout']);
    }
}
