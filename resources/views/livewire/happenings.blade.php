<?php

use Livewire\Volt\Component;
use App\Models\Happening;

new class extends Component {
    public $happenings;
    public $selectedHappening = null;

    public function mount()
    {
        $this->happenings = Happening::all();
    }

    public function showDetails($id)
    {
        $this->selectedHappening = Happening::find($id);
    }

    public function closeDetails()
    {
        $this->selectedHappening = null;
    }
}; ?>

<x-layouts.app title="Evenementen Beheer">
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Mijn Evenementen (Happenings)</h1>

        @if($selectedHappening)
            <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200 mb-6 relative">
                <button wire:click="closeDetails" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                    &times; Sluiten
                </button>
                <h2 class="text-xl font-semibold mb-2">{{ $selectedHappening->name ?? 'Naam onbekend' }}</h2>
                <p class="text-gray-600 mb-4">{{ $selectedHappening->description ?? 'Geen beschrijving beschikbaar' }}</p>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <strong>Startdatum:</strong> {{ $selectedHappening->start_date ?? 'N/A' }}
                    </div>
                    <div>
                        <strong>Locatie:</strong> {{ $selectedHappening->location ?? 'N/A' }}
                    </div>
                    <!-- Voeg hier meer velden toe indien nodig -->
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($happenings as $happening)
                <div
                    wire:click="showDetails({{ $happening->id }})"
                    class="bg-white p-4 rounded-lg shadow cursor-pointer hover:shadow-md transition duration-150 border border-gray-100"
                >
                    <h3 class="font-bold text-lg mb-2">{{ $happening->title ?? $happening->name ?? 'Evenement #' . $happening->id }}</h3>
                    <p class="text-gray-500 truncate">{{ $happening->short_description ?? 'Klik voor meer details...' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.app>
