@extends('layouts.dashboard')

@section('title', 'Detalhes do Aluno')

@section('content')

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('dashboard-professor') }}" class="hover:text-[#8E251F]">
                Dashboard
            </a>
        </li>
        <li>/</li>
        <li>
            <a href="{{ route('professor-alunos') }}" class="hover:text-[#8E251F]">
                Meus Alunos
            </a>
        </li>
        <li>/</li>
        <li class="font-semibold text-gray-700">
            Ver Aluno
        </li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex justify-between items-center mb-8">
    <h2 class="text-3xl font-extrabold text-gray-800">
        INFORMAÇÕES DO ALUNO
    </h2>

    <a href="{{ url()->previous() }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">
        ← Voltar
    </a>
</div>

<!-- CARD -->
<div class="bg-white rounded-2xl shadow-md p-8">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

        <!-- COLUNA 1 -->
        <div class="space-y-6">

            <div>
                <p class="text-xs uppercase text-gray-400">Nome</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $aluno->aluno_nome }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Responsável</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $aluno->responsavel->resp_nome ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Data de Nascimento</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($aluno->aluno_nascimento)->format('d/m/Y') }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Telefone</p>
                <p class="text-lg font-semibold text-gray-800">
                    @if($aluno->responsavel->resp_telefone)
                    {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', preg_replace('/\D/', '', $aluno->responsavel->resp_telefone)) }}
                    @else
                    -
                    @endif
                </p>
            </div>

        </div>

        <!-- COLUNA 2 -->
        <div class="space-y-6">

            <div>
                <p class="text-xs uppercase text-gray-400">Idade</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($aluno->aluno_nascimento)->age }} anos
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Bolsista</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ strtolower($aluno->aluno_bolsista) === 'sim' ? 'Sim' : 'Não' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Status</p>

                @if($aluno->matriculas->where('matri_status', 'Matriculado')->count() > 0)
                <span class="px-4 py-2 text-sm rounded-full bg-green-100 text-green-700">
                    Matriculado
                </span>
                @else
                <span class="px-4 py-2 text-sm rounded-full bg-red-100 text-red-700">
                    Não Matriculado
                </span>
                @endif
            </div>

        </div>

        <!-- FOTO -->
        <div>
            <p class="text-xs uppercase text-gray-400">Foto</p>

            @if($aluno->aluno_foto)
            <img src="{{ asset('images/alunos/' . $aluno->aluno_foto) }}"
                style="width:120px; height:120px; object-fit:cover; border-radius:10px;">
            @else
            <p class="text-gray-500">Sem foto</p>
            @endif
        </div>

    </div>

    <!-- OBS -->
    @if($aluno->aluno_desc)
    <div class="mt-10 pt-6 border-t">
        <p class="text-xs uppercase text-gray-400 mb-2">Observações</p>
        <p class="text-gray-700">
            {{ $aluno->aluno_desc }}
        </p>
    </div>
    @endif

</div>

@endsection