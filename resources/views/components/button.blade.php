@props([
    'color' => 'primary',
    'type' => 'button'
])

@php
$colors = [
    'primary' => 'bg-[#8E251F] hover:bg-[#732920] text-white',
    'blue' => 'bg-[#174ab9] hover:bg-[#1e40af] text-white',
    'green' => 'bg-green-700 hover:bg-green-800 text-white',
    'red' => 'bg-red-600 hover:bg-red-700 text-white',
    'gray' => 'bg-gray-300 hover:bg-gray-400 text-gray-800',
];
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'px-4 py-2 rounded-lg shadow transition ' . $colors[$color]
    ]) }}>
    {{ $slot }}
</button>