@extends('layouts.dashboard')

@section('title', 'Editar Modalidade')

@section('content')

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Editar Modalidade ({{ $modalidade->mod_nome }})
    </h2>

    <form action="{{ route('modalidades.update', $modalidade->id_modalidade) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="text-sm font-medium text-gray-600">Nome da Modalidade</label>
                <input type="text"
                    name="mod_nome"
                    maxlength="100"
                    required
                    value="{{ old('mod_nome', $modalidade->mod_nome) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
            </div>

            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-600">Descrição</label>
                <textarea
                    name="mod_desc"
                    rows="3"
                    required
                    class="w-full border rounded-lg px-4 py-2 mt-1
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none">{{ old('mod_desc', $modalidade->mod_desc) }}</textarea>
            </div>

        </div>

        <div class="flex justify-end gap-4 mt-6">
            <a href="{{ route('modalidades') }}"
               class="px-5 py-2 border rounded-lg hover:bg-gray-100 transition">
                Voltar
            </a>

            <button type="submit"
                class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920] transition">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

@endsection
