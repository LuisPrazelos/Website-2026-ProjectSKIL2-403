<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-transparent">
        <!-- Header area inside big container -->
        <div class="mb-6">
            <div>
                <h1 class="text-2xl font-semibold">{{ __('Evenement Aanvragen') }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Vul het formulier in om je evenement aan te vragen. Onze team zal je snel contacteren.') }}</p>
            </div>
        </div>

        @if($submitted)
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-md">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-green-800 dark:text-green-300 font-medium">{{ __('Je evenement aanvraag is succesvol verzonden! We nemen snel contact op.') }}</p>
                </div>
            </div>
        @endif

        <form wire:submit="submitRequest" class="bg-white dark:bg-zinc-900 rounded-md shadow-sm p-6 space-y-6">
            <!-- Titel -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Titel van je evenement') }}
                    <span class="text-red-500">*</span>
                </label>
                <input
                    wire:model="title"
                    type="text"
                    id="title"
                    placeholder="{{ __('Bijv: Bedrijfsfeest, Bruiloft, Verjaardag, etc.') }}"
                    class="w-full px-4 py-2 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 dark:text-gray-100"
                >
                @error('title')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Datum -->
            <div>
                <label for="eventDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Datum en tijd van je evenement') }}
                    <span class="text-red-500">*</span>
                </label>
                <input
                    wire:model="eventDate"
                    type="datetime-local"
                    id="eventDate"
                    class="w-full px-4 py-2 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 dark:text-gray-100"
                >
                @error('eventDate')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Aantal personen -->
            <div>
                <label for="personCount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Aantal personen') }}
                    <span class="text-red-500">*</span>
                </label>
                <input
                    wire:model="personCount"
                    type="number"
                    id="personCount"
                    placeholder="{{ __('Bijv: 50') }}"
                    min="1"
                    max="10000"
                    class="w-full px-4 py-2 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 dark:text-gray-100"
                >
                @error('personCount')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Beschrijving -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Beschrijving van je evenement') }}
                    <span class="text-red-500">*</span>
                </label>
                <textarea
                    wire:model="description"
                    id="description"
                    rows="8"
                    placeholder="{{ __('Beschrijf hier je evenement in detail. Vertel ons wat je zoekt, je wensen, speciale vereisten, thema, etc.') }}"
                    class="w-full px-4 py-2 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 dark:text-gray-100 resize-none"
                ></textarea>
                @error('description')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit button -->
            <div class="flex items-center justify-end gap-4">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-md text-sm font-medium bg-black text-white hover:bg-gray-800 dark:hover:bg-gray-900 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                    <span wire:loading.remove>{{ __('Evenement Aanvragen') }}</span>
                    <span wire:loading>{{ __('Bezig met verzenden...') }}</span>
                </button>
            </div>

            <!-- Info text -->
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-6">{{ __('Alle velden zijn verplicht. Na verzending zal ons team je aanvraag beoordelen en contact met je opnemen.') }}</p>
        </form>
    </div>
</div>

