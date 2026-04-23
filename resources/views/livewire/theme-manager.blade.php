<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-transparent">
        <!-- Header area inside big container -->
        <div class="mb-6">
            <div>
                <h1 class="text-2xl font-semibold">{{ __('Thema\'s Beheren') }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Beheer alle thema\'s voor evenementen.') }}</p>
            </div>
        </div>

        <!-- Success/Error Messages -->
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

        <!-- Main Container -->
        <div class="bg-white dark:bg-zinc-900 rounded-md shadow-sm p-6 space-y-6">
            <!-- Search Bar and Add Button -->
            <div class="flex gap-4 items-center">
                <div class="flex-1">
                    <input
                        wire:model.live="searchQuery"
                        type="text"
                        placeholder="{{ __('Zoeken naar thema...') }}"
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
                    {{ __('Thema Toevoegen') }}
                </button>
            </div>

            <!-- Create/Edit Form Modal -->
            @if($showForm)
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-[9999] p-4 animate-fade-in">
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl p-8 w-full max-w-md border border-white/10 dark:border-zinc-700/50 backdrop-blur-xl animate-scale-in max-h-[90vh] overflow-y-auto">
                        <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">
                            {{ $editingThemeId ? __('Thema Bewerken') : __('Nieuw Thema Aanmaken') }}
                        </h2>

                        <form wire:submit="saveTheme" class="space-y-5">
                            <div>
                                <label for="themeName" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    {{ __('Themanaam') }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <input
                                    wire:model="themeName"
                                    type="text"
                                    id="themeName"
                                    placeholder="{{ __('Bijv: Klassiek, Modern, Vintage, etc.') }}"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-zinc-800/50 border border-gray-300 dark:border-zinc-600/50 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:focus:ring-orange-400 text-gray-900 dark:text-gray-100 transition-all"
                                >
                                @error('themeName')
                                    <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex gap-3 justify-end pt-6 border-t border-gray-200 dark:border-zinc-700/50">
                                <button
                                    type="button"
                                    wire:click="closeForm"
                                    class="px-6 py-2 border border-gray-300 dark:border-zinc-600/50 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-all duration-200 backdrop-blur-sm"
                                >
                                    {{ __('Annuleren') }}
                                </button>
                                <button
                                    type="submit"
                                    class="px-6 py-2 border border-transparent rounded-lg text-sm font-medium bg-gradient-to-r from-orange-600 to-orange-700 text-white hover:from-orange-700 hover:to-orange-800 transition-all duration-200 shadow-lg hover:shadow-xl"
                                >
                                    {{ $editingThemeId ? __('Bijwerken') : __('Toevoegen') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <style>
                    @keyframes fadeIn {
                        from {
                            opacity: 0;
                        }
                        to {
                            opacity: 1;
                        }
                    }

                    @keyframes scaleIn {
                        from {
                            opacity: 0;
                            transform: scale(0.95);
                        }
                        to {
                            opacity: 1;
                            transform: scale(1);
                        }
                    }

                    .animate-fade-in {
                        animation: fadeIn 0.2s ease-out;
                    }

                    .animate-scale-in {
                        animation: scaleIn 0.3s ease-out;
                    }
                </style>
            @endif

            <!-- Themes Table -->
            <div class="overflow-x-auto">
                @if(count($themes) > 0)
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Themanaam') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Aangemaakt op') }}
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Acties') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($themes as $theme)
                                <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $theme['name'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($theme['created_at'])->format('d-m-Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                        <button
                                            wire:click="editTheme({{ $theme['id'] }})"
                                            class="inline-flex items-center text-orange-600 dark:text-orange-400 hover:text-orange-900 dark:hover:text-orange-300 transition-colors"
                                        >
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            {{ __('Bewerken') }}
                                        </button>
                                        <button
                                            wire:click="deleteTheme({{ $theme['id'] }})"
                                            wire:confirm="{{ __('Weet je zeker dat je dit thema wilt verwijderen?') }}"
                                            class="inline-flex items-center text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors"
                                        >
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            {{ __('Verwijderen') }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Geen thema\'s gevonden') }}</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Maak je eerste thema aan door op de knop hieronder te klikken.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

