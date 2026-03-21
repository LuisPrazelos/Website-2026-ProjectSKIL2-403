@props(['desert'])

<div x-data="{ quantity: 1 }" class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
    @if($desert->picture)
        <img src="{{ asset('pictures/' . $desert->picture->hash) }}" alt="{{ $desert->name }}" class="mb-4 rounded-md object-cover w-full h-48">
    @endif
    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $desert->name }}</h3>
    <p class="text-zinc-600 dark:text-zinc-400">{{ $desert->description }}</p>
    <p class="text-zinc-800 dark:text-zinc-200 font-bold mt-2">€{{ number_format($desert->price, 2) }}</p>

    <div class="mt-4 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <button @click="quantity = Math.max(1, quantity - 1)" class="rounded-full bg-zinc-200 px-3 py-1 text-zinc-700 dark:bg-zinc-700 dark:text-zinc-200 hover:bg-zinc-300 dark:hover:bg-zinc-600">-</button>
            <span x-text="quantity" class="px-2 font-medium text-zinc-700 dark:text-zinc-200"></span>
            <button @click="quantity++" class="rounded-full bg-zinc-200 px-3 py-1 text-zinc-700 dark:bg-zinc-700 dark:text-zinc-200 hover:bg-zinc-300 dark:hover:bg-zinc-600">+</button>
        </div>
        <button @click="$dispatch('add-to-cart', { desertId: {{ $desert->id }}, quantity: quantity })" class="rounded-lg bg-accent px-4 py-2 text-white hover:bg-amber-700 dark:bg-accent dark:text-white dark:hover:bg-amber-500 font-medium">
            Add to Cart
        </button>
    </div>
</div>
