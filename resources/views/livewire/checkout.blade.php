<div class="p-6 max-w-4xl mx-auto">
    <div class="bg-white dark:bg-zinc-900 rounded-3xl shadow-2xl overflow-hidden border border-zinc-100 dark:border-zinc-800 transition-all hover:shadow-accent/5">
        <!-- Header -->
        <div class="bg-accent p-8 text-white relative overflow-hidden">
             <!-- Background Texture -->
            <div class="absolute inset-0 opacity-10 mix-blend-overlay">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <pattern id="pattern" width="40" height="40" patternUnits="userSpaceOnUse" patternTransform="rotate(45)">
                        <rect width="100%" height="100%" fill="none" />
                        <path d="M0 40h40M40 0V40" stroke="currentColor" stroke-width="2" />
                    </pattern>
                    <rect width="100%" height="100%" fill="url(#pattern)" />
                </svg>
            </div>

            <div class="relative flex flex-col items-center justify-center text-center">
                <h1 class="text-4xl font-black flex items-center gap-4 uppercase italic tracking-tighter">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 118 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Bestelling afronden
                </h1>
                <p class="mt-2 text-white/90 font-black uppercase tracking-[0.2em] text-[10px]">Controleer uw bestelling en kies een ophaalmoment.</p>
            </div>
        </div>

        <div class="p-8 lg:p-12">
            @if(session('error'))
                <div class="bg-red-50 dark:bg-red-900/10 border-l-8 border-red-500 p-6 mb-8 rounded-xl shadow-sm animate-shake">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-red-700 dark:text-red-400 font-black uppercase tracking-wide">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(count($cart) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Left: Review Items -->
                    <div>
                        <h2 class="text-xl font-black text-zinc-900 dark:text-white mb-6 uppercase italic tracking-tight border-b-2 dark:border-zinc-800 pb-2 flex items-center gap-2">
                             <span class="bg-accent text-white w-6 h-6 rounded-lg text-[10px] flex items-center justify-center font-black">01</span>
                             Overzicht
                        </h2>
                        <div class="space-y-6 max-h-[400px] overflow-y-auto pr-4 scrollbar-thin scrollbar-thumb-zinc-200">
                            @foreach($cart as $id => $item)
                                <div class="flex justify-between items-start group">
                                    <div class="flex flex-col">
                                        <span class="font-black text-zinc-800 dark:text-zinc-200 uppercase italic tracking-tight group-hover:text-accent transition-colors">{{ $item['quantity'] }}x {{ $item['name'] }}</span>
                                        @if(isset($item['portion_size']))
                                            <span class="text-[10px] text-zinc-400 font-black uppercase tracking-widest">{{ $item['portion_size'] }} {{ $item['measurement_unit'] ?? '' }} per portie</span>
                                        @endif
                                    </div>
                                    <span class="font-black text-zinc-900 dark:text-white">€{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-8 pt-6 border-t-4 border-zinc-100 dark:border-zinc-800 flex justify-between items-center text-3xl font-black text-zinc-900 dark:text-white italic tracking-tighter">
                            <span>TOTAAL:</span>
                            <span class="text-accent underline decoration-accent/30 decoration-8 underline-offset-4">€{{ number_format($totalPrice, 2) }}</span>
                        </div>
                    </div>

                    <!-- Right: Pickup Details -->
                    <div>
                        <h3 class="text-xl font-black text-zinc-900 dark:text-white mb-6 uppercase italic tracking-tight border-b-2 dark:border-zinc-800 pb-2 flex items-center gap-2">
                             <span class="bg-accent text-white w-6 h-6 rounded-lg text-[10px] flex items-center justify-center font-black">02</span>
                             Afhalen
                        </h3>

                        <div class="space-y-8 bg-zinc-50 dark:bg-zinc-800/50 p-8 rounded-3xl border border-zinc-100 dark:border-zinc-700 shadow-inner">
                            <div>
                                <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] mb-3">Datum van afhalen:</label>
                                <div class="relative">
                                    <input type="date"
                                           wire:model="pickupDate"
                                           min="{{ now()->toDateString() }}"
                                           class="w-full rounded-2xl border-zinc-200 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white shadow-sm focus:border-accent focus:ring-accent py-4 px-5 transition-all font-bold">
                                </div>
                                @error('pickupDate') <span class="text-red-500 text-[10px] mt-2 block font-black uppercase tracking-widest animate-pulse">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] mb-3">Uur van afhalen:</label>
                                <div class="relative">
                                    <input type="time"
                                           wire:model="pickupTime"
                                           class="w-full rounded-2xl border-zinc-200 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white shadow-sm focus:border-accent focus:ring-accent py-4 px-5 transition-all font-bold">
                                </div>
                                @error('pickupTime') <span class="text-red-500 text-[10px] mt-2 block font-black uppercase tracking-widest animate-pulse">{{ $message }}</span> @enderror
                            </div>
                            <div class="bg-white/50 dark:bg-zinc-900/50 p-4 rounded-xl border border-dashed border-zinc-300 dark:border-zinc-700">
                                <p class="text-[10px] text-zinc-500 font-bold flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    WIJ ZORGEN DAT UW BESTELLING VERS KLAARSTAAT OP DIT TIJDSTIP.
                                </p>
                            </div>
                        </div>

                        <div class="mt-12">
                             <button wire:click="placeOrder"
                                    wire:loading.attr="disabled"
                                    class="w-full bg-accent text-white py-6 rounded-3xl font-black text-2xl uppercase italic tracking-widest hover:bg-amber-700 transition-all shadow-2xl shadow-accent/40 active:scale-[0.97] disabled:opacity-50 flex items-center justify-center gap-4 group">
                                <span wire:loading.remove class="flex items-center gap-4">
                                    Bestelling plaatsen
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 transition-transform group-hover:translate-x-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                                <span wire:loading class="flex items-center gap-4">
                                    <svg class="animate-spin h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    VERWERKEN...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-20 text-zinc-500">
                    <div class="mb-10 bg-zinc-50 dark:bg-zinc-800/50 w-40 h-40 rounded-full flex items-center justify-center mx-auto shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <p class="text-4xl font-black text-zinc-700 dark:text-zinc-300 mb-4 uppercase italic tracking-tighter">Uw winkelwagen is leeg.</p>
                    <p class="mb-10 font-medium">U heeft nog geen producten geselecteerd voor afhaling.</p>
                    <a href="{{ route('deserts.index') }}" class="px-12 py-5 bg-accent text-white rounded-2xl font-black uppercase italic tracking-widest hover:bg-amber-700 transition-all shadow-xl shadow-accent/20">
                        Terug naar shop
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
