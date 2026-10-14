
@props(['title', 'description' => null])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-md']) }}>
    <div class="bg-gray-700 px-6 py-5 text-white">
        <h3 class="text-lg font-bold">{{ $title }}</h3>
        @if ($description)
        <p class="mt-0.5 text-sm text-gray-300">{{ $description }}</p>
        @endif
    </div>

    <div class="space-y-5 p-6">
        {{ $slot }}
    </div>

    @if (isset($actions))
    <div class="flex flex-col-reverse gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4 sm:flex-row sm:justify-end">
        {{ $actions }}
    </div>
    @endif
</div>
