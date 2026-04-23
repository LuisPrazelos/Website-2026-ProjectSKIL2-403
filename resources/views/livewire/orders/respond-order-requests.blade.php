<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Reageren op evenement aanvragen</h1>
            <p class="text-sm text-gray-600 mt-1">Bekijk de binnengekomen evenementen en reageer op maat.</p>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="flex gap-4 mb-6">
        <input
            wire:model.live="searchQuery"
            type="text"
            placeholder="Zoek klant of evenement nummer..."
            class="flex-1 px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        {{-- Status filtering removed as per migration update --}}
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded-md shadow-sm p-4">
        <table class="min-w-full text-left text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-zinc-700">
                    <th class="px-4 py-3 font-semibold text-gray-900 dark:text-white">Evenement nr</th>
                    <th class="px-4 py-3 font-semibold text-gray-900 dark:text-white">Klantnaam</th>
                    <th class="px-4 py-3 font-semibold text-gray-900 dark:text-white">Boodschap</th>
                    <th class="px-4 py-3 font-semibold text-gray-900 dark:text-white">Aantal / datum</th>
                    <th class="px-4 py-3 font-semibold text-gray-900 dark:text-white">Thema</th>
                    <th class="px-4 py-3 font-semibold text-gray-900 dark:text-white">Acties</th>
                </tr>
            </thead>
            <tbody>
                @forelse($happenings as $happening)
                    <tr wire:key="{{ $happening->id }}" class="border-b border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800 transition">
                        <td class="px-4 py-3 text-blue-600 dark:text-blue-400 font-semibold">#{{ $happening->id }}</td>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $happening->user->first_name ?? '' }} {{ $happening->user->last_name ?? 'Onbekend' }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                            {{ Str::limit($happening->message, 30) }}
                        </td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                            {{ $happening->person_count }} pers - {{ $happening->event_date->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $themeColors = [
                                    'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
                                    'bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300',
                                    'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                                    'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
                                    'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                    'bg-pink-100 text-pink-700 dark:bg-pink-900 dark:text-pink-300',
                                    'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300',
                                    'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300',
                                    'bg-teal-100 text-teal-700 dark:bg-teal-900 dark:text-teal-300',
                                    'bg-cyan-100 text-cyan-700 dark:bg-cyan-900 dark:text-cyan-300',
                                ];
                                $color = $happening->theme
                                    ? $themeColors[$happening->theme->id % count($themeColors)]
                                    : 'bg-gray-100 text-gray-600 dark:bg-zinc-700 dark:text-gray-400';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                {{ $happening->theme->name ?? '-' }}
                            </span>
                        </td>

                        <td class="px-4 py-3 flex items-center gap-2">
                            <a href="{{ route('owner.respond-order-requests.respond', $happening->id) }}" wire:navigate class="text-gray-500 hover:text-green-600 transition" title="Beantwoorden">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </a>
                            <a href="{{ route('owner.respond-order-requests.view', $happening->id) }}" wire:navigate class="text-gray-500 hover:text-blue-500 transition" title="Bekijk">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-8 text-center" colspan="6">
                            <h2 class="text-gray-500 dark:text-gray-400">Geen evenementen gevonden.</h2>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex items-center justify-between">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Toont {{ $happenings->firstItem() ?? 0 }}-{{ $happenings->lastItem() ?? 0 }} van {{ $happenings->total() ?? 0 }} evenementen
        </div>
        <div>
            {{ $happenings->links() }}
        </div>
    </div>

    <!-- Info message -->
    <p class="mt-6 text-sm text-gray-500 dark:text-gray-400">
        ℹ️ Na het versturen ontvangen de klanten een e-mail met het antwoord op zijn voorstel.
    </p>
</div>
