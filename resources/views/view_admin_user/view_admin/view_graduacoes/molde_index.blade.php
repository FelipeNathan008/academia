@extends('layouts.dashboard')

@section('title', 'Escolher Molde de Graduação')

@section('content')

<x-alert-error />

<div class="flex items-center gap-4 mb-10">
    <a href="{{ route('graduacoes') }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
        ← Voltar
    </a>
    <h2 class="text-3xl font-extrabold text-gray-800">Escolha um molde de graduação</h2>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @forelse ($modalidadesMolde as $molde)
        <a href="{{ route('graduacoes.molde.show', Crypt::encrypt($molde->id_modalidade_molde)) }}"
            class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg hover:-translate-y-1 transition-all block">
            <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $molde->mod_nome }}</h3>
            <p class="text-sm text-gray-500">{{ $molde->mod_desc ?? 'Sem descrição' }}</p>
            <p class="text-xs text-gray-400 mt-3">{{ $molde->graduacoes->count() }} graduação(ões) no molde</p>
        </a>
    @empty
        <p class="text-gray-500 col-span-3 text-center py-10">Nenhum molde de graduação disponível no sistema.</p>
    @endforelse
</div>

@endsection