@props([
    'label',
    'name'
])

<div class="flex-1">

    <label class="text-sm font-medium text-gray-600">
        {{ $label }}
    </label>

    <select
        name="{{ $name }}"
        {{ $attributes->merge([
            'class' => 'w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]'
        ]) }}>

        {{ $slot }}

    </select>

</div>