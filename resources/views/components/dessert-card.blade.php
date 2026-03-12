@props(['desert'])

<div x-data="{ quantity: 1 }" class="rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
    @if($desert->picture)
        <img src="{{ asset('pictures/' . $desert->picture->hash) }}" alt="{{ $desert->name }}" class="mb-4 rounded-md object-cover w-full h-48">
    @endif
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $desert->name }}</h3>
    <p class="text-gray-600 dark:text-gray-400">{{ $desert->description }}</p>
    <p class="text-gray-800 dark:text-gray-200 font-bold mt-2">€{{ number_format($desert->price, 2) }}</p>

    <div class="mt-4 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <button @click="quantity = Math.max(1, quantity - 1)" class="rounded-full bg-gray-200 px-3 py-1 text-gray-700 dark:bg-neutral-700 dark:text-gray-200">-</button>
            <span x-text="quantity" class="px-2"></span>
            <button @click="quantity++" class="rounded-full bg-gray-200 px-3 py-1 text-gray-700 dark:bg-neutral-700 dark:text-gray-200">+</button>
        </div>
        <button @click="$dispatch('add-to-cart', { desertId: {{ $desert->id }}, quantity: quantity })" class="rounded-lg bg-black px-4 py-2 text-white dark:bg-white dark:text-black">
            Add to Cart
        </button>
    </div>
</div>
