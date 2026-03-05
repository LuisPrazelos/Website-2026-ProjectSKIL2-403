@props(['surplus'])

<div class="bg-white/70 backdrop-blur-md rounded-xl p-4 flex gap-4 items-center shadow-lg">

    {{-- Dessert afbeelding --}}
    <img
        src="{{ $surplus->dessert->picture?->hash ?? '/table_pictures/cover.png' }}"
        alt="{{ $surplus->dessert->name }}"
        class="w-24 h-24 object-cover rounded-lg">


    <div class="flex-1">

        {{-- Naam --}}
        <h2 class="text-xl font-semibold">
            {{ $surplus->dessert->name }}
        </h2>

        {{-- Beschikbaar --}}
        <p class="text-gray-600">
            Aantal beschikbaar: {{ $surplus->total_amount }}
        </p>

        {{-- Prijs --}}
        <p class="text-gray-600">
            Prijs: €{{ number_format($surplus->sale, 2) }}/liter
        </p>

        {{-- Houdbaar --}}
        <p class="text-gray-600">
            Houdbaar tot: {{ $surplus->expiration_date }}
        </p>

        {{-- Quantity controls --}}
        <div class="flex items-center gap-2 mt-3">
            <button class="bg-gray-300 px-3 py-1 rounded">-</button>
            <span>1</span>
            <button class="bg-gray-300 px-3 py-1 rounded">+</button>
        </div>
    </div>

    {{-- Info knop --}}
    <button class="text-blue-600 text-xl">
        ℹ
    </button>

</div>
