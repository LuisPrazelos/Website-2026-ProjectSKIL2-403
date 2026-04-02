<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\PriceEvolution;
use Carbon\Carbon;

class ShoppingList extends Component
{
    public $period = 'week';
    public $shoppingList = [];
    public $totalEstimatedCost = 0;

    public function updatedPeriod()
    {
        $this->generateList();
    }

    public function mount()
    {
        $this->generateList();
    }

    public function generateList()
    {
        $query = Order::with(['items.dessert.ingredients.measurementUnit']);

        $now = Carbon::now();

        if ($this->period === 'today') {
            $query->whereDate('placed_at', $now->toDateString());
        } elseif ($this->period === 'week') {
            $query->whereBetween('placed_at', [
                $now->copy()->startOfWeek(),
                $now->copy()->endOfWeek(),
            ]);
        } elseif ($this->period === 'month') {
            $query->whereMonth('placed_at', $now->month)
                ->whereYear('placed_at', $now->year);
        }
        // If period is 'all', we don't apply any date filter

        $orders = $query->get();
        $ingredientsNeeded = [];

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                // Skip if item doesn't have a dessert linked
                if (!$item->dessert) continue;

                $quantity = $item->amount;

                // Loop through ingredients of the dessert
                foreach ($item->dessert->ingredients as $ingredient) {
                    $ingredientId = $ingredient->id;

                    // Access amount from pivot table
                    $amountPerDessert = $ingredient->pivot->amount ?? 0;
                    $totalAmount = $amountPerDessert * $quantity;

                    if (!isset($ingredientsNeeded[$ingredientId])) {
                        // Find the latest price for this ingredient
                        $latestPriceEntry = PriceEvolution::where('ingredientId', $ingredientId)
                            ->orderBy('date', 'desc')
                            ->first();

                        $pricePerUnit = $latestPriceEntry ? $latestPriceEntry->price : 0;
                        $unitName = $ingredient->measurementUnit ? $ingredient->measurementUnit->name : 'stuks';

                        $ingredientsNeeded[$ingredientId] = [
                            'name' => $ingredient->name,
                            'amount' => 0,
                            'unit' => $unitName,
                            'price_per_unit' => $pricePerUnit,
                            'total_cost' => 0,
                        ];
                    }

                    $ingredientsNeeded[$ingredientId]['amount'] += $totalAmount;
                }
            }
        }

        // Calculate total costs for each ingredient and the final total
        $this->totalEstimatedCost = 0;
        foreach ($ingredientsNeeded as &$item) {
            $item['total_cost'] = $item['amount'] * $item['price_per_unit'];
            $this->totalEstimatedCost += $item['total_cost'];
        }

        // Sort by name and convert to indexed array for the view
        $this->shoppingList = collect($ingredientsNeeded)->sortBy('name')->values()->all();
    }

    public function render()
    {
        return view('livewire.shopping-list')
            ->layout('components.layouts.app', ['title' => 'Boodschappenlijst']);
    }
}
