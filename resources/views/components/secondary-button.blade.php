
@props(['type' => 'button', 'icon' => null])

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 transition disabled:opacity-60 disabled:cursor-not-allowed']) }}>
    @if ($icon === 'reset')
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12a9 9 0 109-9 9.75 9.75 0 00-6.7 2.7L3 9"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v6h6"/></svg>
    @elseif ($icon === 'back')
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7"/></svg>
    @endif
    {{ $slot }}
</button>
