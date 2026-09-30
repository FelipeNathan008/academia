@extends('layouts.dashboard')

@section('title', 'Detalhes dos Recebimentos')

@section('content')

<x-alert-error />

<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2 flex-wrap">
        <li>
            <a href="{{ route('dashboard') }}" class="hover:text-[#8E251F] transition">
                Dashboard
            </a>
        </li>
        <li>/</li>
        <li>
            <a href="{{ route('dashboard.mapaFinanceiro', ['mes' => $mes, 'ano' => $ano]) }}"
                class="hover:text-[#8E251F] transition">
                Recebimentos
            </a>
        </li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Detalhes</li>
    </ol>
</nav>

<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('dashboard.mapaFinanceiro', ['mes' => $mes, 'ano' => $ano]) }}"
            class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>

        <div>
            <h2 class="text-3xl font-extrabold text-gray-800">
                Recebimentos do dia
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                {{ $dataVencimento->format('d/m/Y') }} -
                {{ ucfirst($dataVencimento->translatedFormat('l')) }}
            </p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-md p-6">
        <p class="text-sm font-medium text-gray-500">
            Data de vencimento
        </p>
        <p class="text-2xl font-extrabold text-gray-800 mt-2">
            {{ $dataVencimento->format('d/m/Y') }}
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6">
        <p class="text-sm font-medium text-gray-500">
            Alunos
        </p>
        <p class="text-2xl font-extrabold text-gray-800 mt-2">
            {{ $detalhes->map(function ($detalhe) {
                return optional($detalhe->mensalidade)->aluno_id_aluno;
            })->filter()->unique()->count() }}
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6">
        <p class="text-sm font-medium text-gray-500">
            Total previsto
        </p>
        <p class="text-2xl font-extrabold text-[#8E251F] mt-2">
            R$ {{ number_format($detalhes->sum(function ($detalhe) {
                return (float) $detalhe->det_mensa_valor;
            }), 2, ',', '.') }}
        </p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-md p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h3 class="text-xl font-bold text-gray-700">
                ALUNOS COM VENCIMENTO
            </h3>
            <p class="text-sm text-gray-400 mt-1">
                Mensalidades previstas para {{ $dataVencimento->format('d/m/Y') }}
            </p>
        </div>

        <span class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-medium capitalize">
            {{ $nomeMes }} de {{ $ano }}
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b text-gray-600 text-sm">
                    <th class="py-3 px-4">Aluno</th>
                    <th class="py-3 px-4">Matrícula</th>
                    <th class="py-3 px-4">Modalidade</th>
                    <th class="py-3 px-4">Turma</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-right">Valor</th>
                    <th class="py-3 px-4 text-right">Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse($detalhes as $detalhe)
                @php
                $mensalidade = $detalhe->mensalidade;
                $matricula = $mensalidade?->matricula;
                $aluno = $matricula?->aluno;
                $grade = $matricula?->grade;
                @endphp

                @if($matricula && $matricula->matri_status !== 'Encerrada')
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="py-4 px-4">
                        <p class="font-semibold text-gray-800">
                            {{ $aluno->aluno_nome ?? '-' }}
                        </p>

                        @if($aluno)
                        <p class="text-sm text-gray-500">
                            Nascimento:
                            {{ $aluno->aluno_nascimento
                                            ? \Carbon\Carbon::parse($aluno->aluno_nascimento)->format('d/m/Y')
                                            : '-' }}
                        </p>
                        @endif
                    </td>

                    <td class="py-4 px-4">
                        <p class="text-sm text-gray-500">
                            Plano:
                            <strong>{{ $matricula->matri_plano ?? '-' }}</strong>
                        </p>
                        <p class="text-sm text-gray-500">
                            Categoria:
                            <strong>{{ $matricula->matri_categoria ?? '-' }}</strong>
                        </p>
                    </td>

                    <td class="py-4 px-4">
                        <p class="font-semibold text-gray-800">
                            {{ $grade->grade_modalidade ?? '-' }}
                        </p>
                    </td>

                    <td class="py-4 px-4">
                        @if($grade)
                        <p class="font-semibold text-gray-800">
                            {{ ucfirst($grade->grade_turma ?? '-') }}
                        </p>

                        <p class="text-sm text-gray-500">
                            Prof. {{ $grade->professor->prof_nome ?? '-' }}
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            {{ $grade->grade_inicio
                                            ? \Carbon\Carbon::parse($grade->grade_inicio)->format('H:i')
                                            : '--:--' }}
                            às
                            {{ $grade->grade_fim
                                            ? \Carbon\Carbon::parse($grade->grade_fim)->format('H:i')
                                            : '--:--' }}
                        </p>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>

                    <td class="py-4 px-4">
                        @if($matricula->matri_status === 'Matriculado')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold"
                            style="background-color:#dcfce7;color:#166534;">
                            Matriculado
                        </span>
                        @elseif($matricula->matri_status === 'Pausada')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold"
                            style="background-color:#fef9c3;color:#854d0e;">
                            Pausada
                        </span>
                        @endif

                        <div class="mt-2">
                            @if($detalhe->det_mensa_status === 'Em aberto')
                            <span class="text-xs text-blue-700 font-semibold">
                                Em aberto
                            </span>
                            @elseif($detalhe->det_mensa_status === 'Atrasado')
                            <span class="text-xs text-red-700 font-semibold">
                                Atrasado
                            </span>
                            @elseif($detalhe->det_mensa_status === 'Comprovante enviado')
                            <span class="text-xs text-yellow-700 font-semibold">
                                Comprovante enviado
                            </span>
                            @elseif($detalhe->det_mensa_status === 'Rejeitado')
                            <span class="text-xs text-red-700 font-semibold">
                                Rejeitado
                            </span>
                            @else
                            <span class="text-xs text-gray-500">
                                {{ $detalhe->det_mensa_status }}
                            </span>
                            @endif
                        </div>
                    </td>

                    <td class="py-4 px-4 text-right">
                        <span class="font-bold text-gray-800">
                            R$ {{ number_format((float) $detalhe->det_mensa_valor, 2, ',', '.') }}
                        </span>
                    </td>

                    <td class="py-4 px-4 text-right">
                        <a href="{{ route('matricula', Crypt::encrypt($matricula->aluno_id_aluno)) }}"
                            style="background-color:#174ab9;color:white;"
                            class="inline-flex px-4 py-2 rounded-lg shadow hover:bg-blue-800 transition">
                            Matrícula
                        </a>
                    </td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="7" class="text-center py-10 text-gray-500">
                        <div class="text-4xl mb-3">✓</div>

                        <p class="font-semibold">
                            Nenhum aluno encontrado
                        </p>

                        <p class="text-sm text-gray-400 mt-1">
                            Não existem mensalidades pendentes para esta data.
                        </p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection