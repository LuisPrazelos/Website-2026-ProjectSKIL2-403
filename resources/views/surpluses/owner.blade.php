<x-layouts.app.sidebar :title="__('Overschotten beheren')">
    <div class="app-main">
        <header class="page-header py-6">
            <div class="container mx-auto px-4">
                <h1 class="text-2xl font-semibold">{{ __('Overschotten beheren') }}</h1>
                <p class="text-sm text-gray-600 mt-1">{{ __('Beheer je overschotten hier: zoek, voeg toe, bewerk of verwijder items.') }}</p>
            </div>
        </header>

        <main class="page-content container mx-auto px-4 py-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-1/2">
                    <label for="search" class="sr-only">{{ __('Zoek') }}</label>
                    <input id="search" name="search" type="search" placeholder="Zoek dessert..." class="w-full" />
                </div>

                <div>
                    <a href="#" class="inline-flex items-center px-4 py-2 border rounded-md text-sm bg-brown-700 text-white">
                        + {{ __('Overschot toevoegen') }}
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto bg-white rounded-md shadow-sm">
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
                                    {{ $date ? $date->format('d-m-Y') : __('-') }}
                                </td>
                                <td class="px-4 py-3">{{ $surplus->total_amount ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $surplus->discount ?? $surplus->discount_percentage ?? ($surplus->sale_percentage ?? '-') }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $status = $surplus->status ?? null;
                                    @endphp

                                    @if($status === 'available' || $status === 'beschikbaar')
                                        <span class="inline-block px-2 py-1 rounded-full bg-green-100 text-green-800">{{ __('Beschikbaar') }}</span>
                                    @elseif($status === 'reserved' || $status === 'gereserveerd')
                                        <span class="inline-block px-2 py-1 rounded-full bg-yellow-100 text-yellow-800">{{ __('Gereserveerd') }}</span>
                                    @elseif($status === 'picked_up' || $status === ' opgehaald' || $status === 'opgehaald')
                                        <span class="inline-block px-2 py-1 rounded-full bg-gray-100 text-gray-800">{{ __('Opgehaald') }}</span>
                                    @else
                                        <span class="inline-block px-2 py-1 rounded-full bg-gray-50 text-gray-600">{{ __('-') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <a href="#" title="{{ __('Bewerken') }}" class="mr-2">✏️</a>
                                    <a href="#" title="{{ __('Verwijderen') }}" class="text-red-600">🗑️</a>
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
                    {{-- Placeholder pagination links (assuming $surpluses is a LengthAwarePaginator) --}}
                    @if(method_exists($surpluses, 'links'))
                        {{ $surpluses->links() }}
                    @endif
                </div>
            </div>

            <p class="mt-6 text-sm text-gray-400">{{ __('Bij het bevestigen van een overschot ontvangen geïnteresseerde klanten automatisch een melding.') }}</p>
        </main>
    </div>

</x-layouts.app.sidebar>
