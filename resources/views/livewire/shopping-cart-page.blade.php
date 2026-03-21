<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-gray-900 dark:text-white">Shopping Cart</h1>

    @if(count($cartItems) > 0)
        <div class="bg-white dark:bg-zinc-800 shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                <thead class="bg-gray-50 dark:bg-zinc-900">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Product</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quantity</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                    @foreach($cartItems as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if(isset($item['picture_hash']))
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('pictures/' . $item['picture_hash']) }}" alt="{{ $item['name'] }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-200"></div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item['name'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">€{{ number_format($item['price'], 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="decrementQuantity({{ $item['id'] }})" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">-</button>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $item['quantity'] }}</span>
                                    <button wire:click="incrementQuantity({{ $item['id'] }})" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">+</button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">€{{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="removeItem({{ $item['id'] }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Remove</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8 flex justify-end">
            <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-md w-full max-w-md">
                <div class="flex justify-between mb-4">
                    <span class="text-lg font-medium text-gray-900 dark:text-white">Total</span>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">€{{ number_format($this->totalPrice, 2) }}</span>
                </div>
                <a href="{{ route('checkout') }}" class="w-full bg-black text-white py-3 px-4 rounded-md hover:bg-gray-800 dark:bg-white dark:text-black dark:hover:bg-gray-200 transition duration-150 ease-in-out text-center">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <flux:icon.shopping-cart class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Your cart is empty</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Start adding some delicious desserts!</p>
            <div class="mt-6">
                <a href="{{ route('deserts.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-black hover:bg-gray-800 dark:bg-white dark:text-black dark:hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Browse Desserts
                </a>
            </div>
        </div>
    @endif
</div>
