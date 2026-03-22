@props([
    'variant' => 'primary', // default
    'type' => 'button'
])

@php
    $baseClasses = "px-4 py-2 rounded-lg font-semibold transition duration-200";

    // Updated to match the warm bakery theme
    $variants = [
        'primary' => "bg-accent text-accent-foreground hover:bg-amber-700 dark:hover:bg-amber-500", // Using the accent color defined in app.css
        'secondary' => "bg-zinc-200 text-zinc-800 hover:bg-zinc-300 dark:bg-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-600"
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
