@if ($mode === 'shop')
    {{-- Shop View (Original Layout) --}}
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-6">
        <!-- Header with Background Image -->
        <div class="relative w-full overflow-hidden rounded-xl bg-cover bg-center h-64"
             style="background-image: url('/pictures/Gebakjes.jpg');">
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                <h1 class="text-4xl font-bold text-white drop-shadow-lg">Overschotten</h1>
            </div>
            <div class="absolute top-4 right-4">
                <a href="{{ route('cart.index') }}" class="bg-white p-2 rounded-full shadow-lg flex items-center justify-center hover:bg-gray-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">{{ count(session('cart')) }}</span>
                    @endif
                </a>
            </div>
        </div>

        <!-- Main Content (Original Style) -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            @foreach($surpluses as $surplus)
                <x-surplus-list :surplus="$surplus"/>
            @endforeach
        </div>
    </div>
@else
    {{-- Owner Management View (Original Layout) --}}
    <div x-data="{ showModal: false }" class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-transparent">
            <!-- Header area inside big container (teruggezet zoals dashboard) -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold">{{ __('Overschotten beheren') }}</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ __('Beheer je overschotten hier: zoek, voeg toe, bewerk of verwijder items.') }}</p>
                </div>

                <div>
                    <button @click="showModal = true" class="inline-flex items-center px-4 py-2 border rounded-md text-sm bg-accent text-white hover:bg-amber-700 dark:bg-accent dark:text-white dark:hover:bg-amber-500">+ {{ __('Overschot toevoegen') }}</button>
                </div>
            </div>

            {{-- Search --}}
            <div class="mb-4">
                <input
                    type="text"
                    wire:model.live="search"
                    placeholder="{{ __('Zoek dessert...') }}"
                    class="w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                >
            </div>

            <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded-md shadow-sm p-4">
                <table class="min-w-full text-left">
                    <thead>
                        <tr>
                            <th class="px-4 py-3">{{ __('Dessert') }}</th>
                            <th class="px-4 py-3">{{ __('Datum') }}</th>
                            <th class="px-4 py-3">{{ __('Aantal beschikbaar') }}</th>
                            <th class="px-4 py-3">{{ __('Korting (%)') }}</th>
                            <th class="px-4 py-3">{{ __('Status') }}</th>
                            <th class="px-4 py-3">{{ __('Acties') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($surpluses as $surplus)
                            <tr class="border-t">
                                <td class="px-4 py-3">{{ $surplus->dessert->name ?? __('Onbekend') }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $date = $surplus->date ?? $surplus->expiration_date ?? $surplus->created_at ?? null;
                                    @endphp
                                    {{ $date ? (\Illuminate\Support\Carbon::parse($date))->format('d-m-Y') : __('-') }}
                                </td>
                                <td class="px-4 py-3">{{ $surplus->total_amount ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $surplus->sale ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @php $status = $surplus->status ?? 'available'; @endphp

                                    @if($status === 'available' || $status === 'beschikbaar')
                                        <span class="inline-block px-2 py-1 rounded-full bg-green-100 text-green-800">{{ __('Beschikbaar') }}</span>
                                    @elseif($status === 'reserved' || $status === 'gereserveerd')
                                        <span class="inline-block px-2 py-1 rounded-full bg-yellow-100 text-yellow-800">{{ __('Gereserveerd') }}</span>
                                    @elseif($status === 'picked_up' || $status === ' opgehaald' || $status === 'opgehaald')
                                        <span class="inline-block px-2 py-1 rounded-full bg-gray-100 text-gray-800">{{ __('Opgehaald') }}</span>
                                    @else
                                        <span class="inline-block px-2 py-1 rounded-full bg-gray-50 text-gray-600">{{ $status }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 flex items-center">
                                    <button wire:click="edit({{ $surplus->id }})" title="{{ __('Bewerken') }}" class="mr-2 cursor-pointer">✏️</button>
                                    <button wire:click="delete({{ $surplus->id }})" onclick="confirm('{{ __('Weet u zeker dat u dit overschot wilt verwijderen?') }}') || event.stopImmediatePropagation()" title="{{ __('Verwijderen') }}" class="text-red-600 cursor-pointer">🗑️</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-8 text-center" colspan="6">
                                    <h2 class="text-gray-500">{{ __('Geen overschotten gevonden.') }}</h2>
                                    <p class="text-gray-400 mt-2">{{ __('Voeg een overschot toe via de knop rechtsboven.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    {{ __('Toont :from-:to van :total overschotten', ['from' => $surpluses->firstItem() ?? 0, 'to' => $surpluses->lastItem() ?? 0, 'total' => $surpluses->total() ?? $surpluses->count() ?? 0]) }}
                </div>

                <div>
                    @if(method_exists($surpluses, 'links'))
                        {{ $surpluses->links() }}
                    @endif
                </div>
            </div>

            <p class="mt-6 text-sm text-gray-400">{{ __('Bij het bevestigen van een overschot ontvangen geïnteresseerde klanten automatisch een melding.') }}</p>
        </div>

        <!-- Modal -->
        <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center" style="display: none;">
            <div @click.away="showModal = false" class="bg-white dark:bg-zinc-800 p-8 rounded-lg shadow-lg w-1/3">
                <h2 class="text-2xl font-semibold mb-4">{{ $this->editingSurplus ? __('Overschot bewerken') : __('Overschot toevoegen') }}</h2>
                <form wire:submit.prevent="{{ $this->editingSurplus ? 'update' : 'store' }}">
                    <div class="mb-4">
                        <label for="dessert" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Dessert') }}</label>
                        <select wire:model="dessertInput" id="dessert" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">{{ __('Selecteer een dessert') }}</option>
                            @foreach ($this->desserts as $dessert)
                                <option value="{{ $dessert['id'] }}">{{ $dessert['name'] }}</option>
                            @endforeach
                        </select>
                        @error('dessertInput')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Datum') }}</label>
                        <input type="date" wire:model="dateInput" id="date" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('dateInput')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Aantal beschikbaar') }}</label>
                        <input type="number" wire:model="quantityInput" id="quantity" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('quantityInput')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="discount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Korting (%)') }}</label>
                        <input type="number" step="0.01" wire:model="discountInput" id="discount" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('discountInput')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    @if ($this->editingSurplus)
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                            <select wire:model="statusInput" id="status" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="available">{{ __('Beschikbaar') }}</option>
                                <option value="reserved">{{ __('Gereserveerd') }}</option>
                                <option value="picked_up">{{ __('Opgehaald') }}</option>
                            </select>
                            @error('statusInput')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Extra opmerking') }}</label>
                        <textarea wire:model="{{ $this->editingSurplus ? 'commentInput' : 'notesInput' }}" id="notes" rows="3" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        @error($this->editingSurplus ? 'commentInput' : 'notesInput')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="showModal = false" class="mr-2 inline-flex items-center px-4 py-2 border rounded-md text-sm bg-zinc-200 text-zinc-800 hover:bg-zinc-300 dark:bg-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-600">{{ __('Annuleren') }}</button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border rounded-md text-sm bg-accent text-white hover:bg-amber-700 dark:bg-accent dark:text-white dark:hover:bg-amber-500">{{ $this->editingSurplus ? __('Bijwerken') : __('Toevoegen') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

