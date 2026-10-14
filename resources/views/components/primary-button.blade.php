@props(['type' => 'button', 'icon' => null])

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 px-5 py-3 text-sm font-semibold text-white bg-[#8E251F] rounded-lg shadow-sm hover:bg-[#732920] transition disabled:opacity-60 disabled:cursor-not-allowed']) }}>
    @if ($icon === 'plus')
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
    </svg>
    @elseif ($icon === 'search')
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
        <circle cx="10.5" cy="10.5" r="6.5" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.5 15.5L21 21" />
    </svg>
    @elseif ($icon === 'check')
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
    </svg>
    @endif
    {{ $slot }}
</button>