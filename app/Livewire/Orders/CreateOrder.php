<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Dessert;
use Livewire\Component;

class CreateOrder extends Component
{
    public ?int $selectedUserId = null;
    public string $orderDate = '';
    public string $pickupDate = '';
    public string $status = 'pending';
    public array $items = [];
    public float $totalPrice = 0;
    public string $theme = '';
    public string $location = '';

    public $users = [];
    public $desserts = [];

    public function mount()
    {
        $this->users = User::where('id', '!=', auth()->id())->get();
        $this->desserts = Dessert::all();
        $this->orderDate = now()->format('Y-m-d');
        $this->pickupDate = now()->addDay()->format('Y-m-d');
        $this->items = [
            0 => ['dessertId' => null, 'quantity' => 1, 'price' => 0]
        ];
    }

    public function addItem()
    {
        $this->items[] = ['dessertId' => null, 'quantity' => 1, 'price' => 0];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotal();
    }

    public function updateDessert($index, $dessertId)
    {
        if ($dessertId) {
            $dessert = Dessert::find($dessertId);
            if ($dessert) {
                $this->items[$index]['dessertId'] = $dessertId;
                $this->items[$index]['price'] = $dessert->price;
            }
        }
        $this->calculateTotal();
    }

    public function updateQuantity($index, $quantity)
    {
        $this->items[$index]['quantity'] = max(1, (int)$quantity);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->totalPrice = 0;
        foreach ($this->items as $item) {
            if ($item['dessertId']) {
                $this->totalPrice += ($item['price'] ?? 0) * $item['quantity'];
            }
        }
    }

    public function saveOrder()
    {
        $this->validate([
            'selectedUserId' => 'required|exists:users,id',
            'orderDate' => 'required|date',
            'pickupDate' => 'required|date',
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        // Filter out empty items
        $validItems = array_filter($this->items, fn($item) => !empty($item['dessertId']));

        if (empty($validItems)) {
            session()->flash('error', 'Voeg minstens één dessert toe');
            return;
        }

        // Create order
        $order = Order::create([
            'user_id' => $this->selectedUserId,
            'total_price' => $this->totalPrice,
            'status' => $this->status,
            'placed_at' => $this->orderDate . ' ' . now()->format('H:i:s'),
        ]);

        // Create order items
        foreach ($validItems as $item) {
            OrderItem::create([
                'orderId' => $order->id,
                'dessertId' => $item['dessertId'],
                'amount' => $item['quantity'],
            ]);
        }

        session()->flash('success', 'Bestelling succesvol aangemaakt!');
        return redirect()->route('owner.orders.index');
    }

    public function render()
    {
        return view('livewire.orders.create-order')
            ->layout('components.layouts.app', ['title' => 'Nieuwe Bestelling']);
    }
}

