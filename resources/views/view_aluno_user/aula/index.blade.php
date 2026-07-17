@extends('layouts.dashboard')

@section('title', 'Aulas')

@section('content')

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('aluno.index') }}" class="hover:text-[#8E251F] transition">
                Meus Alunos
            </a>
        </li>
        <li>/</li>
        <li class="text-gray-400">{{ $grade->professor->prof_nome ?? '-' }}</li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Aulas</li>
    </ol>
</nav>


<!-- TOPO -->
<div class="flex justify-between items-center mb-10">

    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">
           AULAS DA TURMA
        </h2>
    </div>

    <a href="{{route('aluno.index') }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
        ← Voltar
    </a>

</div>



@php
$diasNomes = [
1 => 'Domingo',
2 => 'Segunda',
3 => 'Terça',
4 => 'Quarta',
5 => 'Quinta',
6 => 'Sexta',
7 => 'Sábado',
];

$diasSelecionados = $grade->grade_dia_semana
? collect(explode(',', $grade->grade_dia_semana))
->map(fn($d) => $diasNomes[(int) trim($d)] ?? $d)
->implode(' e ')
: '-';
@endphp

<!-- CARD DA GRADE -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#174ab9] rounded-2xl shadow-lg p-6">
        <p class="text-xs uppercase tracking-widest text-gray-500">Turma</p>

        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
            {{ $grade->grade_modalidade }}
        </h3>

        <p class="mt-2 text-sm text-gray-600">
            Professor:
            <strong class="text-gray-800">{{ $grade->professor->prof_nome ?? '-' }}</strong>
        </p>

        <p class="mt-1 text-sm text-gray-600">
            Dias: <strong class="text-gray-800">{{ $diasSelecionados }}</strong>
            —
            Horário:
            <strong class="text-gray-800">
                {{ \Carbon\Carbon::parse($grade->grade_inicio)->format('H:i') }}
                às
                {{ \Carbon\Carbon::parse($grade->grade_fim)->format('H:i') }}
            </strong>
        </p>
    </div>
</div>

<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6 mb-6">

    <h3 class="text-xl font-bold mb-6 text-gray-700">
        AULAS DISPONÍVEIS
    </h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Exercício</th>
                <th class="py-3 px-4">Característica</th>
                <th class="py-3 px-4">Período</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($aulas as $aula)
            <tr class="border-b hover:bg-gray-50 transition">

                <td class="py-3 px-4">{{ $aula->aula_nome_exercicio }}</td>

                <td class="py-3 px-4">{{ $aula->aula_caract_exercicio }}</td>

                <td class="py-3 px-4">
                    {{ \Carbon\Carbon::parse($aula->aula_inicio)->format('d/m/Y') }}
                    até
                    {{ \Carbon\Carbon::parse($aula->aula_fim)->format('d/m/Y') }}
                </td>

                <td class="py-3 px-4">
                    <a href="{{ route('aluno-aulas.show', Crypt::encrypt($aula->id_aula)) }}"
                        style="background-color: #174ab9; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition duration-200 text-center inline-block">
                        Ver
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-6 text-gray-500">
                    Nenhuma aula disponível para esta turma
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection