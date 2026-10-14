@if ($paginator->hasPages())
@php
$current = $paginator->currentPage();
$last = $paginator->lastPage();
$start = max(1, $current - 2);
$end = min($last, $current + 2);
@endphp

<div class="flex justify-center items-center mt-4 mb-6 px-5 pt-5 pb-2 w-full border-t border-gray-100">
    <div class="flex items-center gap-1">

        @if ($paginator->onFirstPage())
        <span class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 bg-gray-50 text-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-4 h-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}"
            class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition"
            onmouseover="this.style.backgroundColor='#8E251F'; this.style.borderColor='#8E251F'; this.style.color='white';"
            onmouseout="this.style.backgroundColor='white'; this.style.borderColor='#e5e7eb'; this.style.color='#4b5563';">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-4 h-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        @endif

        @if ($start > 1)
        <a href="{{ $paginator->url(1) }}"
            class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 text-sm font-medium transition"
            onmouseover="this.style.backgroundColor='#f8eeee'; this.style.borderColor='#8E251F'; this.style.color='#8E251F';"
            onmouseout="this.style.backgroundColor='white'; this.style.borderColor='#e5e7eb'; this.style.color='#4b5563';">
            1
        </a>

        @if ($start > 2)
        <span class="w-7 text-center text-gray-400">
            ...
        </span>
        @endif
        @endif

        @for ($page = $start; $page <= $end; $page++)
            @if ($page==$current)
            <span class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-semibold text-white shadow-sm"
            style="background-color: #8E251F;">
            {{ $page }}
            </span>
            @else
            <a href="{{ $paginator->url($page) }}"
                class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 text-sm font-medium transition"
                onmouseover="this.style.backgroundColor='#f8eeee'; this.style.borderColor='#8E251F'; this.style.color='#8E251F';"
                onmouseout="this.style.backgroundColor='white'; this.style.borderColor='#e5e7eb'; this.style.color='#4b5563';">
                {{ $page }}
            </a>
            @endif
            @endfor

            @if ($end < $last)
                @if ($end < $last - 1)
                <span class="w-7 text-center text-gray-400">
                ...
                </span>
                @endif

                <a href="{{ $paginator->url($last) }}"
                    class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 text-sm font-medium transition"
                    onmouseover="this.style.backgroundColor='#f8eeee'; this.style.borderColor='#8E251F'; this.style.color='#8E251F';"
                    onmouseout="this.style.backgroundColor='white'; this.style.borderColor='#e5e7eb'; this.style.color='#4b5563';">
                    {{ $last }}
                </a>
                @endif

                @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition"
                    onmouseover="this.style.backgroundColor='#8E251F'; this.style.borderColor='#8E251F'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor='white'; this.style.borderColor='#e5e7eb'; this.style.color='#4b5563';">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                @else
                <span class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 bg-gray-50 text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
                @endif

    </div>
</div>
@endif