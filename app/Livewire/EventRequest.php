<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Happening;
use Illuminate\Support\Facades\Auth;

class EventRequest extends Component
{
    public $title = '';
    public $eventDate = '';
    public $personCount = '';
    public $description = '';
    public $submitted = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'eventDate' => 'required|date_format:Y-m-d\TH:i',
        'personCount' => 'required|integer|min:1|max:10000',
        'description' => 'required|string|min:10',
    ];

    public function submitRequest()
    {
        $this->validate();

        // Create the happening record
        Happening::create([
            'message' => $this->description,
            'event_date' => $this->eventDate,
            'person_count' => $this->personCount,
            'user_id' => Auth::id(),
            'theme_id' => null, // Will be set by admin later
            'status_id' => 1, // Assuming 1 is the "pending" status
        ]);

        // Reset form and show success message
        $this->submitted = true;
        $this->reset('title', 'eventDate', 'personCount', 'description');

        // Dispatch success event
        $this->dispatch('event-request-submitted', 'Je evenement aanvraag is succesvol verzonden!');
    }

    public function render()
    {
        return view('livewire.event-request')
            ->layout('components.layouts.app', ['title' => 'Evenement Aanvragen']);
    }
}

