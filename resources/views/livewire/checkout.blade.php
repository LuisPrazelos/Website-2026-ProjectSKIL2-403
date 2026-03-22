<div class="p-6 max-w-lg mx-auto">
    <h1 class="text-2xl font-semibold mb-6">Checkout</h1>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
    @endif

    @if(count($cart) > 0)
        <div class="bg-white dark:bg-zinc-900 rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Overzicht</h2>

            <div class="space-y-3 mb-6">
                @foreach($cart as $id => $item)
                    <div class="flex justify-between border-b pb-2">
                        <span>{{ $item['quantity'] }} x {{ $item['dessert_name'] }}</span>
                        <span class="font-semibold">€{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-between text-xl font-bold mb-6">
                <span>Totaal:</span>
                <span>€{{ number_format($totalPrice, 2) }}</span>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Ophaaldatum:</label>
                <input type="date"
                       wire:model="pickupDate"
                       min="{{ now()->toDateString() }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
            </div>

            <button wire:click="placeOrder"
                    wire:loading.attr="disabled"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                <span wire:loading.remove>Bestelling plaatsen</span>
                <span wire:loading>Bezig met verwerken...</span>
            </button>
        </div>
    @else
        <div class="text-center py-12 text-gray-500">
            <p class="text-xl">Je winkelwagen is leeg.</p>
            <a href="{{ route('userSurplusShop.index') }}" class="mt-4 inline-block text-blue-600 hover:underline">Terug naar shop</a>
        </div>
    @endif
</div>
