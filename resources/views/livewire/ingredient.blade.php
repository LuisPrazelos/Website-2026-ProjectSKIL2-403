<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-transparent">
        <!-- Header area inside big container -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold">{{ __('Ingrediënten beheren') }}</h1>
                <p class="text-sm text-gray-600 mt-1">{{ __('Beheer je ingrediënten hier: zoek, voeg toe, bewerk of verwijder items.') }}</p>
            </div>

            <div>
                <button wire:click="openModal" class="inline-flex items-center px-4 py-2 border rounded-md text-sm bg-black text-white">+ {{ __('Ingrediënt toevoegen') }}</button>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

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
                                <button wire:click="edit({{ $ingredient->id }})" title="{{ __('Bewerken') }}" class="mr-2">✏️</button>
                                <button onclick="confirm('Are you sure?') || event.stopImmediatePropagation()" wire:click="delete({{ $ingredient->id }})" title="{{ __('Verwijderen') }}" class="text-red-600">🗑️</button>
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

        <div class="mt-4">
            {{ $ingredients->links() }}
        </div>

        <p class="mt-6 text-sm text-gray-400">{{ __('Beheer hier alle ingrediënten die gebruikt worden in recepten.') }}</p>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-zinc-800 p-8 rounded-lg shadow-lg w-1/3">
            <h2 class="text-2xl font-semibold mb-4">{{ $isEditMode ? __('Ingrediënt bewerken') : __('Ingrediënt toevoegen') }}</h2>
            <form wire:submit.prevent="save" autocomplete="off">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Naam ingrediënt') }}</label>
                    <input type="text" id="name" wire:model="name" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" autocomplete="off">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Eenheid') }}</label>
                    <select id="unit" wire:model="unit_id" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">{{ __('Selecteer eenheid') }}</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                    @error('unit_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="allergens" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Allergenen') }}</label>
                    <select id="allergens" wire:model="selectedAllergens" multiple class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm h-32">
                        @foreach($allergens as $allergen)
                            <option value="{{ $allergen->allergyId }}">{{ $allergen->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedAllergens.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end">
                    <button type="button" wire:click="closeModal" class="mr-2 inline-flex items-center px-4 py-2 border rounded-md text-sm">{{ __('Annuleren') }}</button>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border rounded-md text-sm bg-black text-white">{{ $isEditMode ? __('Opslaan') : __('Toevoegen') }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
