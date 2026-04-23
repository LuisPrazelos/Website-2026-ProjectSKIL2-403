<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <!-- Header -->
    <div class="relative w-full overflow-hidden rounded-xl bg-cover bg-center h-40" style="background-image: url('/Pictures/Gebakjes.jpg');">
        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white drop-shadow-lg">{{ __('Evenement #' . $happening->id) }}</h1>
                <p class="text-white text-lg mt-2">{{ __('Aanvraagdetails van') }} {{ $happening->user->first_name }} {{ $happening->user->last_name }}</p>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 px-4">
        <a href="{{ route('owner.respond-order-requests') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:underline" wire:navigate>
            {{ __('Reageren op aanvragen') }}
        </a>
        <span>></span>
        <span>{{ __('Evenement #' . $happening->id) }}</span>
    </div>

    <!-- Main Content -->
    <div class="relative flex flex-1 flex-col rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800 overflow-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- Left: Evenement Details -->
            <div>
                <h2 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">{{ __('Evenement details') }}</h2>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Klantnaam') }}</label>
                        <p class="text-gray-900 dark:text-white font-semibold mt-1">{{ $happening->user->first_name }} {{ $happening->user->last_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Gewenste datum') }}</label>
                        <p class="text-gray-900 dark:text-white font-semibold mt-1">{{ $happening->event_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Boodschap') }}</label>
                        <p class="text-gray-900 dark:text-white mt-1">{{ $happening->message }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Aantal personen') }}</label>
                        <p class="text-gray-900 dark:text-white font-semibold mt-1">{{ $happening->person_count }} {{ __('personen') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Op locatie') }}</label>
                        <p class="text-gray-900 dark:text-white font-semibold mt-1">{{ $happening->on_location ? 'Ja' : 'Nee' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Locatie') }}</label>
                        <p class="text-gray-900 dark:text-white font-semibold mt-1">
                            {{ $happening->on_location ? ($happening->location ?: __('Locatie niet ingevuld')) : __('Niet op locatie') }}
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Thema') }}</label>
                        <p class="text-gray-900 dark:text-white font-semibold mt-1">{{ $happening->theme->name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Right: Huidige Reactie -->
            <div>
                <h2 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">{{ __('Reactie') }}</h2>

                @if($happening->price_per_person > 0)
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                ✓ Gerespond
                            </span>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Prijs per persoon') }}</label>
                            <p class="text-gray-900 dark:text-white font-semibold mt-1">€ {{ number_format($happening->price_per_person, 2) }}</p>
                        </div>
                        @if($happening->remarks)
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Bericht aan klant') }}</label>
                                <p class="text-gray-900 dark:text-white mt-1">{{ $happening->remarks }}</p>
                            </div>
                        @endif
                        @if($happening->desserts->count() > 0)
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2 block">{{ __('Gekozen desserts') }}</label>
                                <div class="border border-gray-200 dark:border-zinc-700 rounded-lg overflow-hidden">
                                    <table class="min-w-full text-left text-sm">
                                        <thead class="bg-gray-50 dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-700">
                                        <tr>
                                            <th class="px-4 py-2 font-semibold text-gray-900 dark:text-white">Dessert</th>
                                            <th class="px-4 py-2 font-semibold text-gray-900 dark:text-white">Aantal</th>
                                            <th class="px-4 py-2 font-semibold text-gray-900 dark:text-white text-right">Totaal</th>
                                        </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                                        @foreach($happening->desserts as $dessert)
                                            <tr>
                                                <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $dessert->name }}</td>
                                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400">{{ $dessert->pivot->quantity }}</td>
                                                <td class="px-4 py-2 text-right text-gray-900 dark:text-white whitespace-nowrap">€ {{ number_format($dessert->price * $dessert->pivot->quantity, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center text-gray-400 dark:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mb-3 opacity-40">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                        </svg>
                        <p class="font-medium">Nog geen response</p>
                        <p class="text-sm mt-1 mb-6">Klik op "Beantwoorden" om te reageren.</p>
                        <a href="{{ route('owner.respond-order-requests.respond', $happening->id) }}" wire:navigate
                           class="px-6 py-2 bg-gray-800 dark:bg-gray-700 text-white rounded-md hover:bg-gray-900 dark:hover:bg-gray-600 transition-colors font-medium text-sm">
                            {{ __('Beantwoorden') }}
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
