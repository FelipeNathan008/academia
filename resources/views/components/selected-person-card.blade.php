@props([
    'title' => 'Registro selecionado',
    'name',
    'phone' => null,
    'count' => 0,
    'countLabel' => 'Registros cadastrados',
    'accentColor' => '#8E251F',
    'countBackground' => 'bg-red-50',
    'countLabelColor' => 'text-red-700',
    'countColor' => 'text-red-800',
])

<div class="mb-8 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-md">
    <div class="flex flex-col gap-3 border-l-4 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6" style="border-left-color: {{ $accentColor }};">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">{{ $title }}</p>
            <h2 class="mt-1 text-xl font-bold text-gray-800">{{ $name }}</h2>
            @if ($phone)
                <p class="mt-2 text-sm text-gray-600">
                    Telefone:
                    <span class="font-semibold text-gray-800">{{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', preg_replace('/\D/', '', $phone)) }}</span>
                </p>
            @endif
        </div>
        <div class="rounded-lg {{ $countBackground }} px-4 py-3">
            <p class="text-xs font-medium {{ $countLabelColor }}">{{ $countLabel }}</p>
            <p class="mt-1 text-2xl font-bold {{ $countColor }}">{{ $count }}</p>
        </div>
    </div>
</div>