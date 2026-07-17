@extends('layouts.dashboard')

@section('title', 'Detalhes da Aula')

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
        <li class="text-gray-400">{{ $aula->gradeHorario->professor->prof_nome }}</li>
        <li>/</li>
        <li>
            <a href="{{ route('aulas', Crypt::encrypt($aula->gradeHorario->id_grade)) }}"
                class="hover:text-[#8E251F] transition">
                Aulas
            </a>
        </li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Ver Aula</li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">
            INFORMAÇÕES COMPLETAS DA AULA
        </h2>
    </div>
    <a href="{{ url()->previous() ?? route('aulas', Crypt::encrypt($aula->gradeHorario->id_grade)) }}"
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
                <p class="text-xs uppercase text-gray-400">Nome do Exercício</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $aula->aula_nome_exercicio }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Professor</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $aula->gradeHorario->professor->prof_nome ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Modalidade</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $aula->gradeHorario->grade_modalidade ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Dias da Grade</p>
                <p class="text-lg font-semibold text-gray-800">
                    @php
                    $dias = [
                    1 => 'Domingo',
                    2 => 'Segunda',
                    3 => 'Terça',
                    4 => 'Quarta',
                    5 => 'Quinta',
                    6 => 'Sexta',
                    7 => 'Sábado',
                    ];

                    $diasGrade = $aula->gradeHorario->grade_dia_semana
                    ? collect(explode(',', $aula->gradeHorario->grade_dia_semana))
                    ->map(fn($dia) => $dias[(int) trim($dia)] ?? $dia)
                    ->implode(' e ')
                    : '-';
                    @endphp
                    {{ $diasGrade }}
                </p>
            </div>

        </div>

        <!-- COLUNA 2 -->
        <div class="space-y-6">

            <div>
                <p class="text-xs uppercase text-gray-400">Período</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($aula->aula_inicio)->format('d/m/Y') }}
                    até
                    {{ \Carbon\Carbon::parse($aula->aula_fim)->format('d/m/Y') }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Horário da Grade</p>
                <p class="text-lg font-semibold text-gray-800">
                    @if($aula->gradeHorario->grade_inicio && $aula->gradeHorario->grade_fim)
                    {{ \Carbon\Carbon::parse($aula->gradeHorario->grade_inicio)->format('H:i') }}
                    às
                    {{ \Carbon\Carbon::parse($aula->gradeHorario->grade_fim)->format('H:i') }}
                    @else
                    -
                    @endif
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Status</p>

                @if($aula->aula_status === 'ativo')
                <span class="px-4 py-2 text-sm rounded-full bg-green-100 text-green-700 font-medium">
                    Ativo
                </span>
                @else
                <span class="px-4 py-2 text-sm rounded-full bg-gray-200 text-gray-600 font-medium">
                    Inativo
                </span>
                @endif
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Link</p>

                @if($aula->aula_link)
                <a href="{{ $aula->aula_link }}"
                    target="_blank"
                    class="text-lg font-semibold text-[#174ab9] hover:underline break-all">
                    {{ $aula->aula_link }}
                </a>
                @else
                <p class="text-gray-500">Sem link</p>
                @endif
            </div>

        </div>

    </div>

    <!-- CARACTERÍSTICA -->
    @if($aula->aula_caract_exercicio)
    <div class="mt-10 pt-6 border-t">
        <p class="text-xs uppercase text-gray-400 mb-2">Característica do Exercício</p>
        <p class="text-gray-700 leading-relaxed">
            {{ $aula->aula_caract_exercicio }}
        </p>
    </div>
    @endif

    <!-- DESCRIÇÃO -->
    @if($aula->aula_desc)
    <div class="mt-10 pt-6 border-t">
        <p class="text-xs uppercase text-gray-400 mb-2">Descrição</p>
        <p class="text-gray-700 leading-relaxed">
            {{ $aula->aula_desc }}
        </p>
    </div>
    @endif


</div>

@endsection