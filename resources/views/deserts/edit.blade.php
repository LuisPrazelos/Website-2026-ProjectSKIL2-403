<x-layouts.app :title="__('Dessert bewerken')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-transparent">
            <h1 class="text-2xl font-semibold mb-6">{{ __('Dessert bewerken') }}: {{ $desert->name }}</h1>

            <div class="bg-white dark:bg-zinc-800 p-8 rounded-lg shadow-lg w-full max-w-lg mx-auto">
                <form action="{{ route('owner.deserts.update', $desert) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Naam</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $desert->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prijs</label>
                            <input type="number" name="price" id="price" step="0.01" value="{{ old('price', $desert->price) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Beschrijving</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $desert->description) }}</textarea>
                        </div>
                        <div>
                            <label for="picture_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Afbeelding</label>
                            <select name="picture_id" id="picture_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Geen afbeelding</option>
                                @foreach($pictures as $picture)
                                    <option value="{{ $picture->id }}" @selected(old('picture_id', $desert->picture_id) == $picture->id)>{{ $picture->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="ingredients" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ingrediënten</label>
                            <select name="ingredients[]" id="ingredients" multiple class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @foreach($ingredients as $ingredient)
                                    <option value="{{ $ingredient->id }}" @selected($desert->ingredients->contains($ingredient->id))>{{ $ingredient->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-4">
                        <a href="{{ route('owner.deserts.index') }}" class="px-4 py-2 border rounded-md text-sm">Annuleren</a>
                        <button type="submit" class="px-4 py-2 border rounded-md text-sm bg-black text-white">Opslaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
