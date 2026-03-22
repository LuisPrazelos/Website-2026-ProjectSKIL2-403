<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <!-- Header -->
    <div class="relative w-full overflow-hidden rounded-xl bg-cover bg-center h-40" style="background-image: url('/pictures/Gebakjes.jpg');">
        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white drop-shadow-lg">{{ __('Nieuwe bestelling') }}</h1>
                <p class="text-white text-lg mt-2">{{ __('Vul hieronder alle gegevens in om een nieuwe bestelling aan te maken.') }}</p>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 px-4">
        <a href="{{ route('owner.orders.index') }}" class="hover:text-gray-900 dark:hover:text-gray-200" wire:navigate>
            {{ __('Bestellingen beheren') }}
        </a>
        <span>></span>
        <span>{{ __('Nieuwe bestelling') }}</span>
    </div>

    <!-- Main Content -->
    <div class="relative flex flex-1 flex-col rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800 overflow-auto">

        @if(session()->has('error'))
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md">
                <p class="text-red-800 dark:text-red-300">{{ session('error') }}</p>
            </div>
        @endif

        <form wire:submit="saveOrder" class="space-y-6">
            <!-- Orderinformatie -->
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Orderinformatie') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Klantnaam -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Klantnaam') }}
                            <span class="text-red-500">*</span>
                        </label>
                        <select
                            wire:model="selectedUserId"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="">{{ __('Selecteer een klant') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedUserId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Betaalstatus -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Betaalstatus') }}
                            <span class="text-red-500">*</span>
                        </label>
                        <select
                            wire:model="status"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="pending">{{ __('Niet betaald') }}</option>
                            <option value="processing">{{ __('In behandeling') }}</option>
                            <option value="completed">{{ __('Betaald') }}</option>
                            <option value="cancelled">{{ __('Geannuleerd') }}</option>
                        </select>
                    </div>

                    <!-- Bestellatum -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Bestellatum') }}
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            wire:model="orderDate"
                            type="date"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                        @error('orderDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Afhaalatum -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Afhaalatum') }}
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            wire:model="pickupDate"
                            type="date"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                        @error('pickupDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status bestelling -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Status bestelling') }}
                        </label>
                        <select
                            wire:model="status"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="pending">{{ __('In behandeling') }}</option>
                            <option value="processing">{{ __('In voorbereiding') }}</option>
                            <option value="completed">{{ __('Klaar') }}</option>
                        </select>
                    </div>

                    <!-- Thema (optioneel) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Thema (optioneel)') }}
                        </label>
                        <input
                            wire:model="theme"
                            type="text"
                            placeholder="{{ __('Bijv: Verjaardag, Bruiloft') }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                    </div>

                    <!-- Locatie (optioneel) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Locatie (optioneel)') }}
                        </label>
                        <input
                            wire:model="location"
                            type="text"
                            placeholder="{{ __('Bijv: Sint-Niklaasstraat 13') }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                    </div>
                </div>
            </div>

            <!-- Dessertdetails -->
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Dessertdetails') }}</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900">
                            <tr>
                                <th class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ __('Dessert') }}</th>
                                <th class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ __('Aantal') }}</th>
                                <th class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ __('Allergiëen') }}</th>
                                <th class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ __('Subtotaal (€)') }}</th>
                                <th class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ __('Acties') }}</th>
                            </tr>
                        </thead>
                        <tbody class="border-b border-gray-200 dark:border-zinc-700">
                            @foreach($items as $index => $item)
                                <tr class="border-b border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800">
                                    <td class="px-4 py-3">
                                        <select
                                            wire:change="updateDessert({{ $index }}, $event.target.value)"
                                            class="w-full px-2 py-1 border border-gray-300 dark:border-zinc-600 rounded bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 text-sm"
                                        >
                                            <option value="">{{ __('Selecteer dessert') }}</option>
                                            @foreach($desserts as $dessert)
                                                <option value="{{ $dessert->id }}" @selected($item['dessertId'] == $dessert->id)>
                                                    {{ $dessert->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input
                                            type="number"
                                            wire:change="updateQuantity({{ $index }}, $event.target.value)"
                                            value="{{ $item['quantity'] }}"
                                            min="1"
                                            class="w-20 px-2 py-1 border border-gray-300 dark:border-zinc-600 rounded bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 text-sm"
                                        >
                                    </td>
                                    <td class="px-4 py-3">
                                        <input
                                            type="text"
                                            placeholder="{{ __('Bijv. gluten.') }}"
                                            class="w-full px-2 py-1 border border-gray-300 dark:border-zinc-600 rounded bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 text-sm"
                                        >
                                    </td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100 font-medium">
                                        € {{ number_format(($item['price'] ?? 0) * $item['quantity'], 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <button
                                            type="button"
                                            wire:click="removeItem({{ $index }})"
                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-gray-100">
                                    {{ __('Totaalbedrag') }}
                                </td>
                                <td class="px-4 py-3 font-bold text-lg text-gray-900 dark:text-gray-100">
                                    € {{ number_format($totalPrice, 2, ',', '.') }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <button
                    type="button"
                    wire:click="addItem"
                    class="mt-4 inline-flex items-center gap-2 px-4 py-2 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded transition-colors font-medium"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.5H9a1 1 0 100 2h1.5v3.5a1 1 0 102 0v-3.5H13a1 1 0 100-2h-1.5V7z" clip-rule="evenodd"></path>
                    </svg>
                    {{ __('Dessert toevoegen') }}
                </button>
            </div>

            <!-- Status & Opmerkingen -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Bestellingsstatus') }}
                    </h3>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" disabled class="rounded" />
                            <span class="ml-2 text-gray-900 dark:text-gray-100">{{ __('Voorstel ontvangen') }}</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" disabled class="rounded" />
                            <span class="ml-2 text-gray-900 dark:text-gray-100">{{ __('Bereid') }}</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" disabled class="rounded" />
                            <span class="ml-2 text-gray-900 dark:text-gray-100">{{ __('Opgehaald/geleverd') }}</span>
                        </label>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Interne opmerkingen') }}
                    </h3>
                    <textarea
                        class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
                        rows="6"
                        placeholder="{{ __('Bijv. glutenvrij deeg gebruiken, extra doos voorzien...') }}"
                    ></textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 justify-end">
                <a href="{{ route('owner.orders.index') }}" class="px-6 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500 transition-colors font-medium" wire:navigate>
                    {{ __('Annuleren') }}
                </a>
                <button type="submit" class="px-6 py-2 bg-gray-800 dark:bg-gray-700 text-white rounded-md hover:bg-gray-900 dark:hover:bg-gray-600 transition-colors font-medium">
                    {{ __('Opslaan') }}
                </button>
            </div>
        </form>
    </div>
</div>

