@props([
    'parent' => 'Administração',
    'parentUrl' => null,
    'current' => ''
])

<nav class="mb-4 text-sm text-gray-500" aria-label="Navegação">
    <ol class="flex flex-wrap items-center gap-2">
        <li>
            @if ($parentUrl)
                <a href="{{ $parentUrl }}" class="transition hover:text-[#8E251F]">
                    {{ $parent }}
                </a>
            @else
                <span>{{ $parent }}</span>
            @endif
        </li>

        <li class="text-gray-300" aria-hidden="true">/</li>

        <li class="font-semibold text-gray-700" aria-current="page">
            {{ $current }}
        </li>
    </ol>
</nav>