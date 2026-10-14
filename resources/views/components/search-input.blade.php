
@props(['name', 'label', 'placeholder' => 'Buscar...', 'value' => null, 'id' => null])

@php($id = $id ?? $name)

<div class="flex-1">
    <label for="{{ $id }}" class="mb-2 block text-sm font-semibold text-gray-700">{{ $label }}</label>
    <div class="relative">
        <svg class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="10.5" cy="10.5" r="6.5" stroke-width="1.8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.5 15.5L21 21"/></svg>
        <input id="{{ $id }}" name="{{ $name }}" value="{{ $value ?? request($name) }}" placeholder="{{ $placeholder }}" {{ $attributes->merge(['class' => 'w-full rounded-lg border border-gray-300 bg-white py-3 pl-11 pr-4 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10']) }}>
    </div>
</div>
