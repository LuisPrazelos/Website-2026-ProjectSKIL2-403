<a href="{{ route('cart.index') }}" class="relative inline-block p-2 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline-none">
    <flux:icon.shopping-cart class="w-6 h-6" />
    @if($this->totalItems > 0)
        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
            {{ $this->totalItems }}
        </span>
    @endif
</a>
