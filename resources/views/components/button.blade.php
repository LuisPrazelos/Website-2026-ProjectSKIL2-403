@props([
    'variant' => 'primary', // default
    'type' => 'button'
])

@php
    $baseClasses = "px-4 py-2 rounded-lg font-semibold transition duration-200";

    $variants = [
        'primary' => "bg-blue-600 text-white hover:bg-blue-700",
        'secondary' => "bg-gray-200 text-gray-800 hover:bg-gray-300"
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
