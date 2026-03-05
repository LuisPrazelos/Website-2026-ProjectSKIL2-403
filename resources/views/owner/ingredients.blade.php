<x-layouts.app :title="__('Ingrediënten beheren')">
    <div x-data="{ showModal: false }" class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-transparent">
            <!-- Header area inside big container -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold">{{ __('Ingrediënten beheren') }}</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ __('Beheer je ingrediënten hier: zoek, voeg toe, bewerk of verwijder items.') }}</p>
                </div>

                <div>
                    <button @click="showModal = true" class="inline-flex items-center px-4 py-2 border rounded-md text-sm bg-black text-white">+ {{ __('Ingrediënt toevoegen') }}</button>
                </div>
            </div>

            {{-- <x-layouts.search :action="route('owner.ingredients.index')" :value="$search ?? ''" placeholder="{{ __('Zoek ingrediënt...') }}" /> --}}
            {{-- Placeholder for search component, uncomment and adjust route/value when controller and route are set up --}}

            <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded-md shadow-sm p-4">
                <table class="min-w-full text-left">
                    <thead>
                        <tr>
                            <th class="px-4 py-3">{{ __('Naam') }}</th>
                            <th class="px-4 py-3">{{ __('Eenheid') }}</th>
                            <th class="px-4 py-3">{{ __('Allergeen') }}</th>
                            <th class="px-4 py-3">{{ __('Acties') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ingredients as $ingredient)
                            <tr class="border-t">
                                <td class="px-4 py-3">{{ $ingredient->name }}</td>
                                <td class="px-4 py-3">{{ $ingredient->measurementUnit->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @forelse($ingredient->ingredientAllergies as $ingredientAllergy)
                                        <span class="inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">{{ $ingredientAllergy->allergy->name ?? 'Onbekend' }}</span>
                                    @empty
                                        {{ __('Geen') }}
                                    @endforelse
                                </td>
                                <td class="px-4 py-3 flex items-center">
                                    <a href="{{ route('owner.ingredients.edit', ['ingredient' => $ingredient->id]) }}" title="{{ __('Bewerken') }}" class="mr-2">✏️</a>
                                    <form action="{{ route('owner.ingredients.destroy', ['ingredient' => $ingredient->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ingredient?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="{{ __('Verwijderen') }}" class="text-red-600">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-8 text-center" colspan="4">
                                    <h2 class="text-gray-500">{{ __('Geen ingrediënten gevonden.') }}</h2>
                                    <p class="text-gray-400 mt-2">{{ __('Voeg een ingrediënt toe via de knop rechtsboven.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    {{ __('Toont :from-:to van :total ingrediënten', ['from' => $ingredients->firstItem() ?? 0, 'to' => $ingredients->lastItem() ?? 0, 'total' => $ingredients->total() ?? $ingredients->count() ?? 0]) }}
                </div>

                <div>
                    @if(method_exists($ingredients, 'links'))
                        {{ $ingredients->links() }}
                    @endif
                </div>
            </div>

            <p class="mt-6 text-sm text-gray-400">{{ __('Beheer hier alle ingrediënten die gebruikt worden in recepten.') }}</p>
        </div>

        <!-- Modal -->
        <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center" style="display: none;">
            <div @click.away="showModal = false" class="bg-white dark:bg-zinc-800 p-8 rounded-lg shadow-lg w-1/3">
                <h2 class="text-2xl font-semibold mb-4">{{ __('Ingrediënt toevoegen') }}</h2>
                <form action="{{ route('owner.ingredients.store') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Naam ingrediënt') }}</label>
                        <input type="text" id="name" name="name" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" autocomplete="off">
                    </div>
                    <div class="mb-4">
                        <label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Eenheid') }}</label>
                        <select id="unit" name="unit_id" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="allergens" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Allergenen') }}</label>
                        <select id="allergens" name="allergens[]" multiple class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @foreach($allergens as $allergen)
                                <option value="{{ $allergen->allergyId }}">{{ $allergen->name }}</option>
                            @endforeach
                        </select>
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
