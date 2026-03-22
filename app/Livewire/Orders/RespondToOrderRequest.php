<?php

namespace App\Livewire\Orders;

use Livewire\Component;
use App\Models\Proposal;
use App\Models\Dessert;

class RespondToOrderRequest extends Component
{
    public $proposal;
    public $pricePerPerson = '';
    public $remarks = '';
    public $selectedDesserts = [];
    public $availableDesserts;
    public $showDessertPopup = false;

    public function mount($id)
    {
        $this->proposal = Proposal::with(['user', 'theme', 'desserts'])->findOrFail($id);

        $this->pricePerPerson = $this->proposal->price_per_person ?? '';
        $this->remarks = $this->proposal->remarks ?? '';

        $this->availableDesserts = Dessert::all();

        // Load existing desserts if any
        if ($this->proposal->desserts) {
             foreach ($this->proposal->desserts as $dessert) {
                $this->selectedDesserts[] = [
                    'id' => $dessert->id,
                    'name' => $dessert->name,
                    'price' => $dessert->price,
                    'quantity' => $dessert->pivot->quantity,
                    'allergies' => $dessert->pivot->allergies,
                ];
            }
        }
    }

    public function showDessertSelector()
    {
        $this->showDessertPopup = true;
    }

    public function hideDessertSelector()
    {
        $this->showDessertPopup = false;
    }

    public function addDessert($dessertId)
    {
        $dessert = $this->availableDesserts->find($dessertId);

        if ($dessert) {
            $found = false;
             // Check if already added
            foreach ($this->selectedDesserts as $item) {
                if ($item['id'] == $dessertId) {
                     $found = true;
                     break;
                }
            }

            if (!$found) {
                 $this->selectedDesserts[] = [
                    'id' => $dessert->id,
                    'name' => $dessert->name,
                    'price' => $dessert->price,
                    'quantity' => 1,
                    'allergies' => '',
                ];
            }
        }
        $this->showDessertPopup = false;
    }

    public function removeDessert($index)
    {
        if (isset($this->selectedDesserts[$index])) {
            unset($this->selectedDesserts[$index]);
            $this->selectedDesserts = array_values($this->selectedDesserts);
        }
    }

    public function updateDessertQuantity($index, $qty)
    {
        if (isset($this->selectedDesserts[$index])) {
            $this->selectedDesserts[$index]['quantity'] = max(1, (int)$qty);
        }
    }

    public function updateDessertAllergies($index, $allergies)
    {
         if (isset($this->selectedDesserts[$index])) {
            $this->selectedDesserts[$index]['allergies'] = $allergies;
         }
    }

    public function getTotalPriceProperty()
    {
        $total = 0;
        foreach ($this->selectedDesserts as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public function saveResponse()
    {
        $this->proposal->update([
            'price_per_person' => $this->pricePerPerson,
            'remarks' => $this->remarks,
        ]);

        // Sync desserts
        $syncData = [];
        foreach ($this->selectedDesserts as $item) {
            $syncData[$item['id']] = [
                'quantity' => $item['quantity'],
                'allergies' => $item['allergies'] ?? null
            ];
        }
        $this->proposal->desserts()->sync($syncData);

        return redirect()->route('owner.respond-order-requests');
    }

    public function render()
    {
        return view('livewire.orders.respond-to-order-request', [
            'proposal' => $this->proposal,
            'totalPrice' => $this->getTotalPriceProperty()
        ])->layout('components.layouts.app', ['title' => 'Beantwoord Voorstel']);
    }
}
