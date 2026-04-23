<?php

namespace App\Livewire\Orders;

use Livewire\Component;
use App\Models\Happening;
use App\Models\Dessert;
use App\Mail\HappeningRemarksNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class RespondToOrderRequest extends Component
{
    public $happening;
    public $pricePerPerson = '';
    public $remarks = '';
    public $selectedDesserts = [];
    public $availableDesserts;
    public $showDessertPopup = false;

    public function mount($id)
    {
        $this->happening = Happening::with(['user', 'theme', 'package', 'desserts'])->findOrFail($id);

        $this->pricePerPerson = $this->happening->price_per_person ?? '';
        $this->remarks = $this->happening->remarks ?? '';

        $this->availableDesserts = Dessert::all();

        // Load existing desserts if any
        if ($this->happening->desserts) {
             foreach ($this->happening->desserts as $dessert) {
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
        $this->happening->update([
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
        $this->happening->desserts()->sync($syncData);

        // Send email notification
        $this->sendRemarksEmail($this->happening);

        return redirect()->route('owner.respond-order-requests');
    }

    /**
     * Helper-functie om de opmerkingen-e-mail te versturen.
     */
    private function sendRemarksEmail(Happening $happening): void
    {
        $happening->load('user');

        if (!$happening->user?->email) {
            Log::warning('No user or email found for happening ' . $happening->id . '. Email not sent.');
            return;
        }

        try {
            // =========================================================================
            // TIJDELIJK VOOR TESTEN: Stuur de mail altijd naar het geconfigureerde adres
            // In een productie-omgeving zou je hier $happening->user->email gebruiken.
            // =========================================================================
            $testRecipient = config('mail.mailers.smtp.username');

            Mail::to($testRecipient)
                ->send(new HappeningRemarksNotification($happening, $this->remarks));

            Log::info('Email sent for happening ' . $happening->id . ' to test recipient ' . $testRecipient);

        } catch (\Exception $e) {
            Log::error('Failed to send email for happening ' . $happening->id . ': ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.orders.respond-to-order-request', [
            'happening' => $this->happening,
            'totalPrice' => $this->getTotalPriceProperty()
        ])->layout('components.layouts.app', ['title' => 'Beantwoord Voorstel']);
    }
}
