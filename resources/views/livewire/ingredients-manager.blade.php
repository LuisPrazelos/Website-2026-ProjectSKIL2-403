<div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
    <div class="relative h-full flex-1 overflow-hidden rounded-[2.5rem] border border-zinc-100 dark:border-zinc-800 p-8 bg-white dark:bg-zinc-900 shadow-sm transition-all duration-500 hover:shadow-2xl">
        <!-- Header area -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-black italic tracking-tighter text-zinc-900 dark:text-white uppercase">{{ __('Ingrediënten beheren') }}</h1>
                <p class="text-xs font-black uppercase tracking-[0.2em] text-zinc-400 mt-2">{{ __('Beheer je voorraad en allergenen met precisie.') }}</p>
            </div>
            <div>
                <button wire:click="$set('showAddModal', true)" class="inline-flex items-center px-6 py-4 rounded-2xl bg-accent text-white font-black uppercase italic tracking-widest hover:bg-amber-700 transition-all shadow-xl shadow-accent/20 active:scale-[0.98] gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Toevoegen') }}
                </button>
            </div>
        </div>

        <!-- Success Message -->
        @if(session()->has('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl shadow-sm animate-fade-in-down">
                <p class="text-sm text-green-700 font-bold uppercase tracking-wide">{{ session('success') }}</p>
            </div>
        @endif

        <div class="mb-6 relative">
            <input wire:model.debounce.300ms="search" type="text" placeholder="{{ __('Zoek ingrediënt...') }}" class="w-full rounded-2xl border-zinc-100 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50 py-4 px-6 text-sm font-medium focus:ring-accent focus:border-accent transition-all dark:text-white">
            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-zinc-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-3xl border border-zinc-100 dark:border-zinc-800">
            <table class="min-w-full divide-y divide-zinc-100 dark:divide-zinc-800">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">{{ __('Naam') }}</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">{{ __('Eenheid') }}</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">{{ __('Allergenen') }}</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">{{ __('Acties') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($ingredients as $ingredient)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="text-sm font-black text-zinc-900 dark:text-white uppercase italic tracking-tight">{{ $ingredient->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold text-zinc-500 uppercase tracking-widest">{{ $ingredient->measurementUnit->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($ingredient->ingredientAllergies as $ia)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-tighter bg-red-100 text-red-700 border border-red-200">
                                            {{ $ia->allergy->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <button wire:click="edit({{ $ingredient->id }})" class="text-zinc-400 hover:text-accent transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button wire:click="destroy({{ $ingredient->id }})" onclick="return confirm('Weet je het zeker?')" class="text-zinc-400 hover:text-red-500 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <span class="text-sm font-medium text-zinc-400 italic">{{ __('Geen ingrediënten gevonden.') }}</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $ingredients->links() }}
        </div>
    </div>

    <!-- Modals (Add & Edit) -->
    @if($showAddModal || $showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-6 backdrop-blur-sm bg-black/40 animate-fade-in">
            <div class="w-full max-w-xl bg-white dark:bg-zinc-900 rounded-[2.5rem] shadow-2xl border border-zinc-100 dark:border-zinc-800 overflow-hidden">
                <div class="bg-accent p-8 text-white relative">
                    <h2 class="text-2xl font-black uppercase italic tracking-tighter">{{ $showAddModal ? __('Nieuw Ingrediënt') : __('Ingrediënt Bewerken') }}</h2>
                    <button wire:click="$set('{{ $showAddModal ? 'showAddModal' : 'showEditModal' }}', false)" class="absolute top-8 right-8 text-white/80 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="{{ $showAddModal ? 'store' : 'update' }}" class="p-8 space-y-6">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400 mb-2">Naam</label>
                        <input type="text" wire:model.defer="name" class="w-full rounded-2xl border-zinc-100 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50 py-4 px-6 text-sm font-bold focus:ring-accent focus:border-accent dark:text-white">
                        @error('name') <span class="text-[10px] text-red-500 font-black uppercase tracking-widest mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400 mb-2">Eenheid</label>
                        <select wire:model.defer="unit_id" class="w-full rounded-2xl border-zinc-100 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50 py-4 px-6 text-sm font-bold focus:ring-accent focus:border-accent dark:text-white">
                            <option value="">{{ __('Kies een eenheid') }}</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                        @error('unit_id') <span class="text-[10px] text-red-500 font-black uppercase tracking-widest mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400 mb-2">Allergenen</label>
                        <div class="grid grid-cols-2 gap-3 max-h-48 overflow-y-auto pr-2 scrollbar-thin">
                            @foreach($allergens as $allergen)
                                <label class="relative flex items-center gap-3 p-3 rounded-xl border border-zinc-100 dark:border-zinc-800 cursor-pointer transition-all hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                    <input type="checkbox" wire:model.defer="selectedAllergens" value="{{ $allergen->allergyId }}" class="h-4 w-4 rounded border-zinc-300 text-accent focus:ring-accent">
                                    <span class="text-xs font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-tight">{{ $allergen->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4 flex gap-4">
                        <button type="button" wire:click="$set('{{ $showAddModal ? 'showAddModal' : 'showEditModal' }}', false)" class="flex-1 py-4 rounded-2xl bg-zinc-100 dark:bg-zinc-800 text-zinc-500 font-black uppercase tracking-widest text-xs hover:bg-zinc-200 transition-all active:scale-[0.98]">
                            {{ __('Annuleren') }}
                        </button>
                        <button type="submit" class="flex-2 px-8 py-4 rounded-2xl bg-accent text-white font-black uppercase italic tracking-widest text-xs hover:bg-amber-700 transition-all shadow-xl shadow-accent/20 active:scale-[0.98]">
                            {{ __('Opslaan') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
