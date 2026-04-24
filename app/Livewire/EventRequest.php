<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Happening;
use App\Models\Package;
use App\Models\Theme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EventRequest extends Component
{
    public $title = '';
    public $eventDate = '';
    public $personCount = '';
    public $description = '';
    public $theme_id = null;
    public $package_id = null;
    public $onLocation = false;
    public $location = '';
    public $submitted = false;

    public function submitRequest()
    {
        $this->validate($this->rules());

        // Create the happening record
        Happening::create([
            'message' => $this->description,
            'event_date' => $this->eventDate,
            'person_count' => $this->personCount,
            'user_id' => Auth::id(),
            'theme_id' => $this->theme_id,
            'package_id' => $this->package_id,
            'status_id' => \App\Models\State::firstOrCreate(['name' => 'Nieuw'])->id,
            'on_location' => $this->onLocation,
            'location' => $this->onLocation ? $this->location : null,
        ]);

        // Reset form and show success message
        $this->submitted = true;
        $this->reset('title', 'eventDate', 'personCount', 'description', 'theme_id', 'package_id', 'onLocation', 'location');

        // Dispatch success event
        $this->dispatch('event-request-submitted', 'Je evenement aanvraag is succesvol verzonden!');
    }

    public function getThemesProperty()
    {
        return Theme::all();
    }

    public function getPackagesProperty()
    {
        return Package::orderByDesc('is_standard')->orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.event-request')
            ->layout('components.layouts.app', ['title' => 'Evenement Aanvragen']);
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'eventDate' => 'required|date_format:Y-m-d\TH:i',
            'personCount' => 'required|integer|min:1|max:10000',
            'description' => 'required|string|min:10',
            'theme_id' => 'required|exists:themes,id',
            'package_id' => 'required|exists:packages,id',
            'onLocation' => 'boolean',
            'location' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf($this->onLocation),
            ],
        ];
    }
}
