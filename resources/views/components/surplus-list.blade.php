@props(['surplus'])


<div class="bg-white/70 backdrop-blur-md rounded-xl p-4 flex flex-col gap-4 shadow-lg dark:bg-zinc-800/70">

    {{-- Header: Afbeelding, Naam en Details --}}
    <div class="flex gap-4 items-start">
        {{-- Dessert afbeelding --}}
        <img
            src="{{ $surplus->dessert->picture?->hash ?? '/table_pictures/cover.png' }}"
            alt="{{ $surplus->dessert->name }}"
            class="w-24 h-24 object-cover rounded-lg flex-shrink-0">

        <div class="flex-1">
            {{-- Naam --}}
            <h2 class="text-xl font-semibold dark:text-white">
                {{ $surplus->dessert->name }}
            </h2>

            {{-- Beschikbaar en Prijs --}}
            <p class="text-zinc-600 dark:text-zinc-400">
                Aantal beschikbaar: {{ $surplus->total_amount }} | Prijs: €{{ number_format($surplus->dessert->price, 2) }}/liter
            </p>

            {{-- Houdbaar tot op nieuwe regel --}}
            <p class="text-zinc-600 dark:text-zinc-400">
                Houdbaar tot: {{ $surplus->expiration_date->format('d-m-Y') }}
            </p>

        </div>
    </div>

    {{-- Hoeveelheid en Add to cart knop --}}
    <div class="flex items-center gap-3">
        <form action="{{ route('cart.add', $surplus->id) }}" method="POST" class="flex items-center gap-3 w-full">
            @csrf

            {{-- Hoeveelheid input --}}
            <div class="flex items-center gap-2">
                <label for="quantity_{{ $surplus->id }}" class="text-sm text-zinc-600 dark:text-zinc-400">Hoeveelheid:</label>
                <input
                    type="number"
                    id="quantity_{{ $surplus->id }}"
                    name="quantity"
                    min="1"
                    max="{{ $surplus->total_amount }}"
                    value="1"
                    class="w-16 px-2 py-1 border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white"
                >
            </div>

            {{-- Add to cart button --}}
            <button type="submit" class="bg-accent text-white px-4 py-2 rounded-lg hover:bg-amber-700 dark:hover:bg-amber-500 flex items-center gap-2 ml-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Toevoegen
            </button>
        </form>
    </div>

</div>
