<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-transparent">
            <!-- Header area -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold">{{ __('Desserts beheren') }}</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ __('Beheer je desserts hier: zoek, voeg toe, bewerk of verwijder items.') }}</p>
                </div>
                <div>
                    <button wire:click="$set('showAddModal', true)" class="inline-flex items-center px-4 py-2 border rounded-md text-sm bg-black text-white">+ {{ __('Dessert toevoegen') }}</button>
                </div>
            </div>

            <!-- Success Message -->
            @if(session()->has('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <input wire:model.debounce.300ms="search" type="text" placeholder="{{ __('Zoek dessert...') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm mb-4">

            <!-- Table -->
            <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded-md shadow-sm p-4">
                <table class="min-w-full text-left">
                    <thead>
                        <tr>
                            <th class="px-4 py-3">{{ __('Afbeelding') }}</th>
                            <th class="px-4 py-3">{{ __('Naam') }}</th>
                            <th class="px-4 py-3">{{ __('Prijs') }}</th>
                            <th class="px-4 py-3">{{ __('Aantal') }}</th>
                            <th class="px-4 py-3">{{ __('Beschrijving') }}</th>
                            <th class="px-4 py-3">{{ __('Ingrediënten') }}</th>
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
                                <td class="px-4 py-3">{{ $desert->portion_size }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500 truncate max-w-xs">{{ $desert->description }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500">
                                    {{ $desert->ingredients->pluck('name')->join(', ') }}
                                </td>
                                <td class="px-4 py-3 flex items-center space-x-2">
                                    <button wire:click="edit({{ $desert->id }})" title="{{ __('Bewerken') }}" class="text-blue-600 hover:text-blue-900">✏️</button>
                                    <button wire:click="destroy({{ $desert->id }})" onclick="return confirm('{{ __('Weet je zeker dat je dit dessert wilt verwijderen?') }}');" title="{{ __('Verwijderen') }}" class="text-red-600 hover:text-red-900">🗑️</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-8 text-center" colspan="7">
                                    <h2 class="text-gray-500">{{ __('Geen desserts gevonden.') }}</h2>
                                    <p class="text-gray-400 mt-2">{{ __('Voeg een dessert toe via de knop rechtsboven.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $deserts->links() }}
            </div>
        </div>

        <!-- Add Dessert Modal -->
        @if($showAddModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white dark:bg-zinc-800 p-8 rounded-lg shadow-lg w-full max-w-lg">
                    <h2 class="text-2xl font-semibold mb-4">{{ __('Dessert toevoegen') }}</h2>
                    <form wire:submit.prevent="store">
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Naam <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prijs <span class="text-red-500">*</span></label>
                                <input type="number" wire:model.defer="price" id="price" step="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="portion_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Aantal <span class="text-red-500">*</span></label>
                                <input type="number" wire:model.defer="portion_size" id="portion_size" step="1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('portion_size') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Beschrijving <span class="text-red-500">*</span></label>
                                <textarea wire:model.defer="description" id="description" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="picture_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Afbeelding <span class="text-red-500">*</span></label>
                                <select wire:model.defer="picture_id" id="picture_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Geen afbeelding</option>
                                    @foreach($pictures as $picture)
                                        <option value="{{ $picture->id }}">{{ $picture->title }}</option>
                                    @endforeach
                                </select>
                                @error('picture_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="ingredients" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ingrediënten <span class="text-red-500">*</span></label>
                                <select wire:model.defer="ingredients" id="ingredients" multiple required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @foreach($allIngredients as $ingredient)
                                        <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                                    @endforeach
                                </select>
                                @error('ingredients') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end space-x-4">
                            <button type="button" wire:click="$set('showAddModal', false)" class="px-4 py-2 border rounded-md text-sm">Annuleren</button>
                            <button type="submit" class="px-4 py-2 border rounded-md text-sm bg-black text-white">Opslaan</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Edit Dessert Modal -->
        @if($showEditModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white dark:bg-zinc-800 p-8 rounded-lg shadow-lg w-full max-w-lg">
                    <h2 class="text-2xl font-semibold mb-4">{{ __('Dessert bewerken') }}</h2>
                    <form wire:submit.prevent="update">
                        <div class="space-y-4">
                            <div>
                                <label for="edit_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Naam <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="name" id="edit_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="edit_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prijs <span class="text-red-500">*</span></label>
                                <input type="number" wire:model.defer="price" id="edit_price" step="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="edit_portion_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Aantal <span class="text-red-500">*</span></label>
                                <input type="number" wire:model.defer="portion_size" id="edit_portion_size" step="1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('portion_size') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="edit_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Beschrijving <span class="text-red-500">*</span></label>
                                <textarea wire:model.defer="description" id="edit_description" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="edit_picture_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Afbeelding <span class="text-red-500">*</span></label>
                                <select wire:model.defer="picture_id" id="edit_picture_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Geen afbeelding</option>
                                    @foreach($pictures as $picture)
                                        <option value="{{ $picture->id }}">{{ $picture->title }}</option>
                                    @endforeach
                                </select>
                                @error('picture_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="edit_ingredients" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ingrediënten <span class="text-red-500">*</span></label>
                                <select wire:model.defer="ingredients" id="edit_ingredients" multiple required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @foreach($allIngredients as $ingredient)
                                        <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                                    @endforeach
                                </select>
                                @error('ingredients') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end space-x-4">
                            <button type="button" wire:click="$set('showEditModal', false)" class="px-4 py-2 border rounded-md text-sm">Annuleren</button>
                            <button type="submit" class="px-4 py-2 border rounded-md text-sm bg-black text-white">Opslaan</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
