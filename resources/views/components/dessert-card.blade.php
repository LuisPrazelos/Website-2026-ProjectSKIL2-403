@props(['desert'])

<div x-data="{ quantity: 1 }" class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-800 flex flex-col h-full">
    @if($desert->picture)
        <img src="{{ asset('pictures/' . $desert->picture->hash) }}" alt="{{ $desert->name }}" class="mb-4 rounded-md object-cover w-full h-48">
    @else
        <div class="mb-4 rounded-md bg-zinc-100 dark:bg-zinc-700 w-full h-48 flex items-center justify-center text-zinc-400">
            {{ __('No Image') }}
        </div>
    @endif

    <div class="flex-1">
        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $desert->name }}</h3>
        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">{{ $desert->description }}</p>

        <div class="mt-3 flex flex-col gap-1">
            <p class="text-zinc-800 dark:text-zinc-200 font-bold text-lg">€{{ number_format($desert->price, 2) }}</p>
            @if($desert->portion_size > 0)
                <p class="text-xs text-zinc-500 dark:text-zinc-400 italic">
                    Per portie: {{ $desert->portion_size }} {{ $desert->measurementUnit?->name ?? '' }}
                </p>
            @endif
        </div>
    </div>

    <div class="mt-6">
        <div class="flex flex-col gap-3">
            <div class="flex items-center justify-between bg-zinc-50 dark:bg-zinc-900/50 p-2 rounded-lg border border-zinc-100 dark:border-zinc-700">
                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Aantal porties:</span>
                <div class="flex items-center gap-3">
                    <button @click="quantity = Math.max(1, quantity - 1)" class="w-8 h-8 flex items-center justify-center rounded-full bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition shadow-sm">-</button>
                    <span x-text="quantity" class="w-6 text-center font-bold text-zinc-800 dark:text-zinc-100"></span>
                    <button @click="quantity++" class="w-8 h-8 flex items-center justify-center rounded-full bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition shadow-sm">+</button>
                </div>
            </div>

            <form action="{{ route('cart.addDessert', $desert->id) }}" method="POST">
                @csrf
                <input type="hidden" name="quantity" :value="quantity">
                <button type="submit" class="w-full rounded-lg bg-accent px-4 py-2.5 text-white hover:bg-amber-700 dark:bg-accent dark:text-white dark:hover:bg-amber-500 font-bold transition shadow-md flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    {{ __('Toevoegen') }}
                </button>
            </form>
        </div>
    </div>
</div>
