<x-layouts.app.sidebar :title="__('Surplus Shop')">
    <flux:main>
        <flux:page-header :title="__('Available Surplus Desserts')">
            <flux:subheading>{{ __('Browse and buy delicious surplus desserts at a discount!') }}</flux:subheading>
        </flux:page-header>

        <flux:content>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($surpluses as $surplus)
                    <flux:card class="flex flex-col justify-between h-full">
                        <div>
                            <flux:heading size="lg" class="mb-2">{{ $surplus->dessert->name ?? 'Unknown Dessert' }}</flux:heading>
                            <div class="text-sm text-gray-500 mb-4">
                                <p>{{ __('Available until:') }} {{ $surplus->expiration_date->format('d M Y') }}</p>
                                <p>{{ __('Quantity:') }} {{ $surplus->total_amount }}</p>
                            </div>
                            @if($surplus->comment)
                                <p class="text-gray-600 italic mb-4">"{{ $surplus->comment }}"</p>
                            @endif
                        </div>

                        <div class="mt-auto flex items-center justify-between">
                            <span class="text-xl font-bold text-green-600">€{{ number_format($surplus->sale, 2) }}</span>
                            <flux:button variant="primary">{{ __('Buy Now') }}</flux:button>
                        </div>
                    </flux:card>
                @empty
                    <div class="col-span-full text-center py-12">
                        <flux:heading level="2" class="text-gray-500">{{ __('No surplus desserts available at the moment.') }}</flux:heading>
                        <p class="text-gray-400 mt-2">{{ __('Check back later!') }}</p>
                    </div>
                @endforelse
            </div>
        </flux:content>
    </flux:main>
</x-layouts.app.sidebar>
