<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Mijn Evenementen (Happenings)</h1>
            <p class="text-sm text-gray-600 mt-1">Bekijk en beheer je evenementen.</p>
        </div>
    </div>

    <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded-md shadow-sm p-4">
        <table class="min-w-full text-left">
            <thead>
                <tr>
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Datum</th>
                    <th class="px-4 py-3">Aantal Personen</th>
                    <th class="px-4 py-3">Acties</th>
                </tr>
            </thead>
            <tbody>
                @forelse($happenings as $happening)
                    <tr
                        wire:key="{{ $happening->id }}"
                        class="border-t transition-colors duration-200 {{ in_array($happening->id, $acceptedHappenings) ? 'bg-green-100 dark:bg-green-900/50' : 'hover:bg-gray-50 dark:hover:bg-zinc-800' }}"
                    >
                        <td class="px-4 py-3 cursor-pointer" wire:click="showDetails({{ $happening->id }})">#{{ $happening->id }}</td>
                        <td class="px-4 py-3 cursor-pointer" wire:click="showDetails({{ $happening->id }})">{{ $happening->event_date->format('d-m-Y H:i') }}</td>
                        <td class="px-4 py-3 cursor-pointer" wire:click="showDetails({{ $happening->id }})">{{ $happening->person_count }}</td>
                        <td class="px-4 py-3 flex items-center space-x-2">
                            <button
                                wire:click.stop="acceptHappening({{ $happening->id }})"
                                title="Accepteren"
                                class="transition-all duration-200 hover:scale-110 {{ in_array($happening->id, $acceptedHappenings) ? 'text-green-500' : 'text-gray-400 hover:text-green-500' }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </button>
                            <button wire:click="showDetails({{ $happening->id }})" title="Bekijk" class="text-gray-500 hover:text-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                            <button wire:click.stop="delete({{ $happening->id }})" wire:confirm="Weet je zeker dat je dit evenement wilt verwijderen?" title="Verwijderen" class="text-gray-500 hover:text-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-8 text-center" colspan="4">
                            <h2 class="text-gray-500">Geen evenementen gevonden.</h2>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal for Happening Details -->
    @if($selectedHappening)
        <div
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            x-data="{}"
            @keydown.escape.window="$wire.closeDetails()"
        >
            <div
                @click.away="$wire.closeDetails()"
                class="bg-white dark:bg-zinc-800 p-8 rounded-lg shadow-lg w-full max-w-2xl relative"
            >
                <button @click="$wire.closeDetails()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>

                <h2 class="text-2xl font-semibold mb-4">Details van Evenement #{{ $selectedHappening->id }}</h2>

                <div class="border-t border-gray-200 dark:border-zinc-700 my-4"></div>

                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold text-lg">Bericht</h3>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">{{ $selectedHappening->message }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="p-3 bg-gray-50 dark:bg-zinc-700 rounded-md">
                            <strong>Datum:</strong><br>
                            {{ $selectedHappening->event_date->format('d-m-Y H:i') }}
                        </div>
                        <div class="p-3 bg-gray-50 dark:bg-zinc-700 rounded-md">
                            <strong>Aantal personen:</strong><br>
                            {{ $selectedHappening->person_count }}
                        </div>
                        <div class="p-3 bg-gray-50 dark:bg-zinc-700 rounded-md">
                            <strong>Prijs per persoon:</strong><br>
                            €{{ number_format($selectedHappening->price_per_person, 2, ',', '.') }}
                        </div>
                        <div class="p-3 bg-gray-50 dark:bg-zinc-700 rounded-md">
                            <strong>Status:</strong><br>
                            {{ $selectedHappening->status->name ?? 'Onbekend' }}
                        </div>
                    </div>
                     <div class="p-3 bg-gray-50 dark:bg-zinc-700 rounded-md text-sm">
                        <strong>Thema:</strong><br>
                        {{ $selectedHappening->theme->name ?? 'Geen thema' }}
                    </div>

                    <div class="mt-6">
                        <label class="block font-semibold text-lg mb-2">Opmerkingen</label>
                        <textarea
                            wire:model="remarks"
                            placeholder="Voeg hier je opmerkingen in..."
                            class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            rows="5"
                        ></textarea>
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <button
                            wire:click="closeDetails()"
                            class="px-4 py-2 bg-gray-300 dark:bg-zinc-700 text-gray-900 dark:text-white rounded-md hover:bg-gray-400 dark:hover:bg-zinc-600 transition"
                        >
                            Annuleren
                        </button>
                        <button
                            wire:click="saveRemarks()"
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition"
                        >
                            Opmerkingen Opslaan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
