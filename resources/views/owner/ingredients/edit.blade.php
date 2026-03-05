<x-layouts.app :title="__('Ingrediënt bewerken')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-transparent">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold">{{ __('Ingrediënt bewerken') }}</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ __('Bewerk de details van dit ingrediënt.') }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 p-8 rounded-lg shadow-lg w-full">
                <form action="{{ route('owner.ingredients.update', $ingredient->ingredientId) }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT') {{-- Use PUT method for updates --}}

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Naam ingrediënt') }}</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $ingredient->ingredientName) }}" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" autocomplete="off">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Eenheid') }}</label>
                        <select id="unit" name="unit_id" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @foreach($units as $unit)
                                <option value="{{ $unit->measurementUnitId }}" @selected(old('unit_id', $ingredient->standardUnitId) == $unit->measurementUnitId)>{{ $unit->unitName }}</option>
                            @endforeach
                        </select>
                        @error('unit_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="allergens" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Allergenen') }}</label>
                        <select id="allergens" name="allergens[]" multiple class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @php
                                $currentAllergenIds = old('allergens', $ingredient->ingredientAllergies->pluck('allergyId')->toArray());
                            @endphp
                            @foreach($allergens as $allergen)
                                <option value="{{ $allergen->allergyId }}" @selected(in_array($allergen->allergyId, $currentAllergenIds))>{{ $allergen->name }}</option>
                            @endforeach
                        </select>
                        @error('allergens')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        @error('allergens.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('owner.ingredients.index') }}" class="mr-2 inline-flex items-center px-4 py-2 border rounded-md text-sm">{{ __('Annuleren') }}</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border rounded-md text-sm bg-black text-white">{{ __('Opslaan') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
