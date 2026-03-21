<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-transparent">

            @if (session()->has('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Header area -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold">{{ __('Recepten beheren') }}</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ __('Beheer je recepten hier: zoek, voeg toe, bewerk of verwijder items.') }}</p>
                </div>
                <div>
                    <button wire:click="create" class="inline-flex items-center px-4 py-2 border rounded-md text-sm bg-accent text-white hover:bg-amber-700 dark:bg-accent dark:text-white dark:hover:bg-amber-500">+ {{ __('Recept toevoegen') }}</button>
                </div>
            </div>

            <!-- Filter & Search Form -->
            <div class="mb-4 flex flex-col md:flex-row gap-2">
                <div class="flex-1">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('Zoek recept...') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-zinc-900 dark:border-zinc-700 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="w-full md:w-1/4">
                    <select wire:model.live="category" class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-zinc-900 dark:border-zinc-700 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">{{ __('Alle categorieën') }}</option>
                        @foreach($allCategories as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Recipes Table -->
            <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded-md shadow-sm p-4">
                <table class="min-w-full text-left">
                    <thead>
                        <tr>
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
                                <td class="px-4 py-3">{{ $recipe->name ?? __('Onbekend') }}</td>
                                <td class="px-4 py-3">{{ $recipe->category->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $recipe->preparation_time ?? '-' }} {{ __('minuten') }}</td>
                                <td class="px-4 py-3">{{ $recipe->portion_size ?? '-' }} {{ $recipe->portionUnit->name ?? '' }}</td>
                                <td class="px-4 py-3">€{{ number_format($recipe->cost ?? 0, 2) }}</td>
                                <td class="px-4 py-3">{{ $recipe->updated_at ? $recipe->updated_at->format('d-m-Y H:i') : '-' }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('owner.recipes.show', $recipe->id) }}" title="{{ __('Bekijken') }}" class="mr-2">👁️</a>
                                    <button wire:click="edit({{ $recipe->id }})" title="{{ __('Bewerken') }}" class="mr-2">✏️</button>
                                    <button wire:click="delete({{ $recipe->id }})" wire:confirm="Weet je zeker dat je dit recept wilt verwijderen?" title="{{ __('Verwijderen') }}" class="text-red-600">🗑️</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-8 text-center" colspan="7">
                                    <h2 class="text-gray-500">{{ __('Geen recepten gevonden.') }}</h2>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $recipes->links() }}
            </div>

            <!-- Modal for creating/editing -->
            @if($showModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center" style="display: flex;">
                <div class="bg-white dark:bg-zinc-800 p-8 rounded-lg shadow-lg w-1/2 max-h-screen overflow-y-auto" @click.away="$wire.closeModal()">
                    <h2 class="text-2xl font-semibold mb-4">{{ $isEditing ? __('Recept bewerken') : __('Recept toevoegen') }}</h2>
                    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                        @csrf
                        <h3 class="text-lg font-semibold mb-2">{{ __('Basisinformatie') }}</h3>
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Receptnaam') }}</label>
                            <input type="text" id="name" wire:model="name" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border @error('name') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Categorie') }}</label>
                            <select id="category_id" wire:model="category_id" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border @error('category_id') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Selecteer een categorie</option>
                                @foreach($allCategories as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="preparation_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Bereidingstijd (minuten)') }}</label>
                            <input type="number" id="preparation_time" wire:model="preparation_time" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border @error('preparation_time') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('preparation_time') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="selling_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Verkoopprijs (€)') }}</label>
                            <input type="number" step="0.01" id="selling_price" wire:model="selling_price" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border @error('selling_price') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('selling_price') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="portion_size_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Portie grootte') }}</label>
                            <div class="flex gap-2">
                                <input type="number" id="portion_size_quantity" wire:model="portion_size_quantity" class="mt-1 block w-1/2 px-3 py-2 bg-white dark:bg-zinc-700 border @error('portion_size_quantity') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="{{ __('Aantal') }}">
                                <select id="portion_size_unit_id" wire:model="portion_size_unit_id" class="w-1/2 mt-1 block px-3 py-2 bg-white dark:bg-zinc-700 border @error('portion_size_unit_id') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Selecteer eenheid</option>
                                    @foreach($allUnits as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('portion_size_quantity') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            @error('portion_size_unit_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <h3 class="text-lg font-semibold mb-2 mt-6">{{ __('Ingrediëntenlijst') }}</h3>
                        <div>
                            @foreach($ingredientsList as $index => $item)
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="flex-1">
                                        <select wire:model="ingredientsList.{{ $index }}.id" class="w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm">
                                            <option value="">Selecteer ingrediënt</option>
                                            @foreach($allIngredients as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="number" wire:model="ingredientsList.{{ $index }}.quantity" placeholder="Hoeveelheid" class="w-1/4 px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm">
                                    <div class="flex-1">
                                        <select wire:model="ingredientsList.{{ $index }}.unit_id" class="w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm">
                                            <option value="">Selecteer eenheid</option>
                                            @foreach($allUnits as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" wire:click="removeIngredient({{ $index }})" class="text-red-600">🗑️</button>
                                </div>
                            @endforeach
                            @error('ingredientsList') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            <button type="button" wire:click="addIngredient" class="mt-2 text-sm text-blue-600">+ {{ __('Ingrediënt toevoegen') }}</button>
                        </div>

                        <h3 class="text-lg font-semibold mb-2 mt-6">{{ __('Beschrijving & Bereiding') }}</h3>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Beschrijving') }}</label>
                            <textarea id="description" wire:model="description" rows="3" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border @error('description') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm"></textarea>
                            @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Bereidingsstappen') }}</label>
                            <textarea id="instructions" wire:model="instructions" rows="5" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border @error('instructions') border-red-500 @else border-gray-300 dark:border-zinc-600 @enderror rounded-md shadow-sm"></textarea>
                            @error('instructions') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Foto') }}</label>
                            <input type="file" id="photo" wire:model="photo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            @if ($photo)
                                <img src="{{ $photo->temporaryUrl() }}" class="mt-2 h-20 w-20 object-cover rounded-md">
                            @elseif ($existingPhoto)
                                <img src="{{ Storage::url($existingPhoto) }}" class="mt-2 h-20 w-20 object-cover rounded-md">
                            @endif
                            @error('photo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="button" wire:click="closeModal" class="mr-2 inline-flex items-center px-4 py-2 border rounded-md text-sm bg-zinc-200 text-zinc-800 hover:bg-zinc-300 dark:bg-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-600">{{ __('Annuleren') }}</button>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border rounded-md text-sm bg-accent text-white hover:bg-amber-700 dark:bg-accent dark:text-white dark:hover:bg-amber-500">{{ $isEditing ? __('Wijzigingen opslaan') : __('Recept opslaan') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
