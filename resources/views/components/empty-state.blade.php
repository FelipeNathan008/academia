
@props(['title', 'message', 'route' => null, 'buttonText' => null, 'buttonType' => 'link', 'buttonAction' => null])

<div class="px-5 py-14 text-center">
    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-500">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8M8 12h8M8 17h5M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>
    </div>
    <p class="text-base font-semibold text-gray-700">{{ $title }}</p>
    <p class="mt-1 text-sm text-gray-500">{{ $message }}</p>

    @if ($buttonText && $buttonType === 'link' && $route)
    <a href="{{ $route }}" class="mt-4 inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-200">
        {{ $buttonText }}
    </a>
    @elseif ($buttonText && $buttonType === 'button' && $buttonAction)
    <button type="button" onclick="{{ $buttonAction }}" class="mt-4 inline-flex items-center justify-center gap-2 rounded-lg bg-[#8E251F] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#732920]">
        {{ $buttonText }}
    </button>
    @endif
</div>
