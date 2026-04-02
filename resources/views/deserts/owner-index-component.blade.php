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
                    <button wire:click="$set('showAddModal', true)" class="inline-flex items-center px-4 py-2 border rounded-md text-sm bg-accent text-white hover:bg-amber-700 dark:bg-accent dark:text-white dark:hover:bg-amber-500">+ {{ __('Dessert toevoegen') }}</button>
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
                            <th class="px-4 py-3">{{ __('Kostprijs') }}</th>
                            <th class="px-4 py-3">{{ __('Portie') }}</th>
                            <th class="px-4 py-3">{{ __('Beschikbaar') }}</th>
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
                                <td class="px-4 py-3 text-sm font-semibold">€{{ number_format($desert->cost_price, 2) }}</td>
                                <td class="px-4 py-3">
                                    {{ $desert->portion_size }} {{ $desert->measurementUnit ? $desert->measurementUnit->name : '' }}
                                </td>
                                <td class="px-4 py-3">
                                    <button wire:click="toggleAvailability({{ $desert->id }})"
                                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 {{ $desert->is_available ? 'bg-green-500' : 'bg-gray-200' }}"
                                            role="switch" aria-checked="{{ $desert->is_available ? 'true' : 'false' }}">
                                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $desert->is_available ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                    </button>
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
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-zinc-800 p-8 rounded-lg shadow-lg w-full max-w-lg overflow-y-auto max-h-[90vh]">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">{{ __('Dessert toevoegen') }}</h2>
                    <form wire:submit.prevent="store">
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Naam <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prijs <span class="text-red-500">*</span></label>
                                <input type="number" wire:model.defer="price" id="price" step="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="portion_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Aantal <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model.defer="portion_size" id="portion_size" step="0.1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                    @error('portion_size') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="measurement_unit_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Eenheid <span class="text-red-500">*</span></label>
                                    <select wire:model.defer="measurement_unit_id" id="measurement_unit_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                        <option value="">Kies een eenheid</option>
                                        @foreach($measurementUnits as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('measurement_unit_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Beschrijving <span class="text-red-500">*</span></label>
                                <textarea wire:model.defer="description" id="description" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white"></textarea>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="recipe_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gekoppeld Recept <span class="text-red-500">*</span></label>
                                <select wire:model.defer="recipe_id" id="recipe_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                    <option value="">Kies een recept</option>
                                    @foreach($recipes as $recipe)
                                        <option value="{{ $recipe->id }}">{{ $recipe->name }}</option>
                                    @endforeach
                                </select>
                                @error('recipe_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" wire:model.defer="is_available" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 dark:bg-zinc-700 dark:border-zinc-600">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Beschikbaar</span>
                                </label>
                                @error('is_available') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nieuwe afbeelding uploaden</label>
                                <input type="file" wire:model="photo" id="photo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:text-gray-400 dark:file:bg-zinc-700 dark:file:text-indigo-300">
                                @if ($photo)
                                    <img src="{{ $photo->temporaryUrl() }}" class="mt-2 w-20 h-20 object-cover rounded-md">
                                @endif
                                @error('photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="picture_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Of kies bestaande afbeelding</label>
                                <select wire:model.defer="picture_id" id="picture_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                    <option value="">Geen afbeelding</option>
                                    @foreach($pictures as $picture)
                                        <option value="{{ $picture->id }}">{{ $picture->title }}</option>
                                    @endforeach
                                </select>
                                @error('picture_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end space-x-4">
                            <button type="button" wire:click="$set('showAddModal', false)" class="px-4 py-2 border rounded-md text-sm bg-zinc-200 text-zinc-800 hover:bg-zinc-300 dark:bg-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-600">Annuleren</button>
                            <button type="submit" class="px-4 py-2 border rounded-md text-sm bg-accent text-white hover:bg-amber-700 dark:bg-accent dark:text-white dark:hover:bg-amber-500">Opslaan</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Edit Dessert Modal -->
        @if($showEditModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-zinc-800 p-8 rounded-lg shadow-lg w-full max-w-lg overflow-y-auto max-h-[90vh]">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">{{ __('Dessert bewerken') }}</h2>
                    <form wire:submit.prevent="update">
                        <div class="space-y-4">
                            <div>
                                <label for="edit_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Naam <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="name" id="edit_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div x-data="{ currentPrice: @entangle('price'), originalPrice: @entangle('originalPrice') }">
                                <label for="edit_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prijs <span class="text-red-500">*</span></label>
                                <input type="number" x-model="currentPrice" id="edit_price" step="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                                <div x-show="currentPrice != originalPrice && originalPrice > 0" class="mt-2 text-sm">
                                    <span x-text="((currentPrice - originalPrice) / originalPrice * 100).toFixed(2) > 0 ? 'Prijs is gestegen met ' + ((currentPrice - originalPrice) / originalPrice * 100).toFixed(2) + '%' : 'Prijs is gedaald met ' + Math.abs(((currentPrice - originalPrice) / originalPrice * 100).toFixed(2)) + '%'"
                                          :class="((currentPrice - originalPrice) / originalPrice * 100) > 0 ? 'text-red-600' : 'text-green-600'">
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="edit_portion_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Aantal <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model.defer="portion_size" id="edit_portion_size" step="0.1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                    @error('portion_size') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="edit_measurement_unit_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Eenheid <span class="text-red-500">*</span></label>
                                    <select wire:model.defer="measurement_unit_id" id="edit_measurement_unit_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                        <option value="">Kies een eenheid</option>
                                        @foreach($measurementUnits as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('measurement_unit_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label for="edit_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Beschrijving <span class="text-red-500">*</span></label>
                                <textarea wire:model.defer="description" id="edit_description" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white"></textarea>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="edit_recipe_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gekoppeld Recept <span class="text-red-500">*</span></label>
                                <select wire:model.defer="recipe_id" id="edit_recipe_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                    <option value="">Kies een recept</option>
                                    @foreach($recipes as $recipe)
                                        <option value="{{ $recipe->id }}">{{ $recipe->name }}</option>
                                    @endforeach
                                </select>
                                @error('recipe_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" wire:model.defer="is_available" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 dark:bg-zinc-700 dark:border-zinc-600">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Beschikbaar</span>
                                </label>
                                @error('is_available') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="edit_photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nieuwe afbeelding uploaden</label>
                                <input type="file" wire:model="photo" id="edit_photo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:text-gray-400 dark:file:bg-zinc-700 dark:file:text-indigo-300">
                                @if ($photo)
                                    <img src="{{ $photo->temporaryUrl() }}" class="mt-2 w-20 h-20 object-cover rounded-md">
                                @endif
                                @error('photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="edit_picture_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Of kies bestaande afbeelding</label>
                                <select wire:model.defer="picture_id" id="edit_picture_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                    <option value="">Geen afbeelding</option>
                                    @foreach($pictures as $picture)
                                        <option value="{{ $picture->id }}">{{ $picture->title }}</option>
                                    @endforeach
                                </select>
                                @error('picture_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end space-x-4">
                            <button type="button" wire:click="$set('showEditModal', false)" class="px-4 py-2 border rounded-md text-sm bg-zinc-200 text-zinc-800 hover:bg-zinc-300 dark:bg-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-600">Annuleren</button>
                            <button type="submit" class="px-4 py-2 border rounded-md text-sm bg-accent text-white hover:bg-amber-700 dark:bg-accent dark:text-white dark:hover:bg-amber-500">Opslaan</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
