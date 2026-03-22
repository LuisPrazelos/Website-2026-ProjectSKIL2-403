<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Log;

class OrderDetail extends Component
{
    #[Locked]
    public int $orderId;

    public ?array $orderData = null;
    public ?array $userData = null;
    public ?array $itemsData = null;
    public ?string $theme = null;

    public function mount(Order $order)
    {
        $this->orderId = $order->id;

        // Load and store order data as array to avoid serialization issues
        $this->orderData = [
            'id' => $order->id,
            'user_id' => $order->user_id,
            'total_price' => $order->total_price,
            'status' => $order->status,
            'availability_id' => $order->availability_id,
            'placed_at' => $order->placed_at,
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at,
            'theme' => $order->theme,
        ];

        $this->theme = $order->theme;

        // Load user data
        $order->load('user');
        if ($order->user) {
            $this->userData = [
                'id' => $order->user->id,
                'name' => $order->user->name,
                'email' => $order->user->email,
            ];
        }

        // Load items with dessert relationship
        try {
            Log::info('Attempting to load items for order ' . $order->id);
            $order->load('items.dessert');

            Log::info('Items loaded: ' . $order->items->count());

            // Convert to array data
            $this->itemsData = $order->items->map(function ($item) {
                $dessertPrice = $item->dessert?->price ?? 0;
                $quantity = $item->amount ?? 1;

                return [
                    'id' => $item->id,
                    'quantity' => $quantity,
                    'price' => $dessertPrice,
                    'dessertName' => $item->dessert?->name ?? 'Verwijderd item',
                    'dessertId' => $item->dessertId,
                ];
            })->toArray();

            Log::info('Items data prepared: ' . count($this->itemsData));
        } catch (\Exception $e) {
            // If loading items fails, try direct query
            Log::error('Failed to load order items via relation: ' . $e->getMessage());
            $this->itemsData = [];

            // Fallback: try direct query
            Log::info('Trying direct query for items of order ' . $order->id);
            $items = OrderItem::where('orderId', $order->id)->with('dessert')->get();
            Log::info('Direct query result: ' . $items->count() . ' items found');

            if ($items->count() > 0) {
                $this->itemsData = $items->map(function ($item) {
                    $dessertPrice = $item->dessert?->price ?? 0;
                    $quantity = $item->amount ?? 1;

                    return [
                        'id' => $item->id,
                        'quantity' => $quantity,
                        'price' => $dessertPrice,
                        'dessertName' => $item->dessert?->name ?? 'Verwijderd item',
                        'dessertId' => $item->dessertId,
                    ];
                })->toArray();

                Log::info('Items prepared from fallback: ' . count($this->itemsData));
            }
        }
    }

    public function render()
    {
        return view('livewire.orders.order-detail', [
            'orderData' => $this->orderData,
            'userData' => $this->userData,
            'itemsData' => $this->itemsData,
            'theme' => $this->theme,
        ])->layout('components.layouts.app', ['title' => 'Bestellingsdetails']);
    }
}

