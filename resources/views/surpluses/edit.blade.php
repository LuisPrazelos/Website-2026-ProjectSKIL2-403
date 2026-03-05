<x-layouts.app :title="__('Overschot bewerken')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-transparent">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold">{{ __('Overschot bewerken') }}</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ __('Bewerk de details van dit overschot.') }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 p-8 rounded-lg shadow-lg w-full">
                <form action="{{ route('owner.surpluses.update', $surplus->id) }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="dessert_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Dessert') }}</label>
                        <select id="dessert_id" name="dessert_id" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @foreach($desserts as $dessert)
                                <option value="{{ $dessert->id }}" @selected(old('dessert_id', $surplus->dessert_id) == $dessert->id)>{{ $dessert->name }}</option>
                            @endforeach
                        </select>
                        @error('dessert_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Datum') }}</label>
                        <input type="date" id="date" name="date" value="{{ old('date', $surplus->date->format('Y-m-d')) }}" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="total_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Aantal beschikbaar') }}</label>
                        <input type="number" id="total_amount" name="total_amount" value="{{ old('total_amount', $surplus->total_amount) }}" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('total_amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="sale" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Korting (%)') }}</label>
                        <input type="number" id="sale" name="sale" value="{{ old('sale', $surplus->sale) }}" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('sale')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                        <select id="status" name="status" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="available" @selected(old('status', $surplus->status) == 'available')>Beschikbaar</option>
                            <option value="reserved" @selected(old('status', $surplus->status) == 'reserved')>Gereserveerd</option>
                            <option value="picked_up" @selected(old('status', $surplus->status) == 'picked_up')>Opgehaald</option>
                        </select>
                        @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Extra opmerking') }}</label>
                        <textarea id="comment" name="comment" rows="3" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('comment', $surplus->comment) }}</textarea>
                        @error('comment')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('owner.surpluses.index') }}" class="mr-2 inline-flex items-center px-4 py-2 border rounded-md text-sm">{{ __('Annuleren') }}</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border rounded-md text-sm bg-black text-white">{{ __('Opslaan') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
