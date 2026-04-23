<div class="rounded-2xl border border-zinc-100 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 flex flex-col h-full transition-all hover:shadow-xl hover:-translate-y-1 group relative">
    @if($surplus->sale > 0)
        <div class="absolute top-6 right-6 z-10 bg-red-600 text-white text-[12px] font-black uppercase px-3 py-1 rounded-lg shadow-xl italic tracking-widest transform rotate-12 group-hover:rotate-0 transition-transform">
            -{{ $surplus->sale }}% KORTING!
        </div>
    @endif

    @if($surplus->dessert->picture)
        <div class="relative overflow-hidden rounded-xl mb-4 h-48 shadow-inner border border-zinc-100 dark:border-zinc-800">
            <img src="{{ asset('pictures/' . $surplus->dessert->picture->hash) }}" alt="{{ $surplus->dessert->name }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
        </div>
    @else
        <div class="mb-4 rounded-xl bg-zinc-50 dark:bg-zinc-800 w-full h-48 flex items-center justify-center text-zinc-300 shadow-inner">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 102-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
    @endif

    <div class="flex-1 px-1">
        <h3 class="text-xl font-black text-zinc-900 dark:text-white uppercase italic tracking-tighter">{{ $surplus->dessert->name }}</h3>
        <p class="text-[10px] text-zinc-400 font-black uppercase tracking-widest mt-1">Overschot van vandaag</p>

        <div class="mt-4 space-y-2">
            <div class="flex items-baseline gap-2">
                @php
                    $discountPrice = $surplus->dessert->price * (1 - ($surplus->sale / 100));
                @endphp
                <span class="text-accent font-black text-3xl italic tracking-tighter">€{{ number_format($discountPrice, 2) }}</span>
                @if($surplus->sale > 0)
                    <span class="text-xs text-zinc-400 font-bold line-through">€{{ number_format($surplus->dessert->price, 2) }}</span>
                @endif
            </div>

            <div class="flex flex-col gap-1">
                <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest text-zinc-500">
                    <span>Beschikbaar:</span>
                    <span class="text-zinc-900 dark:text-white">{{ $surplus->total_amount }} porties</span>
                </div>
                <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest text-zinc-500">
                    <span>Houdbaar tot:</span>
                    <span class="text-zinc-900 dark:text-white">{{ $surplus->expiration_date->format('d-m-Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 space-y-4">
        @if(session()->has('success') && session('success') == 'Overschot toegevoegd aan winkelwagen!')
             <div class="text-[10px] text-green-500 font-black uppercase text-center mb-1 animate-pulse tracking-widest italic">✓ Toegevoegd!</div>
        @endif

        <div class="flex items-center justify-between bg-zinc-50 dark:bg-zinc-800/50 p-2 rounded-2xl border border-zinc-100 dark:border-zinc-800 shadow-inner">
            <div class="flex items-center gap-1 bg-white dark:bg-zinc-900 rounded-xl p-1 shadow-sm border border-zinc-200/50 dark:border-zinc-700 w-full">
                <button wire:click="decrement" class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-200 transition-colors font-black text-xl">-</button>
                <span class="flex-1 text-center font-black text-zinc-900 dark:text-white text-lg italic tracking-tighter">{{ $quantity }}</span>
                <button wire:click="increment" class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-200 transition-colors font-black text-xl">+</button>
            </div>
        </div>

        <button wire:click="addToCart"
                class="w-full rounded-2xl bg-accent px-4 py-5 text-white hover:bg-amber-700 dark:bg-accent dark:text-white dark:hover:bg-amber-500 font-black uppercase italic tracking-[0.2em] transition-all shadow-xl shadow-accent/30 active:scale-[0.96] flex items-center justify-center gap-3 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transition-transform group-hover:rotate-180 duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('Toevoegen') }}
        </button>
    </div>
</div>
