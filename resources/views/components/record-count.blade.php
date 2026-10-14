
@props(['count', 'singular' => 'registro', 'plural' => 'registros'])

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 rounded-lg border border-red-100 bg-red-50 px-3.5 py-2 text-sm font-semibold text-[#732920]']) }}>
    <svg class="h-4 w-4 shrink-0 text-[#8E251F]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
    Exibindo {{ $count }} {{ $count == 1 ? $singular : $plural }}
</div>
