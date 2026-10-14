@extends('layouts.dashboard')

@section('title', 'Escolher Molde de Graduação')

@section('content')
<x-alert-error />

<div class="w-full px-4 pt-2 pb-6 sm:px-6 lg:px-8">
    <x-page-header
        title="Moldes de Graduação"
        :breadcrumbs="[
            ['label' => 'Administração', 'url' => route('admin.administracao')],
            ['label' => 'Graduações', 'url' => route('graduacoes')],
            ['label' => 'Moldes de Graduação']
        ]" />

    <div class="mb-6 flex justify-end">
        <x-back-button :url="route('graduacoes')" />
    </div>

    <x-form-card title="Escolha um molde" description="Selecione um molde para visualizar as graduações disponíveis e utilizá-las como referência.">
        @if($modalidadesMolde->isEmpty())
            <x-empty-state
                title="Nenhum molde disponível"
                description="Não há moldes de graduação disponíveis no sistema no momento." />
        @else
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($modalidadesMolde as $molde)
                    <a href="{{ route('graduacoes.molde.show', Crypt::encrypt($molde->id_modalidade_molde)) }}" class="group flex h-full flex-col rounded-xl border border-gray-200 bg-white p-5 transition hover:border-[#8E251F]/40 hover:shadow-md">
                        <div class="mb-3 flex items-start justify-between gap-3">
                            <h3 class="text-base font-bold text-gray-800 transition group-hover:text-[#8E251F]">{{ $molde->mod_nome }}</h3>
                            <span class="shrink-0 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">{{ $molde->graduacoes->count() }} {{ $molde->graduacoes->count() === 1 ? 'graduação' : 'graduações' }}</span>
                        </div>
                        <p class="flex-1 text-sm leading-relaxed text-gray-500">{{ $molde->mod_desc ?: 'Este molde não possui descrição.' }}</p>
                        <div class="mt-5 flex items-center justify-between border-t border-gray-100 pt-4 text-sm font-semibold text-[#8E251F]">
                            Visualizar molde
                            <span class="transition group-hover:translate-x-1">→</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </x-form-card>
</div>
@endsection