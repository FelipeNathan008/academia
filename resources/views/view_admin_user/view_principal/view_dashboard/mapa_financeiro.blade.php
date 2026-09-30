@extends('layouts.dashboard')

@section('title', 'Previsão de Recebimentos')

@section('content')

<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('dashboard') }}"
                class="hover:text-[#8E251F] transition">
                Dashboard
            </a>
        </li>
        <li>/</li>
        <li class="font-semibold text-gray-700">
            Recebimentos
        </li>
    </ol>
</nav>

<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.principal') }}"
            class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>

        <div>
            <h2 class="text-3xl font-extrabold text-gray-800">
                Previsão de Recebimentos
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Acompanhe os valores previstos para recebimento
            </p>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-md p-6 mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                Período selecionado
            </p>
            <h3 class="text-2xl font-bold text-gray-800 mt-1 capitalize">
                {{ $nomeMes }} de {{ $ano }}
            </h3>
        </div>

        <div class="flex items-center gap-2">
            @php
            $mesAnterior = $inicioMes->copy()->subMonth();
            $proximoMes = $inicioMes->copy()->addMonth();
            $hoje = now();
            @endphp

            <a href="{{ route('dashboard.mapaFinanceiro', ['mes' => $mesAnterior->month, 'ano' => $mesAnterior->year]) }}"
                class="w-11 h-11 flex items-center justify-center border border-gray-300 rounded-xl text-gray-600 hover:bg-gray-100 transition"
                title="Mês anterior">
                ←
            </a>

            <a href="{{ route('dashboard.mapaFinanceiro', ['mes' => $hoje->month, 'ano' => $hoje->year]) }}"
                class="px-5 h-11 flex items-center justify-center rounded-xl bg-[#8E251F] text-white hover:bg-[#732920] transition shadow-md">
                Hoje
            </a>

            <a href="{{ route('dashboard.mapaFinanceiro', ['mes' => $proximoMes->month, 'ano' => $proximoMes->year]) }}"
                class="w-11 h-11 flex items-center justify-center border border-gray-300 rounded-xl text-gray-600 hover:bg-gray-100 transition"
                title="Próximo mês">
                →
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-md p-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total a receber</p>
                <p class="text-3xl font-extrabold text-gray-800 mt-2">
                    R$ {{ number_format($totalAReceber, 2, ',', '.') }}
                </p>
                <p class="text-xs text-gray-400 mt-2">
                    Previsão para {{ $nomeMes }}
                </p>
            </div>

            <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
                <span class="text-xl text-[#8E251F]">$</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Alunos com vencimento</p>
                <p class="text-3xl font-extrabold text-gray-800 mt-2">
                    {{ $totalAlunos }}
                </p>
                <p class="text-xs text-gray-400 mt-2">
                    Alunos distintos durante o mês
                </p>
            </div>

            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                <span class="text-xl text-blue-700">👥</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Dias com recebimentos</p>
                <p class="text-3xl font-extrabold text-gray-800 mt-2">
                    {{ $diasComRecebimentos }}
                </p>
                <p class="text-xs text-gray-400 mt-2">
                    De {{ $inicioMes->daysInMonth }} dias
                </p>
            </div>

            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                <span class="text-xl text-green-700">✓</span>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-md p-6 mb-8">
    @if($proximo && $dataProximo)
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 rounded-2xl bg-[#8E251F] text-white flex items-center justify-center text-xl font-bold">
                {{ $dataProximo->day }}
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    Próximo recebimento
                </p>
                <h3 class="text-xl font-bold text-gray-800 mt-1 capitalize">
                    {{ $dataProximo->day }} de {{ $dataProximo->translatedFormat('F') }}
                </h3>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $proximo['quantidade_alunos'] }}
                    {{ $proximo['quantidade_alunos'] == 1 ? 'aluno possui' : 'alunos possuem' }}
                    vencimento nesta data
                </p>
            </div>
        </div>

        <div class="flex items-center justify-between lg:justify-end gap-6">
            <div class="text-right">
                <p class="text-xs text-gray-400">Valor previsto</p>
                <p class="text-2xl font-extrabold text-[#8E251F]">
                    R$ {{ number_format($proximo['valor'], 2, ',', '.') }}
                </p>
            </div>

            <a href="{{ route('dashboard.listaMapaFinanceiro', ['dia' => $dataProximo->day,'mes' => $mes,'ano' => $ano]) }}"
                class="px-5 py-3 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
                Ver detalhes
            </a>
        </div>
    </div>
    @else
    <div class="text-center py-6">
        <div class="text-4xl mb-3">✓</div>
        <h3 class="text-lg font-bold text-gray-700">
            Nenhum recebimento previsto
        </h3>
        <p class="text-sm text-gray-400 mt-1">
            Não existem mensalidades pendentes para este período.
        </p>
    </div>
    @endif
