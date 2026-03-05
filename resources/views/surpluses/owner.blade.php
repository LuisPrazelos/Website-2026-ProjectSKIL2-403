<x-layouts.app :title="__('Overschotten beheren')">
    <div x-data="{ showModal: false }" class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-transparent">
            <!-- Header area inside big container (teruggezet zoals dashboard) -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold">{{ __('Overschotten beheren') }}</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ __('Beheer je overschotten hier: zoek, voeg toe, bewerk of verwijder items.') }}</p>
                </div>

                <div>
                    <button @click="showModal = true" class="inline-flex items-center px-4 py-2 border rounded-md text-sm bg-black text-white">+ {{ __('Overschot toevoegen') }}</button>
                </div>
            </div>

            <x-layouts.search :action="route('owner.surpluses.index')" :value="$search ?? ''" placeholder="{{ __('Zoek dessert...') }}" />

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
                                    <a href="{{ route('owner.surpluses.edit', $surplus->id) }}" title="{{ __('Bewerken') }}" class="mr-2">✏️</a>
                                    <form action="{{ route('owner.surpluses.destroy', $surplus->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this surplus?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="{{ __('Verwijderen') }}" class="text-red-600">🗑️</button>
                                    </form>
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
                <h2 class="text-2xl font-semibold mb-4">{{ __('Overschot toevoegen') }}</h2>
                <form action="{{ route('owner.surpluses.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="dessert" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Dessert') }}</label>
                        <x-layouts.app.dropdown name="dessert" :options="$desserts" />
                    </div>
                    <div class="mb-4">
                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Datum') }}</label>
                        <input type="date" id="date" name="date" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Aantal beschikbaar') }}</label>
                        <input type="number" id="quantity" name="quantity" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="discount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Korting (%)') }}</label>
                        <input type="number" id="discount" name="discount" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Extra opmerking') }}</label>
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="showModal = false" class="mr-2 inline-flex items-center px-4 py-2 border rounded-md text-sm">{{ __('Annuleren') }}</button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border rounded-md text-sm bg-black text-white">{{ __('Toevoegen') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
