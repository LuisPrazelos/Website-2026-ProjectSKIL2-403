<x-layouts.app :title="__('Desserts beheren')">
    <div x-data="{ showModal: false }" class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-transparent">
            <!-- Header area inside big container -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold">{{ __('Desserts beheren') }}</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ __('Beheer je desserts hier: zoek, voeg toe, bewerk of verwijder items.') }}</p>
                </div>

                <div>
                    <button @click="showModal = true" class="inline-flex items-center px-4 py-2 border rounded-md text-sm bg-black text-white">+ {{ __('Dessert toevoegen') }}</button>
                </div>
            </div>

            <x-layouts.search :action="route('owner.deserts.index')" :value="$search ?? ''" placeholder="{{ __('Zoek dessert...') }}" />

            <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded-md shadow-sm p-4">
                <table class="min-w-full text-left">
                    <thead>
                        <tr>
                            <th class="px-4 py-3">{{ __('Afbeelding') }}</th>
                            <th class="px-4 py-3">{{ __('Naam') }}</th>
                            <th class="px-4 py-3">{{ __('Prijs') }}</th>
                            <th class="px-4 py-3">{{ __('Beschrijving') }}</th>
                            <th class="px-4 py-3">{{ __('Acties') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deserts as $desert)
                            <tr class="border-t">
                                <td class="px-4 py-3">
                                    @if($desert->picture)
                                        <img src="{{ asset('pictures/' . $desert->picture->hash) }}" alt="{{ $desert->name }}" class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <span class="inline-block h-10 w-10 rounded-full bg-gray-200"></span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-medium">{{ $desert->name }}</td>
                                <td class="px-4 py-3">€{{ number_format($desert->price, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500 truncate max-w-xs">{{ $desert->description }}</td>
                                <td class="px-4 py-3">
                                    <a href="#" title="{{ __('Bewerken') }}" class="mr-2">✏️</a>
                                    <a href="#" title="{{ __('Verwijderen') }}" class="text-red-600">🗑️</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-8 text-center" colspan="5">
                                    <h2 class="text-gray-500">{{ __('Geen desserts gevonden.') }}</h2>
                                    <p class="text-gray-400 mt-2">{{ __('Voeg een dessert toe via de knop rechtsboven.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    {{ __('Toont :from-:to van :total desserts', ['from' => $deserts->firstItem() ?? 0, 'to' => $deserts->lastItem() ?? 0, 'total' => $deserts->total() ?? $deserts->count() ?? 0]) }}
                </div>

                <div>
                    @if(method_exists($deserts, 'links'))
                        {{ $deserts->links() }}
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal (Placeholder for now) -->
        <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center" style="display: none;">
            <div @click.away="showModal = false" class="bg-white dark:bg-zinc-800 p-8 rounded-lg shadow-lg w-1/3">
                <h2 class="text-2xl font-semibold mb-4">{{ __('Dessert toevoegen') }}</h2>
                <p class="text-gray-600 mb-4">{{ __('Functionaliteit om desserts toe te voegen komt binnenkort.') }}</p>
                <div class="flex justify-end">
                    <button type="button" @click="showModal = false" class="px-4 py-2 border rounded-md text-sm bg-black text-white">{{ __('Sluiten') }}</button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
