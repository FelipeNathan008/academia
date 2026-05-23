@extends('layouts.dashboard')

@section('title', 'Editar Turma')

@section('content')

<x-alert-error />

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Editar Turma ({{ $turma->turma_nome }})
    </h2>

    <form action="{{ route('turmas.update', Crypt::encrypt($turma->id_turma)) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label class="text-sm font-medium text-gray-600">Nome da Turma</label>
            <input type="text"
                name="turma_nome"
                maxlength="100"
                required
                value="{{ old('turma_nome', $turma->turma_nome) }}"
                class="w-full border rounded-lg px-4 py-2 mt-1
                       focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
        </div>

        <div class="flex justify-end gap-4 mt-6">
            <a href="{{ route('turmas') }}"
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