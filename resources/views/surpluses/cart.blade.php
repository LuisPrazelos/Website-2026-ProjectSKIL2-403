<x-layouts.app title="Winkelwagen">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-6">
        <!-- Header with Background Image -->
        <div class="relative w-full overflow-hidden rounded-xl bg-cover bg-center h-64"
             style="background-image: url('/pictures/Gebakjes.jpg');">
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                <h1 class="text-4xl font-bold text-white drop-shadow-lg">Winkelwagen</h1>
            </div>
            <div class="absolute top-4 right-4">
                <a href="{{ route('deserts.index') }}" class="bg-white p-2 rounded-full shadow-lg flex items-center justify-center hover:bg-gray-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Cart Content -->
        <div class="relative flex flex-1 flex-col rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800 overflow-hidden">

            @if(session('cart') && count(session('cart')) > 0)
                <div class="flex-1 overflow-y-auto">
                    <!-- Cart Overview -->
                    <div class="max-w-xl mx-auto bg-white dark:bg-zinc-900 rounded-xl shadow-md p-6 mt-8 border border-zinc-100 dark:border-zinc-700">
                        <h2 class="text-xl font-bold text-center mb-6 text-zinc-900 dark:text-white">Uw Bestelling</h2>
                        <div class="space-y-4 mb-6">
                            @php $total = 0; @endphp
                            @foreach(session('cart', []) as $cartKey => $item)
                                @php
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total += $subtotal;
                                @endphp
                                <div class="flex items-center justify-between border-b dark:border-zinc-700 pb-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-zinc-900 dark:text-white">{{ $item['name'] }}</span>
                                        <span class="text-sm text-zinc-500">€{{ number_format($item['price'], 2) }} per stuk</span>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <form action="{{ route('cart.update', $cartKey) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <div class="flex items-center border rounded-lg overflow-hidden dark:border-zinc-600">
                                                <button type="button" class="bg-zinc-100 dark:bg-zinc-800 px-3 py-1 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition" onclick="document.getElementById('qty_{{ $cartKey }}').stepDown(); this.form.submit();">-</button>
                                                <input type="number" id="qty_{{ $cartKey }}" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-12 text-center border-none bg-transparent focus:ring-0 dark:text-white" readonly>
                                                <button type="button" class="bg-zinc-100 dark:bg-zinc-800 px-3 py-1 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition" onclick="document.getElementById('qty_{{ $cartKey }}').stepUp(); this.form.submit();">+</button>
                                            </div>
                                        </form>
                                        <div class="text-right min-w-[80px]">
                                            <span class="font-bold text-zinc-900 dark:text-white">€{{ number_format($subtotal, 2) }}</span>
                                        </div>
                                        <form action="{{ route('cart.remove', $cartKey) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between items-center text-2xl font-bold mb-8 px-2 text-zinc-900 dark:text-white">
                            <span>Totaal:</span>
                            <span>€{{ number_format($total, 2) }}</span>
                        </div>

                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg mb-8 border border-blue-100 dark:border-blue-800">
                            <p class="text-sm text-blue-700 dark:text-blue-300 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                In de volgende stap kunt u de ophaaldatum en het exacte tijdstip selecteren.
                            </p>
                        </div>

                        <a href="{{ route('checkout') }}" class="block">
                            <button class="w-full bg-blue-600 text-white py-4 rounded-xl text-lg font-bold hover:bg-blue-700 transition shadow-lg flex items-center justify-center gap-2">
                                <span>Doorgaan naar Afrekenen</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </a>
                    </div>
                </div>

            @else
                <!-- Empty Cart Message -->
                <div class="flex flex-col items-center justify-center h-full text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h2 class="text-2xl font-bold text-gray-600 mb-2">Je winkelwagen is leeg</h2>
                    <p class="text-gray-500 mb-6">Voeg wat lekkere desserts toe om te beginnen!</p>
                    <a href="{{ route('deserts.index') }}" class="px-6 py-3 bg-accent text-white rounded-lg hover:bg-amber-700 transition font-medium">
                        Bekijk desserts
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-layouts.app>
