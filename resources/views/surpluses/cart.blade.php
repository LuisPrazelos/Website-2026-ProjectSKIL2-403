<x-layouts.app title="Winkelwagen">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-6">
        <!-- Header with Background Image -->
        <div class="relative w-full overflow-hidden rounded-xl bg-cover bg-center h-64"
             style="background-image: url('/pictures/Gebakjes.jpg');">
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                <h1 class="text-4xl font-bold text-white drop-shadow-lg">Winkelwagen</h1>
            </div>
            <div class="absolute top-4 right-4">
                <a href="{{ route('userSurplusShop.index') }}" class="bg-white p-2 rounded-full shadow-lg flex items-center justify-center hover:bg-gray-100 transition">
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
                    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md p-6 mt-8">
                        <h2 class="text-xl font-bold text-center mb-4">Overzicht</h2>
                        <div class="space-y-2 mb-4">
                            @php $total = 0; @endphp
                            @foreach(session('cart', []) as $surplus_id => $item)
                                @php
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total += $subtotal;
                                @endphp
                                <div class="flex items-center justify-between border-b pb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold">€{{ number_format($subtotal, 2) }}</span>
                                        <span class="text-gray-700">- {{ $item['quantity'] }} x {{ $item['dessert_name'] ?? $item['name'] ?? 'Unknown Item' }}</span>
                                    </div>
                                    <form action="{{ route('cart.update', $surplus_id) }}" method="POST" class="flex items-center gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" class="bg-gray-200 px-2 py-1 rounded" onclick="document.getElementById('qty_{{ $surplus_id }}').stepDown(); this.form.submit();">-</button>
                                        <input type="number" id="qty_{{ $surplus_id }}" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-10 text-center border rounded" onchange="this.form.submit();">
                                        <button type="button" class="bg-gray-200 px-2 py-1 rounded" onclick="document.getElementById('qty_{{ $surplus_id }}').stepUp(); this.form.submit();">+</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-2xl font-bold text-center mb-6">€{{ number_format($total, 2) }}</div>
                        <div class="mb-4">
                            <label for="pickup_date" class="block text-sm font-medium text-gray-700 mb-1">Kies een ophaaldatum:</label>
                            <input type="date" id="pickup_date" name="pickup_date" class="mt-1 block w-full px-1.5 py-2 text-base border border-black focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
                        </div>
                        <a href="{{ route('checkout') }}" class="flex justify-center mb-2">
                            <button class="bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-bold hover:bg-blue-700 transition">Betalen</button>
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
                    <p class="text-gray-500 mb-6">Voeg wat overschotten toe om te beginnen met winkelen!</p>
                    <a href="{{ route('userSurplusShop.index') }}" class="px-6 py-3 bg-accent text-white rounded-lg hover:bg-amber-700 transition font-medium">
                        Terug naar shop
                    </a>
                </div>
            @endif

        </div>
    </div>
    @include('components.layouts.app.footer')
</x-layouts.app>
