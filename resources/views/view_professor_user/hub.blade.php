@extends('layouts.dashboard')

@section('title', 'Detalhes do Aluno')

@section('content')

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">

        <li>
            <a href="{{ route('professor-aluno.index') }}" class="hover:text-[#8E251F] transition">
                Meus Alunos
            </a>
        </li>
        <li>/</li>
        <li class="text-gray-400">{{ $aluno->aluno_nome }}</li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Hub</li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex justify-between items-center mb-8">
    <h2 class="text-3xl font-extrabold text-gray-800">
        {{ $aluno->aluno_nome }}
    </h2>

    <a href="{{ route('professor-aluno.index', Crypt::encrypt($aluno->id_aluno)) }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition-colors">
        ← Voltar
    </a>
</div>

<!-- CARD RESPONSAVEL -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#8E251F] rounded-2xl shadow-lg p-6">

        <p class="text-xs uppercase tracking-widest text-gray-500">
            Responsável do aluno
        </p>

        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
            {{ $responsavel->resp_nome ?? 'Responsável não encontrado' }}
        </h3>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">

            <div>
                <span class="font-semibold">Telefone:</span>

                @php
                $telefone = preg_replace('/\D/', '', $responsavel->resp_telefone);

                if(strlen($telefone) == 11){
                $telefoneFormatado = preg_replace(
                "/(\d{2})(\d{5})(\d{4})/",
                "($1) $2-$3",
                $telefone
                );
                } elseif(strlen($telefone) == 10){
                $telefoneFormatado = preg_replace(
                "/(\d{2})(\d{4})(\d{4})/",
                "($1) $2-$3",
                $telefone
                );
                } else {
                $telefoneFormatado = $responsavel->resp_telefone;
                }
                @endphp

                {{ $telefoneFormatado }}
            </div>

            <div>
                <span class="font-semibold">Email:</span>
                {{ $responsavel->resp_email ?? '-' }}
            </div>

        </div>

    </div>
</div>
<!-- TELA AUXILIAR -->
<div class="bg-white shadow-lg rounded-2xl p-6">

    <h3 class="text-xl font-bold text-gray-700 mb-6">
        Navegação Rápida
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

        <a href="{{ route('professor-aluno.show', Crypt::encrypt($aluno->id_aluno)) }}"
            style="background-color: #325db8; color: white;"
            class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
            Ver Aluno
        </a>

        <a href="{{ route('professor-aluno.edit', Crypt::encrypt($aluno->id_aluno)) }}"
            style="background-color: #ca8a04; color: white;"
            class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
            Editar Aluno
        </a>

        <a href="{{ route('professor-responsavel.show', Crypt::encrypt($aluno->id_aluno)) }}"
            style="background-color: #275cce; color: white;"
            class="px-4 py-2 rounded-lg shadow hover:bg-[#166534] transition duration-200 text-center">
            Responsável
        </a>
        <a href="{{ route('professor-detalhes-aluno.index', Crypt::encrypt($aluno->id_aluno)) }}"
            style="background-color: #174ab9; color: white;"
            class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
            Graduações
        </a>

        <a href="{{ route('professor-matricula', Crypt::encrypt($aluno->id_aluno)) }}"
            style="background-color: #8E251F; color: white;"
            class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
            Matrícula
        </a>

        @if(strtolower($aluno->aluno_bolsista) !== 'sim')
        <a href="{{ route('professor-financeiro', Crypt::encrypt($aluno->id_aluno)) }}"
            style="background-color: #15803d; color: white;"
            class="px-4 py-2 rounded-lg shadow hover:bg-[#166534] transition duration-200 text-center">
            Financeiro
        </a>
        @endif
    </div>
</div>

@endsection