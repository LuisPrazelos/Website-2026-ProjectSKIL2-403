<div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
    <!-- Header with Background Image -->
    <div class="relative w-full overflow-hidden rounded-2xl bg-cover bg-center h-72 shadow-xl"
         style="background-image: url('/pictures/Gebakjes.jpg');">
        <div class="absolute inset-0 bg-black/40 flex items-center justify-center backdrop-blur-[2px]">
            <div class="text-center">
                <h1 class="text-5xl font-black text-white drop-shadow-2xl uppercase tracking-tighter italic">Winkelwagen</h1>
                <p class="text-white/90 font-medium mt-2">Controleer uw heerlijke selectie</p>
            </div>
        </div>
        <div class="absolute top-6 right-6">
            <a href="{{ route('deserts.index') }}" class="bg-white/90 backdrop-blur p-3 rounded-full shadow-lg flex items-center justify-center hover:bg-white transition-all hover:scale-110 active:scale-95 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-zinc-800 group-hover:text-accent transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1">
        @if(session()->has('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg shadow-sm animate-fade-in-down">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 font-bold uppercase tracking-wide">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(count($cartItems) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items List -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cartItems as $cartKey => $item)
                        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-4 shadow-sm border border-zinc-100 dark:border-zinc-800 flex items-center gap-4 transition-all hover:shadow-md hover:border-accent/20">
                            <!-- Product Image -->
                            <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-xl bg-zinc-100 dark:bg-zinc-800 border border-zinc-100 dark:border-zinc-700">
                                @if(isset($item['picture_hash']))
                                    <img class="h-full w-full object-cover" src="{{ asset('pictures/' . $item['picture_hash']) }}" alt="{{ $item['name'] }}">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-zinc-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-black text-zinc-900 dark:text-white truncate uppercase italic tracking-tight">{{ $item['name'] }}</h3>
                                @if(isset($item['portion_size']))
                                    <p class="text-xs text-zinc-500 font-bold">{{ $item['portion_size'] }} {{ $item['measurement_unit'] ?? '' }} per portie</p>
                                @endif
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="text-accent font-black text-xl">€{{ number_format($item['price'] * (1 - (isset($item['discount']) ? $item['discount']/100 : 0)), 2) }}</span>
                                    @if(isset($item['discount']) && $item['discount'] > 0)
                                        <span class="text-xs text-red-500 font-bold line-through">€{{ number_format($item['price'], 2) }}</span>
                                        <span class="bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded font-black">-{{ $item['discount'] }}%</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Quantity Selector -->
                            <div class="flex flex-col items-end gap-3">
                                <div class="flex items-center bg-zinc-100 dark:bg-zinc-800 rounded-xl p-1 border border-zinc-200 dark:border-zinc-700">
                                    <button wire:click="decrementQuantity('{{ $cartKey }}')" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4" />
                                        </svg>
                                    </button>
                                    <span class="w-10 text-center font-black text-zinc-900 dark:text-white">{{ $item['quantity'] }}</span>
                                    <button wire:click="incrementQuantity('{{ $cartKey }}')" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>
                                <button wire:click="removeItem('{{ $cartKey }}')" class="text-[10px] uppercase font-black text-zinc-400 hover:text-red-500 transition-colors flex items-center gap-1 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Verwijderen
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Summary Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 shadow-md border border-zinc-100 dark:border-zinc-800 sticky top-6">
                        <h2 class="text-xl font-black text-zinc-900 dark:text-white mb-6 uppercase italic tracking-tight border-b dark:border-zinc-800 pb-2">Overzicht</h2>

                        <div class="space-y-3 mb-8">
                            <div class="flex justify-between text-zinc-600 dark:text-zinc-400 font-bold uppercase text-xs">
                                <span>Artikelen:</span>
                                <span>{{ array_sum(array_column($cartItems, 'quantity')) }}</span>
                            </div>
                            <div class="flex justify-between text-zinc-900 dark:text-white text-2xl font-black uppercase italic italic tracking-tighter pt-4 border-t dark:border-zinc-800">
                                <span>Totaal:</span>
                                <span class="text-accent">€{{ number_format($this->totalPrice, 2) }}</span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <a href="{{ route('checkout') }}" class="block">
                                <button class="w-full bg-accent text-white py-4 rounded-xl text-lg font-black uppercase italic tracking-widest hover:bg-amber-700 transition-all shadow-lg shadow-accent/20 active:scale-[0.98] flex items-center justify-center gap-3">
                                    <span>Betalen</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </button>
                            </a>
                            <p class="text-[10px] text-zinc-400 text-center font-bold uppercase">In de volgende stap kiest u uw ophaalmoment</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart State -->
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-16 shadow-sm border border-zinc-100 dark:border-zinc-800 text-center">
                <div class="bg-zinc-50 dark:bg-zinc-800/50 w-32 h-32 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-black text-zinc-900 dark:text-white mb-4 uppercase italic tracking-tight">Je winkelwagen is leeg</h2>
                <p class="text-zinc-500 mb-10 font-medium max-w-md mx-auto">Het lijkt erop dat je nog geen keuzes hebt gemaakt. Onze desserts wachten op je!</p>
                <a href="{{ route('deserts.index') }}" class="inline-flex items-center gap-3 px-10 py-4 bg-accent text-white rounded-xl font-black uppercase italic tracking-widest hover:bg-amber-700 transition-all shadow-xl shadow-accent/20 active:scale-95">
                    Bekijk desserts
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        @endif
    </div>
</div>
