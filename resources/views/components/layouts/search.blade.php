@props([
    'action',
    'placeholder' => 'Zoek...',
    'value' => ''
])

<form action="{{ $action }}" method="GET" class="mb-6">
    <div class="relative">
        <label for="search" class="sr-only">{{ __('Zoek') }}</label>
        <input
            id="search"
            name="search"
            type="search"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => 'w-1/3 px-3 py-2 rounded-md border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500']) }}
        />
    </div>
</form>
