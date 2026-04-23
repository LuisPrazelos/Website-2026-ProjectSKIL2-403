<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <!-- Header -->
    <div class="relative w-full overflow-hidden rounded-xl bg-cover bg-center h-40" style="background-image: url('/Pictures/Gebakjes.jpg');">
        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white drop-shadow-lg">{{ __('Beantwoord Evenement #' . $happening->id) }}</h1>
                <p class="text-white text-lg mt-2">{{ __('Reageer op de aanvraag van') }} {{ $happening->user->first_name }} {{ $happening->user->last_name }}</p>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 px-4">
        <a href="{{ route('owner.respond-order-requests') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:underline" wire:navigate>
            {{ __('Reageren op aanvragen') }}
        </a>
        <span>></span>
        <span>{{ __('Evenement #' . $happening->id) }}</span>
    </div>

    <!-- Main Content -->
    <div class="relative flex flex-1 flex-col rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800 overflow-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left: Evenement Details -->
            <div>
                <h2 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">{{ __('Evenement details') }}</h2>

                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Klantnaam') }}</label>
                        <p class="text-gray-900 dark:text-white font-semibold mt-1">{{ $happening->user->first_name }} {{ $happening->user->last_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Gewenste datum') }}</label>
                        <p class="text-gray-900 dark:text-white font-semibold mt-1">{{ $happening->event_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Boodschap') }}</label>
                        <p class="text-gray-900 dark:text-white mt-1">{{ $happening->message }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Aantal personen') }}</label>
                        <p class="text-gray-900 dark:text-white font-semibold mt-1">{{ $happening->person_count }} {{ __('personen') }}</p>
                    </div>
                     <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Op locatie') }}</label>
                        <p class="text-gray-900 dark:text-white font-semibold mt-1">{{ $happening->on_location ? 'Ja' : 'Nee' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Locatie') }}</label>
                        <p class="text-gray-900 dark:text-white font-semibold mt-1">
                            {{ $happening->on_location ? ($happening->location ?: __('Locatie niet ingevuld')) : __('Niet op locatie') }}
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Thema') }}</label>
                        <p class="text-gray-900 dark:text-white font-semibold mt-1">{{ $happening->theme->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Pakket') }}</label>
                        <p class="text-gray-900 dark:text-white font-semibold mt-1">{{ $happening->package->name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Right: Reactie Formulier -->
            <div>
                <h2 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">{{ __('Jouw reactie') }}</h2>

                <form wire:submit="saveResponse" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Prijsvoorstel per persoon (€)') }}
                        </label>
                        <input
                            wire:model="pricePerPerson"
                            type="number"
                            step="0.01"
                            min="0"
                            placeholder="bijv. 45.00"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Bericht aan klant') }}
                        </label>
                        <textarea
                            wire:model="remarks"
                            placeholder="{{ __('Beste ' . ($happening->user->first_name ?? 'klant') . ', geef hier je reactie op de aanvraag...') }}"
                            rows="8"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
                        ></textarea>
                    </div>

                    <!-- Dessert Selection Section -->
                    <div class="mt-8">
                        <div class="flex items-center justify-between mb-4">
                             <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Gekozen desserts</h3>
                             <button type="button" wire:click="showDessertSelector" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">+ Dessert toevoegen</button>
                        </div>

                        <div class="border border-gray-200 dark:border-zinc-700 rounded-lg overflow-hidden mb-4">
                            <table class="min-w-full text-left text-sm">
                                <thead class="bg-gray-50 dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-700">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold text-gray-900 dark:text-white">Dessert</th>
                                        <th class="px-4 py-3 font-semibold text-gray-900 dark:text-white">Prijs/st</th>
                                        <th class="px-4 py-3 font-semibold text-gray-900 dark:text-white">Aantal</th>
                                        <th class="px-4 py-3 font-semibold text-gray-900 dark:text-white">Allergieën</th>
                                        <th class="px-4 py-3 font-semibold text-gray-900 dark:text-white text-right">Totaal</th>
                                        <th class="px-4 py-3"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                                    @forelse($selectedDesserts as $index => $dessert)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800" wire:key="selected-dessert-{{ $index }}">
                                            <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $dessert['name'] }}</td>
                                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">€ {{ number_format($dessert['price'], 2) }}</td>
                                            <td class="px-4 py-3">
                                                <input type="number" min="1" wire:change="updateDessertQuantity({{ $index }}, $event.target.value)" value="{{ $dessert['quantity'] }}" class="w-16 px-2 py-1 border border-gray-300 dark:border-zinc-600 rounded bg-white dark:bg-zinc-800 text-gray-900 dark:text-white text-sm">
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="text" placeholder="bijv. gluten" wire:change="updateDessertAllergies({{ $index }}, $event.target.value)" value="{{ $dessert['allergies'] }}" class="w-full px-2 py-1 border border-gray-300 dark:border-zinc-600 rounded bg-white dark:bg-zinc-800 text-gray-900 dark:text-white text-sm">
                                            </td>
                                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white font-medium whitespace-nowrap">
                                                € {{ number_format($dessert['price'] * $dessert['quantity'], 2) }}
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <button type="button" wire:click="removeDessert({{ $index }})" class="text-red-500 hover:text-red-700 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400 italic">Nog geen desserts toegevoegd.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if(count($selectedDesserts) > 0)
                                    <tfoot class="bg-gray-50 dark:bg-zinc-900 border-t border-gray-200 dark:border-zinc-700">
                                        <tr>
                                            <td colspan="4" class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white">Totaal desserts:</td>
                                            <td class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white whitespace-nowrap">
                                                € {{ number_format($totalPrice, 2) }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>


                    <div class="flex gap-3 justify-end pt-4 border-t border-gray-200 dark:border-zinc-700">
                        <a
                            href="{{ route('owner.respond-order-requests') }}"
                            wire:navigate
                            class="px-6 py-2 bg-gray-300 dark:bg-zinc-700 text-gray-900 dark:text-white rounded-md hover:bg-gray-400 dark:hover:bg-zinc-600 transition-colors font-medium"
                        >
                            {{ __('Annuleren') }}
                        </a>
                        <button
                            type="submit"
                            class="px-6 py-2 bg-gray-800 dark:bg-gray-700 text-white rounded-md hover:bg-gray-900 dark:hover:bg-gray-600 transition-colors font-medium"
                        >
                            {{ __('Reactie versturen') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

         <!-- Dessert Selector Popup -->
        @if($showDessertPopup)
            <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" wire:click="hideDessertSelector">
                <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-2xl border border-gray-200 dark:border-zinc-700 w-full max-w-2xl max-h-[70vh] flex flex-col" @click.stop>
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700 flex items-center justify-between bg-gray-50 dark:bg-zinc-800 rounded-t-xl">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Kies een dessert</h3>
                        <button type="button" wire:click="hideDessertSelector" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="overflow-y-auto flex-1 px-6 py-4">
                        <table class="w-full text-left text-sm">
                            <thead class="sticky top-0 bg-white dark:bg-zinc-900 border-b border-gray-300 dark:border-zinc-700">
                                <tr>
                                    <th class="px-2 py-3 font-semibold text-gray-900 dark:text-white">Naam</th>
                                    <th class="px-2 py-3 font-semibold text-gray-900 dark:text-white">Prijs</th>
                                    <th class="px-2 py-3 text-right">Actie</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                                @forelse($availableDesserts as $dessert)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800 transition">
                                        <td class="px-2 py-3 text-gray-900 dark:text-white font-medium">{{ $dessert->name }}</td>
                                        <td class="px-2 py-3 text-gray-600 dark:text-gray-400">€ {{ number_format($dessert->price, 2) }}</td>
                                        <td class="px-2 py-3 text-right">
                                            <button type="button" wire:click="addDessert({{ $dessert->id }})" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded transition font-medium text-xs">Toevoegen</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-2 py-8 text-center text-gray-500 dark:text-gray-400">Geen desserts beschikbaar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
