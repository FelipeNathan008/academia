@props([
'showRoute' => null,
'editRoute' => null,
'deleteAction' => null,
'deleteId' => 'formExcluir',
'deleteModal' => 'modalExclusao',
'showText' => 'Detalhes',
'editText' => 'Editar',
'deleteText' => 'Excluir',
'extraRoute' => null,
'extraText' => null,
'extraColor' => 'blue',
'secondRoute' => null,
'secondText' => null,
'secondColor' => 'blue',
])

@php
$colors = [
'blue' => 'border-blue-200 bg-blue-50 text-blue-700 hover:border-blue-300 hover:bg-blue-100',
'red' => 'border-red-200 bg-red-50 text-red-700 hover:border-red-300 hover:bg-red-100',
'primary' => 'border-[#8E251F]/20 bg-[#8E251F]/5 text-[#8E251F] hover:bg-[#8E251F]/10',
'gray' => 'border-gray-200 bg-gray-50 text-gray-700 hover:bg-gray-100',
'green' => 'border-green-200 bg-green-50 text-green-700 hover:bg-green-100',
'purple' => 'border-purple-200 bg-purple-50 text-purple-700 hover:bg-purple-100',
'blue-dark' => 'border-blue-300 bg-blue-100 text-blue-900 hover:border-blue-400 hover:bg-blue-200',
'wine' => 'border-[#8E251F]/30 bg-[#8E251F]/10 text-[#732920] hover:border-[#8E251F]/50 hover:bg-[#8E251F]/15',
];
@endphp

<div class="flex flex-wrap items-center justify-center gap-2">
    @if ($showRoute)
    <a href="{{ $showRoute }}" title="{{ $showText }}" class="inline-flex h-9 items-center justify-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-3 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" />
            <circle cx="12" cy="12" r="3" />
        </svg>
        {{ $showText }}
    </a>
    @endif

    @if ($extraRoute && $extraText)
    <a href="{{ $extraRoute }}" title="{{ $extraText }}" class="inline-flex h-9 items-center justify-center gap-2 rounded-lg border px-3 text-sm font-medium transition {{ $colors[$extraColor] ?? $colors['blue'] }}">
        {{ $extraText }}
    </a>
    @endif

    @if ($secondRoute && $secondText)
    <a href="{{ $secondRoute }}" title="{{ $secondText }}" class="inline-flex h-9 items-center justify-center gap-2 rounded-lg border px-3 text-sm font-medium transition {{ $colors[$secondColor] ?? $colors['blue'] }}">
        {{ $secondText }}
    </a>
    @endif

    @if ($editRoute)
    <a href="{{ $editRoute }}" title="{{ $editText }}" class="inline-flex h-9 items-center justify-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-3 text-sm font-medium text-blue-700 transition hover:border-blue-300 hover:bg-blue-100">
        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11v-5m-1.5-8.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 8.5-8.5z" />
        </svg>
        {{ $editText }}
    </a>
    @endif

    @if ($deleteAction)
    <form id="{{ $deleteId }}" action="{{ $deleteAction }}" method="POST" class="m-0 flex" onsubmit="return confirmarExclusao(event, this, '{{ $deleteModal }}')">
        @csrf
        @method('DELETE')
        <button type="submit" title="{{ $deleteText }}" class="inline-flex h-9 items-center justify-center gap-2 rounded-lg border border-red-200 bg-red-50 px-3 text-sm font-medium text-red-700 transition hover:border-red-300 hover:bg-red-100">
            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2m-9 0l1 14h8l1-14M10 10v6m4-6v6" />
            </svg>
            {{ $deleteText }}
        </button>
    </form>
    @endif
</div>