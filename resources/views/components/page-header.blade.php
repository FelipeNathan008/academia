@props(['title', 'backUrl' => null, 'backText' => 'Administração', 'breadcrumbs' => []])

<div class="mb-8">
    <nav class="mb-4 flex flex-wrap items-center gap-2 text-sm text-gray-500">
        @if (count($breadcrumbs))
            @foreach ($breadcrumbs as $item)
                @if (!$loop->first)
                    <span class="text-gray-300">/</span>
                @endif

                @if (!empty($item['url']))
                    <a href="{{ $item['url'] }}" class="transition hover:text-[#8E251F]">{{ $item['label'] }}</a>
                @else
                    <span class="{{ $loop->last ? 'font-semibold text-gray-700' : '' }}">{{ $item['label'] }}</span>
                @endif
            @endforeach
        @else
            @if ($backUrl)
                <a href="{{ $backUrl }}" class="transition hover:text-[#8E251F]">{{ $backText }}</a>
                <span class="text-gray-300">/</span>
            @endif
            <span class="font-semibold text-gray-700">{{ $title }}</span>
        @endif
    </nav>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-3xl font-extrabold text-gray-800">{{ $title }}</h2>
        @if (isset($action))
            <div>{{ $action }}</div>
        @endif
    </div>
</div>