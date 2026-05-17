@extends('layouts.dashboard')

@section('title', 'Matrículas do Aluno')

@section('content')

<x-alert-error />

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('professor-aluno.index') }}"
                class="hover:text-[#8E251F] transition">
                Alunos
            </a>
        </li>

        <li>/</li>
        <li class="text-gray-400">{{ $aluno->aluno_nome }}</li>

        <li>/</li>
        <li class="font-semibold text-gray-700">Matrícula</li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex items-center gap-4 mb-10">

    <a href="{{ route('professor-aluno.hub', Crypt::encrypt($aluno->id_aluno)) }}"
        class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
        ← Voltar
    </a>

    <h2 class="text-3xl font-extrabold text-gray-800">
        Matrículas do Aluno
    </h2>
</div>

<!-- CARD DO ALUNO -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#8E251F] rounded-2xl shadow-lg p-6">
        <p class="text-xs uppercase tracking-widest text-gray-500">
            Aluno selecionado
        </p>

        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
            {{ $aluno->aluno_nome }}
        </h3>

        <p class="mt-2 text-sm text-gray-600">
            Data de nascimento:
            <strong class="text-gray-800">
                {{ \Carbon\Carbon::parse($aluno->aluno_nascimento)->format('d/m/Y') }}
            </strong> <br>

            Idade:
            <strong class="text-gray-800">
                {{ $aluno->aluno_nascimento ? \Carbon\Carbon::parse($aluno->aluno_nascimento)->age : '-' }}
            </strong>
        </p>
    </div>
</div>

<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">
        HISTÓRICO DE MATRÍCULAS
    </h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Plano</th>
                <th class="py-3 px-4">Professor</th>
                <th class="py-3 px-4">Turma</th>
                <th class="py-3 px-4">Modalidade</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($matriculas as $matricula)
            <tr class="border-b hover:bg-gray-50 transition">

                <td class="py-3 px-4">
                    {{ $matricula->matri_plano }}
                </td>

                <td class="py-3 px-4">
                    {{ $matricula->grade->professor->prof_nome ?? '-' }}
                </td>

                <td class="py-3 px-4">
                    @if($matricula->grade)
                    {{ ucfirst($matricula->grade->grade_turma) }}
                    <span class="text-xs text-gray-500 block">
                        {{ \Carbon\Carbon::parse($matricula->grade->grade_inicio)->format('H:i') }}
                        às
                        {{ \Carbon\Carbon::parse($matricula->grade->grade_fim)->format('H:i') }}
                    </span>
                    @else
                    -
                    @endif
                </td>

                <td class="py-3 px-4">
                    {{ $matricula->grade->grade_modalidade ?? '-' }}
                </td>

                <td class="py-3 px-4">
                    @if ($matricula->matri_status === 'Matriculado')
                    <span class="px-2 py-1 rounded-full text-xs font-semibold"
                        style="background-color: #dcfce7; color: #166534;">
                        Matriculado
                    </span>
                    @else
                    <span class="px-2 py-1 rounded-full text-xs font-semibold"
                        style="background-color: #fee2e2; color: #991b1b;">
                        Encerrada
                    </span>
                    @endif
                </td>

                <td class="py-3 px-4 flex gap-2">

                    {{-- VER MATRÍCULA --}}
                    <a href="{{ route('professor-matricula.show', Crypt::encrypt($matricula->id_matricula)) }}"
                        style="background-color: #174ab9; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                        Ver Matrícula
                    </a>

                    {{-- FINANCEIRO --}}
                    @if(strtolower($aluno->aluno_bolsista) !== 'sim')
                    <a href="{{ route('professor-financeiro', Crypt::encrypt($aluno->id_aluno)) }}"
                        style="background-color: #15803d; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#166534] transition duration-200 text-center">
                        Financeiro
                    </a>
                    @endif

                </td>

            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-6 text-gray-500">
                    Nenhuma matrícula encontrada
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection