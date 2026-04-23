<div class="group relative flex flex-col overflow-hidden rounded-[2.5rem] bg-white shadow-sm transition-all duration-500 hover:shadow-2xl dark:bg-zinc-900 border border-zinc-100 dark:border-zinc-800">
    <!-- Image Section -->
    <div class="relative aspect-[4/3] overflow-hidden">
        @if($desert->picture)
            <img src="{{ asset('pictures/' . $desert->picture->hash) }}"
                 alt="{{ $desert->name }}"
                 class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-110">
        @else
            <div class="flex h-full w-full items-center justify-center bg-zinc-50 dark:bg-zinc-800">
                <svg class="h-16 w-16 text-zinc-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 102-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif
    </div>

    <!-- Content Section -->
    <div class="flex flex-1 flex-col p-8">
        <div class="mb-3">
            <h3 class="text-2xl font-black leading-none tracking-tight text-zinc-900 dark:text-white uppercase italic">{{ $desert->name }}</h3>
            @if($desert->portion_size > 0)
                <span class="mt-2 inline-block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">
                    {{ $desert->portion_size }} {{ $desert->measurementUnit?->name ?? '' }} portie
                </span>
            @endif
        </div>

        <p class="mb-6 line-clamp-2 text-sm leading-relaxed text-zinc-500 dark:text-zinc-400 font-medium">
            {{ $desert->description }}
        </p>

        <!-- Price Section -->
        <div class="mb-6">
            <span class="text-3xl font-black tracking-tighter text-accent italic">€{{ number_format($desert->price, 2) }}</span>
        </div>

        <div class="mt-auto space-y-4">
            <!-- Modern Quantity Selector -->
            <div class="flex items-center justify-between rounded-2xl bg-zinc-50 p-1.5 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800">
                <button wire:click="decrement"
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-zinc-400 shadow-sm transition-all hover:bg-zinc-50 hover:text-accent dark:bg-zinc-800 dark:text-zinc-500 dark:hover:bg-zinc-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                    </svg>
                </button>
                <span class="text-lg font-black text-zinc-900 dark:text-white tabular-nums">{{ $quantity }}</span>
                <button wire:click="increment"
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-zinc-400 shadow-sm transition-all hover:bg-zinc-50 hover:text-accent dark:bg-zinc-800 dark:text-zinc-500 dark:hover:bg-zinc-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
            </div>

            <!-- Modern CTA Button -->
            <button wire:click="addToCart"
                    class="group relative flex w-full items-center justify-center gap-3 overflow-hidden rounded-2xl {{ session()->has('success') && session('success') == 'Dessert toegevoegd aan winkelwagen!' ? 'bg-green-500' : 'bg-zinc-900 dark:bg-white' }} py-5 text-sm font-black uppercase tracking-widest text-white transition-all duration-300 hover:bg-accent active:scale-[0.98] dark:text-zinc-900 dark:hover:bg-accent dark:hover:text-white shadow-xl shadow-zinc-200 dark:shadow-none italic">

                @if(session()->has('success') && session('success') == 'Dessert toegevoegd aan winkelwagen!')
                    <span class="flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Toegevoegd!
                    </span>
                @else
                    <span wire:loading.remove wire:target="addToCart" class="flex items-center gap-3">
                        Toevoegen
                        <svg class="h-5 w-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </span>
                    <span wire:loading wire:target="addToCart" class="animate-pulse">Even geduld...</span>
                @endif
            </button>
        </div>
    </div>
</div>
