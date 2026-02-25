@extends('layouts.dashboard')

@section('title', 'Detalhes da Matrícula')

@section('content')

<!-- TOPO -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">
            Detalhes da Matrícula
        </h2>
        <p class="text-sm text-gray-500 mt-1">
            Informações completas da matrícula selecionada
        </p>
    </div>

    <a href="{{ route('matricula', $matricula->aluno->id_aluno) }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
        ← Voltar
    </a>
</div>

<!-- CARD PRINCIPAL -->
<div class="bg-white rounded-2xl shadow-md p-8">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

        <!-- COLUNA 1 -->
        <div class="space-y-6">

            <div>
                <p class="text-xs uppercase text-gray-400">Aluno</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $matricula->aluno->aluno_nome }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Professor</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $matricula->grade->professor->prof_nome ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Plano</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $matricula->matri_plano }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Data da Matrícula</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($matricula->matri_data)->format('d/m/Y') }}
                </p>
            </div>

        </div>

        <!-- COLUNA 2 -->
        <div class="space-y-6">

            <div>
                <p class="text-xs uppercase text-gray-400">Turma</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ ucfirst($matricula->grade->grade_turma ?? '-') }}
                </p>
            </div>

            <!-- MODALIDADE -->
            <div>
                <p class="text-xs uppercase text-gray-400">Modalidade</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $matricula->grade->grade_modalidade ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Horário</p>
                <p class="text-lg font-semibold text-gray-800">
                    @if($matricula->grade)
                    {{ \Carbon\Carbon::parse($matricula->grade->grade_inicio)->format('H:i') }}
                    às
                    {{ \Carbon\Carbon::parse($matricula->grade->grade_fim)->format('H:i') }}
                    @else
                    -
                    @endif
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Dias da Semana</p>
                <p class="text-lg font-semibold text-gray-800">
                    @if($matricula->grade && $matricula->grade->grade_dia_semana)

                    @php
                    $diasSemana = [
                    1 => 'Domingo',
                    2 => 'Segunda',
                    3 => 'Terça',
                    4 => 'Quarta',
                    5 => 'Quinta',
                    6 => 'Sexta',
                    7 => 'Sábado'
                    ];

                    $diasArray = explode(',', $matricula->grade->grade_dia_semana);

                    $diasFormatados = collect($diasArray)
                    ->map(fn($dia) => $diasSemana[trim($dia)] ?? $dia)
                    ->implode(', ');
                    @endphp

                    {{ $diasFormatados }}

                    @else
                    -
                    @endif
                </p>
            </div>

        </div>


        <!-- STATUS -->
        <div class="mt-10 pt-6 border-t">
            <p class="text-xs uppercase text-gray-400 mb-2">Status</p>

            @if ($matricula->matri_status === 'Matriculado')
            <span class="px-4 py-2 text-sm rounded-full bg-green-100 text-green-700 font-medium">
                Matriculado
            </span>
            @else
            <span class="px-4 py-2 text-sm rounded-full bg-red-100 text-red-700 font-medium">
                Matrícula Encerrada
            </span>
            @endif
        </div>

        <!-- OBSERVAÇÕES -->
        @if($matricula->matri_desc)
        <div class="mt-8 pt-6 border-t">
            <p class="text-xs uppercase text-gray-400 mb-2">Observações</p>
            <p class="text-gray-700 leading-relaxed">
                {{ $matricula->matri_desc }}
            </p>
        </div>
        @endif

    </div>

    @endsection