@extends('layouts.dashboard')

@section('title', 'Detalhes do Horário')

@section('content')

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('professor-agenda') }}"
                class="hover:text-[#8E251F] transition">
                Minha Agenda
            </a>
        </li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Ver Horário</li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">
            INFORMAÇÕES COMPLETAS DO HORÁRIO
        </h2>
    </div>
    <a href="{{ route('professor-agenda') }}"
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
                <p class="text-xs uppercase text-gray-400">Professor</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $grade->professor->prof_nome ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Modalidade</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $grade->grade_modalidade }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Turma</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ ucfirst($grade->grade_turma) }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Dias da Semana</p>
                <p class="text-lg font-semibold text-gray-800">
                    @php
                        $mapaDias = [
                            1 => 'Domingo',
                            2 => 'Segunda-feira',
                            3 => 'Terça-feira',
                            4 => 'Quarta-feira',
                            5 => 'Quinta-feira',
                            6 => 'Sexta-feira',
                            7 => 'Sábado'
                        ];

                        $dias = explode(',', $grade->grade_dia_semana);

                        echo collect($dias)
                            ->map(fn($d) => $mapaDias[$d] ?? $d)
                            ->implode(', ');
                    @endphp
                </p>
            </div>

        </div>

        <!-- COLUNA 2 -->
        <div class="space-y-6">

            <div>
                <p class="text-xs uppercase text-gray-400">Horário de Início</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($grade->grade_inicio)->format('H:i') }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Horário de Fim</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($grade->grade_fim)->format('H:i') }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400">Total de Alunos</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $grade->matriculas->count() }}
                </p>
            </div>

        </div>

    </div>

    <!-- DESCRIÇÃO -->
    @if($grade->grade_desc)
        <div class="mt-10 pt-6 border-t">
            <p class="text-xs uppercase text-gray-400 mb-2">Descrição</p>
            <p class="text-gray-700 leading-relaxed">
                {{ $grade->grade_desc }}
            </p>
        </div>
    @endif

</div>

@endsection