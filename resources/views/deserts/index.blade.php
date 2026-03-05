<x-layouts.app title="Desserts Overview">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Header -->
        <div class="relative w-full overflow-hidden rounded-xl bg-cover bg-center h-64"
             style="background-image: url('/pictures/Gebakjes.jpg');">
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                <h1 class="text-4xl font-bold text-white drop-shadow-lg">Desserts Overview</h1>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            @forelse($deserts as $desert)
                <x-dessert-card :desert="$desert" />
            @empty
                <p class="col-span-3 text-center text-gray-500 dark:text-gray-400">{{ __('No desserts available.') }}</p>
            @endforelse
        </div>
    </div>
</x-layouts.app>
