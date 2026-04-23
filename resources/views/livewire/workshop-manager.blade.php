<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 bg-transparent">
            <!-- Header area -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold">{{ __('Workshops beheren') }}</h1>
                    <p class="text-sm text-zinc-600 mt-1">{{ __('Beheer je workshops hier: zoek, voeg toe, bewerk of verwijder items.') }}</p>
                </div>
                <div>
                    <button wire:click="create" class="inline-flex items-center px-4 py-2 border rounded-md text-sm bg-accent text-white hover:bg-amber-700 dark:bg-accent dark:text-white dark:hover:bg-amber-500">+ {{ __('Workshop toevoegen') }}</button>
                </div>
            </div>

            <!-- Success Message -->
            @if(session()->has('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <input wire:model.live.debounce.300ms="search" type="text" placeholder="{{ __('Zoek workshop...') }}" class="w-full rounded-md border-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm mb-4 dark:bg-zinc-900 dark:border-zinc-700 dark:text-zinc-200">

            <!-- Table -->
            <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded-md shadow-sm p-4">
                <table class="min-w-full text-left">
                    <thead>
                        <tr>
                            <th class="px-4 py-3">{{ __('Naam') }}</th>
                            <th class="px-4 py-3">{{ __('Datum') }}</th>
                            <th class="px-4 py-3">{{ __('Locatie') }}</th>
                            <th class="px-4 py-3">{{ __('Prijs Volw.') }}</th>
                            <th class="px-4 py-3">{{ __('Prijs Kind') }}</th>
                            <th class="px-4 py-3">{{ __('Max Deeln.') }}</th>
                            <th class="px-4 py-3">{{ __('Bewerken') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($workshops as $workshop)
                            <tr class="border-t border-zinc-200 dark:border-zinc-700">
                                <td class="px-4 py-3 font-medium">{{ $workshop->name }}</td>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($workshop->date)->format('d-m-Y H:i') }}</td>
                                <td class="px-4 py-3">{{ $workshop->location }}</td>
                                <td class="px-4 py-3">€{{ number_format($workshop->price_adults, 2) }}</td>
                                <td class="px-4 py-3">€{{ number_format($workshop->price_children, 2) }}</td>
                                <td class="px-4 py-3">{{ $workshop->max_participants }}</td>
                                <td class="px-4 py-3 flex items-center space-x-2">
                                    <button wire:click="edit({{ $workshop->workshopId }})" title="{{ __('Bewerken') }}" class="text-blue-600 hover:text-blue-900">✏️</button>
                                    <button wire:click="destroy({{ $workshop->workshopId }})" onclick="return confirm('{{ __('Weet je zeker dat je :name op :date wilt verwijderen?', ['name' => $workshop->name, 'date' => \Carbon\Carbon::parse($workshop->date)->format('d-m-Y')]) }}');" title="{{ __('Verwijderen') }}" class="text-red-600 hover:text-red-900">🗑️</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-8 text-center text-zinc-500 dark:text-zinc-400" colspan="7">
                                    <h2 class="text-lg font-semibold">{{ __('Geen workshops gevonden.') }}</h2>
                                    <p class="mt-2">{{ __('Voeg een workshop toe via de knop rechtsboven.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $workshops->links() }}
            </div>
        </div>

        <!-- Add Workshop Modal -->
        @if($showAddModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-zinc-800 p-8 rounded-lg shadow-lg w-full max-w-lg overflow-y-auto max-h-[90vh]">
                    <h2 class="text-2xl font-semibold mb-4">{{ __('Workshop toevoegen') }}</h2>
                    <form wire:submit.prevent="store">
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Naam <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="name" id="name" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="date" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Datum en Tijd <span class="text-red-500">*</span></label>
                                <input type="datetime-local" wire:model.defer="date" id="date" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                @error('date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="price_adults" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Prijs Volwassenen <span class="text-red-500">*</span></label>
                                <input type="number" wire:model.defer="price_adults" id="price_adults" step="0.01" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                @error('price_adults') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="price_children" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Prijs Kinderen <span class="text-red-500">*</span></label>
                                <input type="number" wire:model.defer="price_children" id="price_children" step="0.01" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                @error('price_children') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="location" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Locatie <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="location" id="location" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                @error('location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="duration_minutes" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Duur (minuten) <span class="text-red-500">*</span></label>
                                <input type="number" wire:model.defer="duration_minutes" id="duration_minutes" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                @error('duration_minutes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="max_participants" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Max. Deelnemers <span class="text-red-500">*</span></label>
                                <input type="number" wire:model.defer="max_participants" id="max_participants" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                @error('max_participants') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="description" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Beschrijving <span class="text-red-500">*</span></label>
                                <textarea wire:model.defer="description" id="description" rows="3" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white"></textarea>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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

        <!-- Edit Workshop Modal -->
        @if($showEditModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-zinc-800 p-8 rounded-lg shadow-lg w-full max-w-lg overflow-y-auto max-h-[90vh]">
                    <h2 class="text-2xl font-semibold mb-4">{{ __('Workshop bewerken') }}</h2>
                    <form wire:submit.prevent="update">
                        <div class="space-y-4">
                            <div>
                                <label for="edit_name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Naam <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="name" id="edit_name" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="edit_date" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Datum en Tijd <span class="text-red-500">*</span></label>
                                <input type="datetime-local" wire:model.defer="date" id="edit_date" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                @error('date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="edit_price_adults" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Prijs Volwassenen <span class="text-red-500">*</span></label>
                                <input type="number" wire:model.defer="price_adults" id="edit_price_adults" step="0.01" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                @error('price_adults') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="edit_price_children" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Prijs Kinderen <span class="text-red-500">*</span></label>
                                <input type="number" wire:model.defer="price_children" id="edit_price_children" step="0.01" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                @error('price_children') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="edit_location" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Locatie <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="location" id="edit_location" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                @error('location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="edit_duration_minutes" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Duur (minuten) <span class="text-red-500">*</span></label>
                                <input type="number" wire:model.defer="duration_minutes" id="edit_duration_minutes" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                @error('duration_minutes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="edit_max_participants" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Max. Deelnemers <span class="text-red-500">*</span></label>
                                <input type="number" wire:model.defer="max_participants" id="edit_max_participants" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                @error('max_participants') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="edit_description" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Beschrijving <span class="text-red-500">*</span></label>
                                <textarea wire:model.defer="description" id="edit_description" rows="3" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white"></textarea>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
