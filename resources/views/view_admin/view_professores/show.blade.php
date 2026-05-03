@extends('layouts.dashboard')

@section('title', 'Detalhes do Professor')

@section('content')

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('professores') }}"
                class="hover:text-[#8E251F] transition">
                Professores
            </a>
        </li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Ver Professor</li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">
            INFORMAÇÕES COMPLETAS DO PROFESSOR
        </h2>
    </div>

    <a href="{{ route('professores') }}"
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
                    {{ $professor->prof_nome }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Telefone</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $professor->prof_telefone }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Empresa</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $professor->empresas->emp_nome ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Data de Nascimento</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($professor->prof_nascimento)->format('d/m/Y') }}
                </p>
            </div>

        </div>

        <!-- COLUNA 2 -->
        <div class="space-y-6">

            <div>
                <p class="text-xs uppercase text-gray-400">Idade</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($professor->prof_nascimento)->age }} anos
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Quantidade de Alunos</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $professor->qtd_aluno ?? 0 }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Graduado</p>

                @if($professor->detalhes->count() > 0)
                <span class="px-4 py-2 text-sm rounded-full bg-green-100 text-green-700 font-medium">
                    🥋 Sim
                </span>
                @else
                <span class="px-4 py-2 text-sm rounded-full bg-red-100 text-red-700 font-medium">
                    Não
                </span>
                @endif
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Foto</p>

                @if($professor->prof_foto)
                <img src="{{ asset('images/professores/' . $professor->prof_foto) }}"
                    style="width:120px; height:120px; object-fit:cover; border-radius:10px;">
                @else
                <p class="text-gray-500">Sem foto</p>
                @endif
            </div>

        </div>

    </div>

    <!-- OBSERVAÇÃO -->
    @if($professor->prof_desc)
    <div class="mt-10 pt-6 border-t">
        <p class="text-xs uppercase text-gray-400 mb-2">Observações</p>
        <p class="text-gray-700 leading-relaxed">
            {{ $professor->prof_desc }}
        </p>
    </div>
    @endif

</div>

@endsection