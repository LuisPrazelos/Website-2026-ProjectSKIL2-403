<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <!-- Header -->
    <div class="relative w-full overflow-hidden rounded-xl bg-cover bg-center h-40" style="background-image: url('/pictures/Gebakjes.jpg');">
        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white drop-shadow-lg">{{ __('Bestellingen beheren') }}</h1>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 px-4">
        <a href="{{ route('owner.orders.index') }}" class="hover:text-gray-900 dark:hover:text-gray-200" wire:navigate>
            {{ __('Bestellingen beheren') }}
        </a>
        <span>></span>
        <span>{{ __('Order') }} #{{ str_pad($orderData['id'], 7, '0', STR_PAD_LEFT) }} - {{ $userData['name'] ?? 'Onbekend' }}</span>
    </div>

    <!-- Main Content -->
    <div class="relative flex flex-1 flex-col rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800 overflow-auto">

        <!-- Title -->
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                {{ __('Bestelling') }} #{{ str_pad($orderData['id'], 7, '0', STR_PAD_LEFT) }}
            </h2>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                {{ __('Volledige overzicht van de bestelling van') }} {{ $userData['name'] ?? 'Onbekend' }}.
            </p>
        </div>

        <!-- Order Information -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 bg-gray-50 dark:bg-zinc-900 p-6 rounded-lg">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('Klantnaam') }}
                </label>
                <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $userData['name'] ?? 'Onbekend' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('Klant-ID (persoonId)') }}
                </label>
                <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $userData['id'] ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('Bestellatum') }}
                </label>
                <p class="text-gray-900 dark:text-gray-100 font-medium">{{ \Carbon\Carbon::parse($orderData['placed_at'])->format('d-m-Y') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('Afhaalatum') }}
                </label>
                <p class="text-gray-900 dark:text-gray-100 font-medium">
                    {{ !empty($orderData['pickup_at']) ? \Carbon\Carbon::parse($orderData['pickup_at'])->format('d-m-Y') : __('Niet ingesteld') }}
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('Afhaaluur') }}
                </label>
                <p class="text-gray-900 dark:text-gray-100 font-medium">
                    {{ !empty($orderData['pickup_at']) ? \Carbon\Carbon::parse($orderData['pickup_at'])->format('H:i') : __('Niet ingesteld') }}
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('Totaalbedrag') }}
                </label>
                <p class="text-gray-900 dark:text-gray-100 font-medium">€ {{ number_format($orderData['total_price'], 2, ',', '.') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('Betaalstatus') }}
                </label>
                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                    @switch($orderData['status'])
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
                    @switch($orderData['status'])
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
                            {{ ucfirst($orderData['status']) }}
                    @endswitch
                </span>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('Thema') }}
                </label>
                <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $theme ?? '-' }}</p>
            </div>
        </div>

        <!-- Dessert Details -->
        @if(count($itemsData) > 0)
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('Dessertdetails') }}
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900">
                            <tr>
                                <th class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ __('Dessert') }}</th>
                                <th class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ __('Aantal') }}</th>
                                <th class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ __('Prijs per stuk (€)') }}</th>
                                <th class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ __('Subtotaal (€)') }}</th>
                            </tr>
                        </thead>
                        <tbody class="border-b border-gray-200 dark:border-zinc-700">
                            @foreach($itemsData as $item)
                                <tr class="border-b border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800">
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                        <span class="flex items-center gap-2">
                                            <span>{{ $item['dessertName'] }}</span>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $item['quantity'] }} {{ $item['unit'] }}</td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">€ {{ number_format($item['price'], 2, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100 font-medium">
                                        € {{ number_format($item['quantity'] * $item['price'], 2, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-gray-100">
                                    {{ __('Totaal') }}
                                </td>
                                <td class="px-4 py-3 font-bold text-lg text-gray-900 dark:text-gray-100">
                                    € {{ number_format($orderData['total_price'], 2, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endif

        <!-- Status & Notes Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Order Status -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('Bestellingsstatus') }}
                </h3>
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="checkbox" @if($orderData['status'] === 'pending' || $orderData['status'] === 'processing' || $orderData['status'] === 'completed') checked @endif disabled class="rounded" />
                        <span class="ml-2 text-gray-900 dark:text-gray-100">{{ __('Voorstel ontvangen') }}</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" @if($orderData['status'] === 'processing' || $orderData['status'] === 'completed') checked @endif disabled class="rounded" />
                        <span class="ml-2 text-gray-900 dark:text-gray-100">{{ __('Bereid') }}</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" @if($orderData['status'] === 'completed') checked @endif disabled class="rounded" />
                        <span class="ml-2 text-gray-900 dark:text-gray-100">{{ __('Opgehaald/geleverd') }}</span>
                    </label>
                </div>
            </div>

            <!-- Internal Notes -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('Interne opmerkingen') }}
                </h3>
                <textarea
                    disabled
                    class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-gray-50 dark:bg-zinc-900 text-gray-900 dark:text-gray-100 focus:outline-none resize-none"
                    rows="6"
                    placeholder="{{ __('Bijv. glutenvrij deeg gebruiken, extra doos voorzien...') }}"
                ></textarea>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 mt-8">
            <a href="{{ route('owner.orders.index') }}" class="px-6 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500 transition-colors font-medium" wire:navigate>
                {{ __('Annuleren') }}
            </a>
            <button class="px-6 py-2 bg-gray-800 dark:bg-gray-700 text-white rounded-md hover:bg-gray-900 dark:hover:bg-gray-600 transition-colors font-medium">
                {{ __('Opslaan') }}
            </button>
        </div>
    </div>
</div>
