@props([
    'name' => null,
    'id' => null,
    'options' => [],
    'valueField' => 'id',
    'labelField' => 'name',
    'placeholder' => null,
])

@php
    $id = $id ?? $name;
@endphp

<select id="{{ $id }}" name="{{ $name }}" {{ $attributes->merge(['class' => 'mt-1 block w-full px-3 py-2 bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm']) }}>
    @if($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif
    @foreach($options as $key => $option)
        @if(is_object($option))
            <option value="{{ $option->{$valueField} }}">{{ $option->{$labelField} }}</option>
        @else
            <option value="{{ $key }}">{{ $option }}</option>
        @endif
    @endforeach
</select>
