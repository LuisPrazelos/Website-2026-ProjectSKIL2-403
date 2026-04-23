<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <!-- Header -->
    <div class="relative w-full overflow-hidden rounded-xl bg-cover bg-center h-40" style="background-image: url('/pictures/Gebakjes.jpg');">
        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
            <h1 class="text-4xl font-bold text-white drop-shadow-lg">{{ __('Bestellingen beheren') }}</h1>
        </div>
    </div>

    <!-- Main Content -->
    <div class="relative flex flex-1 flex-col rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800 overflow-hidden">

        <!-- Filters Section -->
        <div class="mb-6 space-y-4">
            <div class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1">
                    <input
                        wire:model.live.debounce.500ms="search"
                        type="text"
                        placeholder="{{ __('Zoek een bestellling ID...') }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-0 focus:ring-indigo-500"
                    >
                </div>

                <div class="w-full md:w-auto">
                    <select
                        wire:model.live="status"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-0 focus:ring-indigo-500"
                    >
                        <option value="">{{ __('Alle statussen') }}</option>
                        <option value="pending">{{ __('In afwachting') }}</option>
                        <option value="processing">{{ __('Verwerking') }}</option>
                        <option value="completed">{{ __('Afgerond') }}</option>
                        <option value="cancelled">{{ __('Geannuleerd') }}</option>
                    </select>
                </div>

                <div class="w-full md:w-auto">
                    <input
                        wire:model.live="dateFrom"
                        type="date"
                        placeholder="dd/mm/yyyy"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-0 focus:ring-indigo-500"
                    >
                </div>

                <div class="text-gray-500 dark:text-gray-400">{{ __('tot') }}</div>

                <div class="w-full md:w-auto">
                    <input
                        wire:model.live="dateTo"
                        type="date"
                        placeholder="dd/mm/yyyy"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-0 focus:ring-indigo-500"
                    >
                </div>
            </div>
        </div>

        <div class="flex justify-end mb-4">
            <a href="{{ route('owner.orders.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 dark:bg-gray-700 text-white rounded-md hover:bg-gray-900 dark:hover:bg-gray-600 transition-colors font-medium" wire:navigate>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('Bestelling toevoegen') }}
            </a>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded-md shadow-sm p-4 flex-1">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-gray-200 dark:border-zinc-700">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ __('Ordernummer') }}</th>
                        <th class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ __('Klantnaam') }}</th>
                        <th class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ __('Datum') }}</th>
                        <th class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ __('Afhaaldatum') }}</th>
                        <th class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ __('Afhaaluur') }}</th>
                        <th class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ __('Thema') }}</th>
                        <th class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ __('Totaalbedrag (€)') }}</th>
                        <th class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ __('Status') }}</th>
                        <th class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ __('Acties') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="border-b border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors">
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                <span class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path>
                                    </svg>
                                    #{{ str_pad($order->id, 7, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $order->user->name }}</td>
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $order->placed_at->format('d-m-Y') }}</td>
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $order->pickup_at?->format('d-m-Y') ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $order->pickup_at?->format('H:i') ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $order->theme->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100 font-medium">€ {{ number_format($order->total_price, 2, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                                    @switch($order->status)
                                        @case('pending')
                                            bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                            @break
                                        @case('processing')
                                            bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                            @break
                                        @case('completed')
                                            bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                            @break
                                        @case('cancelled')
                                            bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                            @break
                                        @default
                                            bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300
                                    @endswitch
                                ">
                                    @switch($order->status)
                                        @case('pending')
                                            {{ __('In afwachting') }}
                                            @break
                                        @case('processing')
                                            {{ __('Verwerking') }}
                                            @break
                                        @case('completed')
                                            {{ __('Afgerond') }}
                                            @break
                                        @case('cancelled')
                                            {{ __('Geannuleerd') }}
                                            @break
                                        @default
                                            {{ ucfirst($order->status) }}
                                    @endswitch
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('owner.orders.show', $order) }}" class="inline-flex items-center gap-1 px-3 py-1 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded transition-colors" wire:navigate>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ __('Meer info') }}
                                    </a>

                                    <div class="relative group inline-block">
                                        <button class="inline-flex items-center gap-1 px-3 py-1 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded transition-colors">
                                            {{ __('Pas status aan') }}
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="absolute hidden group-hover:block right-0 mt-0 w-48 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-600 rounded-md shadow-lg z-10">
                                            <button
                                                wire:click="updateStatus({{ $order->id }}, 'pending')"
                                                class="block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-zinc-700 first:rounded-t-md text-gray-900 dark:text-gray-100"
                                            >
                                                {{ __('In afwachting') }}
                                            </button>
                                            <button
                                                wire:click="updateStatus({{ $order->id }}, 'processing')"
                                                class="block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-zinc-700 text-gray-900 dark:text-gray-100"
                                            >
                                                {{ __('Verwerking') }}
                                            </button>
                                            <button
                                                wire:click="updateStatus({{ $order->id }}, 'completed')"
                                                class="block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-zinc-700 text-gray-900 dark:text-gray-100"
                                            >
                                                {{ __('Afgerond') }}
                                            </button>
                                            <button
                                                wire:click="updateStatus({{ $order->id }}, 'cancelled')"
                                                class="block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-zinc-700 last:rounded-b-md text-gray-900 dark:text-gray-100"
                                            >
                                                {{ __('Geannuleerd') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                {{ __('Geen bestellingen gevonden.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="mt-6 flex items-center justify-between">
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Pagina') }} {{ $orders->currentPage() }} {{ __('van') }} {{ $orders->lastPage() }}
                </span>
                <div class="flex gap-2">
                    @if($orders->onFirstPage())
                        <button disabled class="px-4 py-2 text-gray-400 cursor-not-allowed">{{ __('← Vorige') }}</button>
                    @else
                        <button wire:click="previousPage" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded transition-colors">{{ __('← Vorige') }}</button>
                    @endif

                    @if($orders->hasMorePages())
                        <button wire:click="nextPage" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded transition-colors">{{ __('Volgende →') }}</button>
                    @else
                        <button disabled class="px-4 py-2 text-gray-400 cursor-not-allowed">{{ __('Volgende →') }}</button>
                    @endif
                </div>
            </div>
        @endif

        <p class="text-sm text-gray-500 dark:text-gray-400 mt-6">
            {{ __('Wanneer een bestelling wordt geannuleerd, ontvangt de klant automatisch een bevestigingsmail.') }}
        </p>
    </div>
</div>
