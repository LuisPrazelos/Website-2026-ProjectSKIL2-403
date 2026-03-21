<x-layouts.app title="Feest op tafel">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Header with Background Image -->
        <div class="relative w-full overflow-hidden rounded-xl bg-cover bg-center h-64" style="background-image: url('/pictures/Gebakjes.jpg');">
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                <h1 class="text-4xl font-bold text-white drop-shadow-lg">Feest op tafel</h1>
            </div>
        </div>

        <!-- Recent Reviews Section (Takes up remaining space) -->
        <div class="relative flex flex-1 flex-col rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800 overflow-hidden">
            <div class="flex-1 overflow-y-auto mb-4">
                <livewire:recent-reviews />
            </div>

            <!-- Bottom Left Button -->
            <div class="mt-auto">
                <a href="#" class="inline-flex items-center justify-center rounded-lg bg-accent px-4 py-2 text-sm font-medium text-white hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 dark:bg-accent dark:text-white dark:hover:bg-amber-500">
                    Alle reviews
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
