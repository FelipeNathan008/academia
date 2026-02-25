@extends('layouts.dashboard')

@section('title', 'Editar Graduação')

@section('content')

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Editar Graduação ({{ $graduacao->gradu_nome_cor }})</h2>

    <form action="{{ route('graduacoes.update', $graduacao->id_graduacao) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Nome / Cor -->
            <div>
                <label class="text-sm font-medium text-gray-600">Nome / Cor</label>
                <input type="text"
                    name="gradu_nome_cor"
                    value="{{ old('gradu_nome_cor', $graduacao->gradu_nome_cor) }}"
                    required
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                    placeholder="Ex: Faixa Branca">
            </div>

            <!-- Grau -->
            <div>
                <label class="text-sm font-medium text-gray-600">Grau</label>
                <input type="number"
                    name="gradu_grau"
                    value="{{ old('gradu_grau', $graduacao->gradu_grau) }}"
                    required
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                    placeholder="Ex: 1">
            </div>

            <!-- Meta de Aulas -->
            <div>
                <label class="text-sm font-medium text-gray-600">Meta (Quantidade de Aulas)</label>
                <input type="number"
                    name="gradu_meta"
                    value="{{ old('gradu_meta', $graduacao->gradu_meta) }}"
                    required
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                    placeholder="Ex: 30">
            </div>

        </div>

        <div class="flex justify-end gap-4 mt-6">
            <a href="{{ route('graduacoes') }}"
                class="px-5 py-2 border rounded-lg hover:bg-gray-100 transition">Voltar</a>

            <button type="submit"
                class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920] transition">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

@endsection