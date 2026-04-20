@extends('layouts.dashboard')

@section('title', 'Detalhes do Aluno')

@section('content')

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('responsaveis') }}"
                class="hover:text-[#8E251F] transition">
                Responsáveis
            </a>
        </li>
        <li>/</li>
        <li>
            <a href="{{ route('alunos', Crypt::encrypt($aluno->responsavel->id_responsavel)) }}"
                class="hover:text-[#8E251F] transition">
                Alunos
            </a>
        </li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Ver Aluno</li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">
            INFORMAÇÕES COMPLETAS DO ALUNO
        </h2>
    </div>
    <a href="{{ url()->previous() ?? route('alunos') }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
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
                <p class="text-xs uppercase text-gray-400">Contato do Responsável</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $aluno->responsavel->resp_telefone ?? '-' }}
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

                @if(strtolower($aluno->aluno_bolsista) === 'sim')
                <p class="text-lg font-semibold text-gray-800"> Sim
                </p>
                @else
                <p class="text-lg font-semibold text-gray-800"> Não
                </p>
                @endif
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Status</p>

                @if($aluno->matriculas->where('matri_status', 'Matriculado')->count() > 0)
                <span class="px-4 py-2 text-sm rounded-full bg-green-100 text-green-700 font-medium">
                    Matriculado
                </span>
                @else
                <span class="px-4 py-2 text-sm rounded-full bg-red-100 text-red-700 font-medium">
                    Não Matriculado
                </span>
                @endif
            </div>

        </div>
        <div>
            <p class="text-xs uppercase text-gray-400">Foto</p>

            @if($aluno->aluno_foto)
            <img src="{{ asset('images/alunos/' . $aluno->aluno_foto) }}"
                class="w-32 h-32 object-cover rounded-xl shadow">
            @else
            <p class="text-gray-500">Sem foto</p>
            @endif
        </div>
    </div>

    <!-- OBSERVAÇÕES -->
    @if($aluno->aluno_desc)
    <div class="mt-10 pt-6 border-t">
        <p class="text-xs uppercase text-gray-400 mb-2">Observações</p>
        <p class="text-gray-700 leading-relaxed">
            {{ $aluno->aluno_desc }}
        </p>
    </div>
    @endif

</div>

@endsection