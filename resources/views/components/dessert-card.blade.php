@props(['desert'])

<div class="rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
    @if($desert->picture)
        <img src="{{ asset('pictures/' . $desert->picture->hash) }}" alt="{{ $desert->name }}" class="mb-4 rounded-md object-cover w-full h-48">
    @endif
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $desert->name }}</h3>
    <p class="text-gray-600 dark:text-gray-400">{{ $desert->description }}</p>
    <p class="text-gray-800 dark:text-gray-200 font-bold mt-2">€{{ number_format($desert->price, 2) }}</p>
</div>
