<x-layouts.app :title="__('Recepten beheren')">
    <div x-data="{ showModal: {{ $errors->any() ? 'true' : 'false' }}, ingredients: {{ old('ingredients') ? json_encode(old('ingredients')) : '[{ id: \'\', quantity: \'\', unit_id: \'\' }]' }} }" class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-transparent">
            <!-- Header area inside big container -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold">{{ __('Recepten beheren') }}</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ __('Beheer je recepten hier: zoek, voeg toe, bewerk of verwijder items.') }}</p>
                </div>

                <div>
                    <button @click="showModal = true" class="inline-flex items-center px-4 py-2 border rounded-md text-sm bg-accent text-white hover:bg-amber-700 dark:bg-accent dark:text-white dark:hover:bg-amber-500">+ {{ __('Recept toevoegen') }}</button>
                </div>
            </div>

            <!-- Filter & Search Form -->
            <form method="GET" action="{{ route('owner.recipes.index') }}" class="mb-4 flex flex-col md:flex-row gap-2">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="{{ __('Zoek recept...') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-zinc-900 dark:border-zinc-700 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="w-full md:w-1/4">
                    <select name="category" onchange="this.form.submit()" class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-zinc-900 dark:border-zinc-700 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">{{ __('Alle categorieën') }}</option>
                        @foreach($categories as $id => $name)
                            <option value="{{ $id }}" {{ (isset($category) && $category == $id) ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-accent text-white rounded-md hover:bg-amber-700 dark:bg-accent dark:text-white dark:hover:bg-amber-500">{{ __('Zoeken') }}</button>
                @if($search || (isset($category) && $category))
                    <a href="{{ route('owner.recipes.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 flex items-center justify-center" title="{{ __('Filters wissen') }}">X</a>
                @endif
            </form>

            <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded-md shadow-sm p-4">
                <table class="min-w-full text-left">
                    <thead>
                        <tr>
                            <th class="px-4 py-3">{{ __('Recept ID') }}</th>
                            <th class="px-4 py-3">{{ __('Naam') }}</th>
                            <th class="px-4 py-3">{{ __('Categorie') }}</th>
                            <th class="px-4 py-3">{{ __('Bereidingstijd') }}</th>
                            <th class="px-4 py-3">{{ __('Portie grootte') }}</th>
                            <th class="px-4 py-3">{{ __('Kostprijs') }}</th>
                            <th class="px-4 py-3">{{ __('Laatste update') }}</th>
                            <th class="px-4 py-3">{{ __('Acties') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recipes as $recipe)
                            <tr class="border-t">
                                <td class="px-4 py-3">#{{ $recipe->id }}</td>
                                <td class="px-4 py-3">{{ $recipe->name ?? __('Onbekend') }}</td>
                                <td class="px-4 py-3">{{ $recipe->category->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $recipe->preparation_time ?? '-' }} {{ __('minuten') }}</td>
                                <td class="px-4 py-3">{{ $recipe->portion_size ?? '-' }}</td>
                                <td class="px-4 py-3">€{{ number_format($recipe->cost ?? 0, 2) }}</td>
                                <td class="px-4 py-3">{{ $recipe->updated_at ? $recipe->updated_at->format('d-m-Y H:i') : '-' }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('owner.recipes.show', $recipe->id) }}" title="{{ __('Bekijken') }}" class="mr-2">👁️</a>
                                    <form action="{{ route('owner.recipes.destroy', $recipe->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="{{ __('Verwijderen') }}" class="text-red-600">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-8 text-center" colspan="8">
                                    <h2 class="text-gray-500">{{ __('Geen recepten gevonden.') }}</h2>
                                    <p class="text-gray-400 mt-2">{{ __('Voeg een recept toe via de knop rechtsboven.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    {{ __('Toont :from-:to van :total recepten', ['from' => $recipes->firstItem() ?? 0, 'to' => $recipes->lastItem() ?? 0, 'total' => $recipes->total() ?? $recipes->count() ?? 0]) }}
                </div>

                <div>
                    @if(method_exists($recipes, 'links'))
                        {{ $recipes->links() }}
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center" style="display: none;">
            <div @click.away="showModal = false" class="bg-white dark:bg-zinc-800 p-8 rounded-lg shadow-lg w-1/2 max-h-screen overflow-y-auto">
                <h2 class="text-2xl font-semibold mb-4">{{ __('Recept toevoegen') }}</h2>
                <form action="{{ route('owner.recipes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <h3 class="text-lg font-semibold mb-2">{{ __('Basisinformatie') }}</h3>
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Receptnaam') }}</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border @error('name') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Categorie') }}</label>
                        <x-layouts.app.dropdown :options="$categories" name="category_id" :selected="old('category_id')" />
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="preparation_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Bereidingstijd (minuten)') }}</label>
                        <input type="number" id="preparation_time" name="preparation_time" value="{{ old('preparation_time') }}" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border @error('preparation_time') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('preparation_time')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="selling_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Verkoopprijs (€)') }}</label>
                        <input type="number" step="0.01" id="selling_price" name="selling_price" value="{{ old('selling_price') }}" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border @error('selling_price') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('selling_price')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="portion_size_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Portie grootte') }}</label>
                        <div class="flex gap-2">
                            <input type="number" id="portion_size_quantity" name="portion_size_quantity" value="{{ old('portion_size_quantity') }}" class="mt-1 block w-1/2 px-3 py-2 bg-white dark:bg-zinc-700 border @error('portion_size_quantity') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="{{ __('Aantal') }}">
                            <x-layouts.app.dropdown :options="$portionUnits" name="portion_size_unit_id" :selected="old('portion_size_unit_id')" class="w-1/2" />
                        </div>
                        @error('portion_size_quantity')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('portion_size_unit_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <h3 class="text-lg font-semibold mb-2 mt-6">{{ __('Ingrediëntenlijst') }}</h3>
                    <div id="ingredients-container">
                        <template x-for="(ingredient, index) in ingredients" :key="index">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="flex-1">
                                    <x-layouts.app.dropdown :options="$ingredients" x-bind:name="`ingredients[${index}][id]`" x-model="ingredient.id" />
                                </div>
                                <input type="number" x-bind:name="`ingredients[${index}][quantity]`" placeholder="Hoeveelheid" x-model="ingredient.quantity" class="w-1/4 px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <div class="flex-1">
                                    <x-layouts.app.dropdown :options="$units" x-bind:name="`ingredients[${index}][unit_id]`" x-model="ingredient.unit_id" />
                                </div>
                                <button type="button" @click="ingredients.splice(index, 1)" class="text-red-600">🗑️</button>
                            </div>
                        </template>
                    </div>
                    @error('ingredients')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <button type="button" @click="ingredients.push({ id: '', quantity: '', unit_id: '' })" class="mt-2 text-sm text-blue-600">+ {{ __('Ingrediënt toevoegen') }}</button>

                    <h3 class="text-lg font-semibold mb-2 mt-6">{{ __('Beschrijving & Bereiding') }}</h3>
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Beschrijving') }}</label>
                        <textarea id="description" name="description" rows="3" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border @error('description') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Bereidingsstappen') }}</label>
                        <textarea id="instructions" name="instructions" rows="5" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border @error('instructions') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('instructions') }}</textarea>
                        @error('instructions')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Foto') }}</label>
                        <input type="file" id="photo" name="photo" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border @error('photo') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('photo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="button" @click="showModal = false" class="mr-2 inline-flex items-center px-4 py-2 border rounded-md text-sm bg-zinc-200 text-zinc-800 hover:bg-zinc-300 dark:bg-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-600">{{ __('Annuleren') }}</button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border rounded-md text-sm bg-accent text-white hover:bg-amber-700 dark:bg-accent dark:text-white dark:hover:bg-amber-500">{{ __('Opslaan recept') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
