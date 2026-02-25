@extends('layouts.dashboard')

@section('title', 'Visualizar Frequência')

@section('content')

<!-- TOPO -->
<div class="flex justify-between items-center mb-8">
    <h2 class="text-3xl font-extrabold text-gray-800">
        Relatório de Frequência
    </h2>

    <a href="{{ route('frequencia.listagem') }}"
        class="px-4 py-2 border rounded-lg hover:bg-gray-100">
        ← Voltar
    </a>
</div>

<!-- CARD DA GRADE -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#174ab9] rounded-2xl shadow-lg p-6">
        <p class="text-xs uppercase tracking-widest text-gray-500">
            Grade selecionada
        </p>

        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
            {{ $grade->grade_modalidade }}
        </h3>

        <div class="mt-4 space-y-3 text-sm text-gray-600">

            <div>
                <span class="font-semibold text-gray-800">Professor:</span><br>
                {{ $grade->professor->prof_nome ?? '-' }}
            </div>

            <div>
                <span class="font-semibold text-gray-800">Horário:</span><br>
                {{ ucfirst($grade->grade_turma) }}
                <span class="text-xs text-gray-500 block">
                    {{ \Carbon\Carbon::parse($grade->grade_inicio)->format('H:i') }}
                    às
                    {{ \Carbon\Carbon::parse($grade->grade_fim)->format('H:i') }}
                </span>
            </div>


        </div>
    </div>
</div>



<!-- TABELA -->
<div class="bg-white rounded-2xl shadow-md p-6">

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Aluno</th>
                <th class="py-3 px-4">Total Aulas</th>
                <th class="py-3 px-4">Presenças</th>
                <th class="py-3 px-4">Faltas</th>
                <th class="py-3 px-4">Presença (%)</th>
                <th class="py-3 px-4">Meta (%)</th>
            </tr>
        </thead>

        <tbody>
            @foreach($grade->matriculas as $matricula)

            @php
            $totalAulas = $matricula->frequencias->count();

            $presencas = $matricula->frequencias
            ->where('freq_presenca', 'Presente')
            ->count();

            $faltas = $matricula->frequencias
            ->where('freq_presenca', 'Falta')
            ->count();

            $percentual = $totalAulas > 0
            ? round(($presencas / $totalAulas) * 100)
            : 0;
            $meta = 120;
            $percentualMeta = round(($presencas / $meta) * 100)
 
            @endphp

            <tr class="border-b hover:bg-gray-50 transition">
                <td class="py-3 px-4 font-semibold">
                    {{ $matricula->aluno->aluno_nome ?? '-' }}
                </td>

                <td class="py-3 px-4">
                    {{ $totalAulas }}
                </td>

                <td class="py-3 px-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold text-green-800 bg-green-100">
                        {{ $presencas }}
                    </span>
                </td>

                <td class="py-3 px-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold text-red-800 bg-red-100">
                        {{ $faltas }}
                    </span>
                </td>

                <td class="py-3 px-4 font-semibold">
                    {{ $percentual }}%
                </td>

                <td class="py-3 px-4 font-semibold">
                    {{ $percentualMeta }}%
                </td>
            </tr>

            @endforeach
        </tbody>
    </table>

</div>

@endsection