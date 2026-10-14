@props(['url' => null, 'text' => 'Voltar'])

<a href="{{ $url ?? url()->previous() }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-300 rounded-lg shadow-sm hover:bg-gray-200 transition">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7"/>
    </svg>
    {{ $text }}
</a>