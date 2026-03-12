<x-layouts.app :title="__('Recept bewerken')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-transparent">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold">{{ __('Recept bewerken') }}</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ __('Werk de details van het recept bij.') }}</p>
                </div>
                <div>
                    <a href="{{ route('owner.recipes.show', $recipe->id) }}" class="inline-flex items-center px-4 py-2 border rounded-md text-sm">{{ __('Annuleren') }}</a>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 p-8 rounded-lg shadow-lg w-full max-h-screen overflow-y-auto">
                <form action="{{ route('owner.recipes.update', $recipe->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h3 class="text-lg font-semibold mb-2">{{ __('Basisinformatie') }}</h3>
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Receptnaam') }}</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $recipe->name) }}" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border @error('name') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Categorie') }}</label>
                        <x-layouts.app.dropdown :options="$categories" name="category_id" :selected="old('category_id', $recipe->category_id)" />
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="preparation_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Bereidingstijd (minuten)') }}</label>
                        <input type="number" id="preparation_time" name="preparation_time" value="{{ old('preparation_time', $recipe->preparation_time) }}" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border @error('preparation_time') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('preparation_time')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="selling_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Verkoopprijs (€)') }}</label>
                        <input type="number" step="0.01" id="selling_price" name="selling_price" value="{{ old('selling_price', $recipe->selling_price) }}" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border @error('selling_price') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('selling_price')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="portion_size_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Portie grootte') }}</label>
                        <div class="flex gap-2">
                            <input type="number" id="portion_size_quantity" name="portion_size_quantity" value="{{ old('portion_size_quantity', $recipe->portion_size) }}" class="mt-1 block w-1/2 px-3 py-2 bg-white dark:bg-zinc-700 border @error('portion_size_quantity') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="{{ __('Aantal') }}">
                            <x-layouts.app.dropdown :options="$portionUnits" name="portion_size_unit_id" :selected="old('portion_size_unit_id', $recipe->portion_size_unit_id)" class="w-1/2" />
                        </div>
                        @error('portion_size_quantity')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('portion_size_unit_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <h3 class="text-lg font-semibold mb-2 mt-6">{{ __('Ingrediëntenlijst') }}</h3>
                    <div x-data="{ ingredients: {{ old('ingredients') ? json_encode(old('ingredients')) : $recipe->ingredients->map(fn($i) => ['id' => $i->id, 'quantity' => $i->pivot->quantity, 'unit_id' => $i->pivot->measurement_unit_id])->toJson() }} }">
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
                    </div>

                    <h3 class="text-lg font-semibold mb-2 mt-6">{{ __('Beschrijving & Bereiding') }}</h3>
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Beschrijving') }}</label>
                        <textarea id="description" name="description" rows="3" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border @error('description') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('description', $recipe->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Bereidingsstappen') }}</label>
                        <textarea id="instructions" name="instructions" rows="5" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border @error('instructions') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('instructions', $recipe->instructions) }}</textarea>
                        @error('instructions')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Foto') }}</label>
                        <input type="file" id="photo" name="photo" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border @error('photo') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @if ($recipe->photo)
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">{{ __('Huidige foto:') }}</p>
                                <img src="{{ Storage::url($recipe->photo) }}" alt="{{ $recipe->name }}" class="mt-1 h-20 w-20 object-cover rounded-md">
                            </div>
                        @endif
                        @error('photo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border rounded-md text-sm bg-black text-white">{{ __('Opslaan recept') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
