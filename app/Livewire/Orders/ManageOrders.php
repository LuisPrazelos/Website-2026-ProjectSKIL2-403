<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class ManageOrders extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public int $perPage = 10;

    protected $queryString = ['search', 'status', 'dateFrom', 'dateTo'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'status', 'dateFrom', 'dateTo');
        $this->resetPage();
    }

    public function updateStatus(Order $order, string $newStatus): void
    {
        $order->update(['status' => $newStatus]);
        $this->dispatch('order-updated', 'Status bijgewerkt!');
    }

    public function createTestOrder(): void
    {
        // Create a simple test order
        Order::create([
            'user_id' => auth()->id(),
            'total_price' => 0,
            'status' => 'pending',
            'placed_at' => now(),
        ]);

        $this->dispatch('order-created', 'Bestelling aangemaakt!');
    }

    public function render()
    {
        $query = Order::with(['user', 'theme'])
            ->orderBy('placed_at', 'desc');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($uq) {
                        $uq->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if (!empty($this->status)) {
            $query->where('status', $this->status);
        }

        if (!empty($this->dateFrom)) {
            $query->whereDate('placed_at', '>=', $this->dateFrom);
        }

        if (!empty($this->dateTo)) {
            $query->whereDate('placed_at', '<=', $this->dateTo);
        }

        $orders = $query->paginate($this->perPage);

        return view('livewire.orders.manage-orders', [
            'orders' => $orders,
            'statuses' => ['pending', 'processing', 'completed', 'cancelled'],
        ])->layout('components.layouts.app', ['title' => 'Bestellingen Beheren']);
    }
}
