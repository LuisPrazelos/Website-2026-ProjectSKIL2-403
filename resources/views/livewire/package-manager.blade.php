<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-transparent">
        <div class="mb-6">
            <div>
                <h1 class="text-2xl font-semibold">{{ __('Pakketten Beheren') }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Beheer alle beschikbare pakketten voor klanten.') }}</p>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-md">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        @foreach($errors->all() as $error)
                            <p class="text-red-800 dark:text-red-300">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white dark:bg-zinc-900 rounded-md shadow-sm p-6 space-y-6">
            <div class="flex gap-4 items-center">
                <div class="flex-1">
                    <input
                        wire:model.live="searchQuery"
                        type="text"
                        placeholder="{{ __('Zoeken naar pakket...') }}"
                        class="w-full px-4 py-2 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 dark:text-gray-100"
                    >
                </div>
                <button
                    wire:click="openCreateForm"
                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-md text-sm font-medium bg-orange-600 hover:bg-orange-700 text-white transition-colors shadow-md hover:shadow-lg"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Pakket Toevoegen') }}
                </button>
            </div>

            @if($showForm)
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-[9999] p-4">
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl p-8 w-full max-w-md border border-white/10 dark:border-zinc-700/50 max-h-[90vh] overflow-y-auto">
                        <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">
                            {{ $editingPackageId ? __('Pakket Bewerken') : __('Nieuw Pakket Aanmaken') }}
                        </h2>

                        <form wire:submit="savePackage" class="space-y-5">
                            <div>
                                <label for="packageName" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    {{ __('Pakketnaam') }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <input
                                    wire:model="packageName"
                                    type="text"
                                    id="packageName"
                                    placeholder="{{ __('Bijv: Standaard, Premium, Luxe') }}"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-zinc-800/50 border border-gray-300 dark:border-zinc-600/50 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:focus:ring-orange-400 text-gray-900 dark:text-gray-100 transition-all"
                                >
                            </div>

                            <div>
                                <label for="packageDescription" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    {{ __('Beschrijving') }}
                                </label>
                                <textarea
                                    wire:model="packageDescription"
                                    id="packageDescription"
                                    rows="4"
                                    placeholder="{{ __('Geef een korte beschrijving van dit pakket.') }}"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-zinc-800/50 border border-gray-300 dark:border-zinc-600/50 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:focus:ring-orange-400 text-gray-900 dark:text-gray-100 transition-all"
                                ></textarea>
                            </div>

                            <div class="flex items-center gap-3">
                                <input
                                    wire:model.live="isStandard"
                                    id="isStandard"
                                    type="checkbox"
                                    class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500"
                                >
                                <label for="isStandard" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Dit is het standaard pakket (gratis)') }}
                                </label>
                            </div>

                            <div>
                                <label for="packagePrice" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    {{ __('Prijs') }}
                                    @unless($isStandard)
                                        <span class="text-red-500">*</span>
                                    @endunless
                                </label>
                                <input
                                    wire:model="packagePrice"
                                    type="number"
                                    id="packagePrice"
                                    min="0"
                                    step="0.01"
                                    @disabled($isStandard)
                                    placeholder="{{ __('Bijv: 49.99') }}"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-zinc-800/50 border border-gray-300 dark:border-zinc-600/50 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:focus:ring-orange-400 text-gray-900 dark:text-gray-100 transition-all disabled:opacity-60"
                                >
                                @if($isStandard)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ __('Het standaard pakket is altijd gratis.') }}</p>
                                @endif
                            </div>

                            <div class="flex gap-3 justify-end pt-6 border-t border-gray-200 dark:border-zinc-700/50">
                                <button
                                    type="button"
                                    wire:click="closeForm"
                                    class="px-6 py-2 border border-gray-300 dark:border-zinc-600/50 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-all duration-200"
                                >
                                    {{ __('Annuleren') }}
                                </button>
                                <button
                                    type="submit"
                                    class="px-6 py-2 border border-transparent rounded-lg text-sm font-medium bg-gradient-to-r from-orange-600 to-orange-700 text-white hover:from-orange-700 hover:to-orange-800 transition-all duration-200 shadow-lg hover:shadow-xl"
                                >
                                    {{ $editingPackageId ? __('Bijwerken') : __('Toevoegen') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <div class="overflow-x-auto">
                @if(count($packages) > 0)
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Pakketnaam') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Beschrijving') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Type') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Prijs') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Acties') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($packages as $package)
                                <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $package['name'] }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-md">{{ $package['description'] ?: __('Geen beschrijving') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        @if($package['is_standard'])
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700 dark:bg-green-900/40 dark:text-green-300">{{ __('Standaard') }}</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-orange-100 px-3 py-1 text-xs font-medium text-orange-700 dark:bg-orange-900/40 dark:text-orange-300">{{ __('Betaald') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">€{{ number_format((float) ($package['price'] ?? 0), 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                        <button wire:click="editPackage({{ $package['id'] }})" class="inline-flex items-center text-orange-600 dark:text-orange-400 hover:text-orange-900 dark:hover:text-orange-300 transition-colors">{{ __('Bewerken') }}</button>
                                        <button wire:click="deletePackage({{ $package['id'] }})" wire:confirm="{{ __('Weet je zeker dat je dit pakket wilt verwijderen?') }}" class="inline-flex items-center text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors">{{ __('Verwijderen') }}</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-12">
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Geen pakketten gevonden') }}</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Maak je eerste pakket aan via de knop hierboven.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
