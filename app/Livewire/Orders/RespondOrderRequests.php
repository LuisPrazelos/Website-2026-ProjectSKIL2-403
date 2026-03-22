<?php

namespace App\Livewire\Orders;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Proposal;

class RespondOrderRequests extends Component
{
    use WithPagination;

    public $searchQuery = '';

    public function updated($property)
    {
        if ($property === 'searchQuery') {
            $this->resetPage();
        }
    }

    public function render()
    {
        $query = Proposal::with(['user', 'theme'])
            ->latest('delivery_date');

        if ($this->searchQuery) {
            $query->where(function ($q) {
                $q->whereHas('user', function ($uq) {
                    $uq->where('first_name', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('last_name', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('email', 'like', '%' . $this->searchQuery . '%');
                })
                ->orWhere('message', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('id', 'like', '%' . $this->searchQuery . '%');
            });
        }

        $proposals = $query->paginate(10);

        return view('livewire.orders.respond-order-requests', [
            'proposals' => $proposals
        ])
        ->layout('components.layouts.app', ['title' => 'Reageren op aanvragen']);
    }
}
