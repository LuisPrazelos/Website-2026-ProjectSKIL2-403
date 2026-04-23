<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 bg-transparent">
            <!-- Header area -->
            <div class="mb-6">
                <h1 class="text-2xl font-semibold">{{ __('Beschikbare Workshops') }}</h1>
                <p class="text-sm text-zinc-600 mt-1">{{ __('Ontdek onze passie en schrijf je in voor een van onze workshops.') }}</p>
            </div>

            <!-- Success Message (Optional, as we have a modal now) -->
            @if(session()->has('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <input wire:model.live.debounce.300ms="search" type="text" placeholder="{{ __('Zoek op workshop naam...') }}" class="w-full rounded-md border-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm mb-6 dark:bg-zinc-900 dark:border-zinc-700 dark:text-zinc-200">

            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($workshops as $workshop)
                    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-md border border-zinc-200 dark:border-zinc-700 overflow-hidden flex flex-col h-full">
                        <div class="p-5 flex flex-col flex-1">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">{{ $workshop->name }}</h3>
                                <div class="flex flex-col items-end">
                                    <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-amber-900 dark:text-amber-300 mb-1">
                                        €{{ number_format($workshop->price_adults, 2) }} {{ __('p.p. (volw.)') }}
                                    </span>
                                    @if($workshop->price_children > 0)
                                        <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-amber-900 dark:text-amber-300">
                                            €{{ number_format($workshop->price_children, 2) }} {{ __('p.p. (kind)') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4 line-clamp-3">{{ $workshop->description }}</p>

                            <div class="space-y-2 mb-6">
                                <div class="flex items-center text-sm text-zinc-600 dark:text-zinc-300">
                                    <flux:icon.calendar class="size-4 mr-2" />
                                    {{ \Carbon\Carbon::parse($workshop->date)->format('d-m-Y H:i') }}
                                </div>
                                <div class="flex items-center text-sm text-zinc-600 dark:text-zinc-300">
                                    <flux:icon.map-pin class="size-4 mr-2" />
                                    {{ $workshop->location }}
                                </div>
                                <div class="flex items-center text-sm">
                                    <flux:icon.users class="size-4 mr-2" />
                                    <span class="{{ $workshop->participant_count >= $workshop->max_participants ? 'text-red-500' : 'text-zinc-600 dark:text-zinc-300' }}">
                                        {{ $workshop->participant_count }} / {{ $workshop->max_participants }} {{ __('deelnemers') }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-auto">
                                @if($workshop->participant_count >= $workshop->max_participants)
                                    <button disabled class="w-full py-2 bg-zinc-300 text-zinc-600 rounded-lg cursor-not-allowed">
                                        {{ __('Volgeboekt') }}
                                    </button>
                                @else
                                    <button wire:click="openRegistrationModal({{ $workshop->workshopId }})" class="w-full py-2 bg-accent text-white rounded-lg hover:bg-amber-700 transition font-medium">
                                        {{ __('Inschrijven') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-zinc-500 dark:text-zinc-400">
                        <flux:icon.users class="mx-auto h-12 w-12 opacity-20 mb-4" />
                        <h2 class="text-xl font-medium">{{ __('Geen workshops beschikbaar') }}</h2>
                        <p>{{ __('Er zijn momenteel geen workshops gepland. Kom later nog eens terug!') }}</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $workshops->links() }}
            </div>
        </div>

        <!-- Registration Modal -->
        @if($showRegistrationModal && $selectedWorkshop)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-zinc-800 p-8 rounded-lg shadow-lg w-full max-w-lg overflow-y-auto max-h-[90vh]">
                    <h2 class="text-2xl font-semibold mb-2">{{ __('Inschrijven voor workshop') }}</h2>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">{{ $selectedWorkshop->name }} op {{ \Carbon\Carbon::parse($selectedWorkshop->date)->format('d-m-Y H:i') }}</p>

                    <div class="flex gap-4 mb-6 text-xs">
                        <span class="text-zinc-500">{{ __('Prijs volwassene:') }} <span class="font-semibold text-zinc-700 dark:text-zinc-300">€{{ number_format($selectedWorkshop->price_adults, 2) }}</span></span>
                        @if($selectedWorkshop->price_children > 0)
                            <span class="text-zinc-500">{{ __('Prijs kind:') }} <span class="font-semibold text-zinc-700 dark:text-zinc-300">€{{ number_format($selectedWorkshop->price_children, 2) }}</span></span>
                        @endif
                    </div>

                    <form wire:submit.prevent="register">
                        <div class="space-y-4">
                            @error('max_participants') <p class="text-red-500 text-sm font-medium">{{ $message }}</p> @enderror

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="total_adults" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Aantal volwassenen') }}</label>
                                    <input type="number" wire:model.live="total_adults" id="total_adults" min="0" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                </div>
                                <div>
                                    <label for="total_children" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Aantal kinderen') }}</label>
                                    <input type="number" wire:model.live="total_children" id="total_children" min="0" required class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                </div>
                            </div>

                            <div>
                                <label for="comment" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Extra opmerking / Allergieën') }}</label>
                                <textarea wire:model.defer="comment" id="comment" rows="3" class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white"></textarea>
                                @error('comment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="pt-4 border-t border-zinc-200 dark:border-zinc-700">
                                <div class="flex justify-between text-base font-bold mb-2">
                                    <span class="text-zinc-900 dark:text-white">{{ __('Totaal prijs schatting:') }}</span>
                                    <span class="text-zinc-900 dark:text-white">
                                        €{{ number_format(($total_adults * $selectedWorkshop->price_adults) + ($total_children * $selectedWorkshop->price_children), 2) }}
                                    </span>
                                </div>

                                <!-- Prijsverdeling breakdown -->
                                <div class="space-y-1 text-xs text-zinc-500 dark:text-zinc-400">
                                    <div class="flex justify-between">
                                        <span>{{ __('Volwassenen') }} ({{ $total_adults }} x €{{ number_format($selectedWorkshop->price_adults, 2) }})</span>
                                        <span>€{{ number_format($total_adults * $selectedWorkshop->price_adults, 2) }}</span>
                                    </div>
                                    @if($selectedWorkshop->price_children > 0)
                                    <div class="flex justify-between">
                                        <span>{{ __('Kinderen') }} ({{ $total_children }} x €{{ number_format($selectedWorkshop->price_children, 2) }})</span>
                                        <span>€{{ number_format($total_children * $selectedWorkshop->price_children, 2) }}</span>
                                    </div>
                                    @endif
                                </div>

                                <p class="text-[10px] text-zinc-400 mt-4 italic">{{ __('Definitieve prijs kan variëren bij speciale verzoeken.') }}</p>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-4">
                            <button type="button" wire:click="$set('showRegistrationModal', false)" class="px-4 py-2 border border-zinc-300 rounded-md text-sm bg-white text-zinc-700 hover:bg-zinc-50 dark:bg-zinc-700 dark:text-zinc-200 dark:border-zinc-600 dark:hover:bg-zinc-600">
                                {{ __('Annuleren') }}
                            </button>
                            <button type="submit" class="px-4 py-2 border rounded-md text-sm bg-accent text-white hover:bg-amber-700 dark:bg-accent dark:text-white dark:hover:bg-amber-500">
                                {{ __('Naar betalen') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Payment Modal -->
        @if($showPaymentModal && $selectedWorkshop)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-zinc-800 p-8 rounded-lg shadow-lg w-full max-w-md text-center">
                    <h2 class="text-2xl font-semibold mb-4">{{ __('Scan om te betalen') }}</h2>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-6">
                        {{ __('Bedankt voor je inschrijving voor') }} <strong>{{ $selectedWorkshop->name }}</strong>.
                        {{ __('Scan de onderstaande QR-code om de betaling van') }} <strong>€{{ number_format(($total_adults * $selectedWorkshop->price_adults) + ($total_children * $selectedWorkshop->price_children), 2) }}</strong> {{ __('te voltooien.') }}
                    </p>

                    <div class="flex justify-center mb-8">
                        <button wire:click="completePayment" class="cursor-pointer transition hover:scale-105">
                            <img src="{{ asset('pictures/QrCode.png') }}" alt="{{ __('QR Code voor betaling') }}" class="w-64 h-64 shadow-md rounded-lg">
                        </button>
                    </div>

                    <p class="text-xs text-zinc-500 mb-6 italic">{{ __('Klik op de QR-code om de betaling te simuleren.') }}</p>

                    <div class="flex justify-center">
                        <button type="button" wire:click="$set('showPaymentModal', false)" class="px-4 py-2 border border-zinc-300 rounded-md text-sm bg-white text-zinc-700 hover:bg-zinc-50 dark:bg-zinc-700 dark:text-zinc-200 dark:border-zinc-600 dark:hover:bg-zinc-600">
                            {{ __('Annuleren') }}
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Success Modal -->
        @if($showSuccessModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-zinc-800 p-8 rounded-lg shadow-lg w-full max-w-sm text-center">
                    <div class="flex justify-center mb-4">
                        <flux:icon.check-circle class="size-16 text-green-500" />
                    </div>
                    <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2">{{ __('Betaling Geslaagd!') }}</h2>
                    <p class="text-zinc-600 dark:text-zinc-400 mb-8">{{ __('Je bent nu officieel ingeschreven voor de workshop. We hebben je een bevestiging gestuurd per e-mail.') }}</p>

                    <button wire:click="closeSuccessModal" class="w-full py-3 bg-accent text-white rounded-lg hover:bg-amber-700 transition font-bold">
                        {{ __('Terug naar overzicht') }}
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