</div>

<div class="bg-white rounded-2xl shadow-md p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h3 class="text-xl font-bold text-gray-700">
                Recebimentos por dia
            </h3>
            <p class="text-sm text-gray-400 mt-1">
                Valores previstos conforme as datas de vencimento
            </p>
        </div>

        <span class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-medium capitalize">
            {{ $nomeMes }} de {{ $ano }}
        </span>
    </div>

    <div class="hidden md:block overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b text-gray-500 text-sm">
                    <th class="py-4 px-4 text-left">Data</th>
                    <th class="py-4 px-4 text-left">Dia</th>
                    <th class="py-4 px-4 text-left">Qtd. Matrículas</th>
                    <th class="py-4 px-4 text-right">Valor a receber</th>
                    <th class="py-4 px-4 text-right">Ações</th>
                </tr>
            </thead>

            <tbody>
                @foreach($dias as $dia)
                @php
                $temRecebimento = $dia['valor'] > 0;
                $ehHoje = $dia['data']->isToday();
                @endphp

                <tr class="border-b transition {{ $temRecebimento ? 'hover:bg-gray-50' : 'bg-gray-50/50' }} {{ $ehHoje ? 'bg-red-50' : '' }}">
                    <td class="py-4 px-4">
                        <span class="font-semibold {{ $ehHoje ? 'text-[#8E251F]' : 'text-gray-800' }}">
                            {{ $dia['data']->format('d/m/Y') }}
                        </span>

                        @if($ehHoje)
                        <span class="ml-2 text-xs bg-[#8E251F] text-white px-2 py-1 rounded-full">
                            Hoje
                        </span>
                        @endif
                    </td>

                    <td class="py-4 px-4 text-gray-600 capitalize">
                        {{ $dia['data']->translatedFormat('l') }}
                    </td>

                    <td class="py-4 px-4">
                        @if($dia['quantidade_alunos'] > 0)
                        <span class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
                            👥 {{ $dia['quantidade_alunos'] }}
                        </span>
                        @else
                        <span class="text-gray-400 text-sm">—</span>
                        @endif
                    </td>

                    <td class="py-4 px-4 text-right">
                        @if($temRecebimento)
                        <span class="font-bold text-gray-800">
                            R$ {{ number_format($dia['valor'], 2, ',', '.') }}
                        </span>
                        @else
                        <span class="text-gray-400">R$ 0,00</span>
                        @endif
                    </td>

                    <td class="py-4 px-4 text-right">
                        @if($temRecebimento)

                        <a href="{{ route('dashboard.listaMapaFinanceiro', ['dia' => $dia['dia'], 'mes' => $mes, 'ano' => $ano]) }}"
                            style="background-color:#11388c;color:white;"
                            class="inline-flex px-4 py-2 rounded-lg shadow hover:bg-blue-800 transition">
                            Detalhes
                        </a>

                        @else

                        <span class="text-gray-400">
                            —
                        </span>

                        @endif
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="md:hidden space-y-4">
        @foreach($dias as $dia)
        @php
        $temRecebimento = $dia['valor'] > 0;
        $ehHoje = $dia['data']->isToday();
        @endphp

        <div class="border rounded-xl p-4 {{ $ehHoje ? 'border-[#8E251F] bg-red-50' : 'border-gray-200' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase font-semibold capitalize">
                        {{ $dia['data']->translatedFormat('l') }}
                    </p>
                    <p class="text-lg font-bold text-gray-800">
                        {{ $dia['data']->format('d/m/Y') }}
                    </p>
                </div>

                @if($dia['quantidade_alunos'] > 0)
                <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
                    👥 {{ $dia['quantidade_alunos'] }}
                </span>
                @endif
            </div>

            <div class="border-t mt-4 pt-4 flex items-center justify-between">
                <span class="text-sm text-gray-500">Valor a receber</span>
                <span class="font-bold {{ $temRecebimento ? 'text-[#8E251F]' : 'text-gray-400' }}">
                    R$ {{ number_format($dia['valor'], 2, ',', '.') }}
                </span>
            </div>

            @if($temRecebimento)

            <a href="{{ route('dashboard.listaMapaFinanceiro', ['dia' => $dia['dia'],'mes' => $mes,'ano' => $ano]) }}"
                class="w-full mt-4 px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-100 transition text-center block">
                Ver detalhes
            </a>

            @endif
        </div>
        @endforeach
    </div>
</div>

@endsection