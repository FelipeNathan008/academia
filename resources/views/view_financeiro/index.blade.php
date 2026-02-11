@extends('layouts.dashboard')

@section('title', 'Financeiro')

@section('content')

<div class="max-w-6xl mx-auto">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Financeiro
            </h1>
            <p class="text-gray-500">
                {{ $matricula->aluno->aluno_nome }}
            </p>
        </div>

        <a href="{{ route('matricula.index') }}"
            class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>
    </div>

    @if($mensalidade)

    {{-- RESUMO FINANCEIRO --}}
    <div class="grid grid-cols-3 gap-6 mb-8">

        <div class="bg-white p-6 rounded-2xl shadow border-l-4 border-green-600">
            <p class="text-gray-500 text-sm">Valor Mensal</p>
            <p class="text-2xl font-bold text-green-600">
                R$ {{ number_format($mensalidade->mensa_valor, 2, ',', '.') }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow border-l-4 border-blue-600">
            <p class="text-gray-500 text-sm">Dia de Vencimento</p>
            <p class="text-2xl font-bold text-blue-600">
                Dia {{ $mensalidade->mensa_dia_venc }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow border-l-4 border-purple-600">
            <p class="text-gray-500 text-sm">Total de Pagamentos</p>
            <p class="text-2xl font-bold text-purple-600">
                {{ $mensalidade->detalhes->count() }}
            </p>
        </div>

    </div>

    {{-- BOTÃO REGISTRAR PAGAMENTO --}}
    <div class="mb-6">
        <a href="#"
            class="px-5 py-2 bg-green-700 text-white rounded-lg shadow hover:bg-green-800 transition">
            + Registrar Pagamento
        </a>
    </div>

    {{-- HISTÓRICO --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-700">
                Histórico de Pagamentos
            </h2>
        </div>

        <table class="w-full text-left">
            <thead class="bg-gray-100 text-gray-600 text-sm uppercase">
                <tr>
                    <th class="px-6 py-3">Período</th>
                    <th class="px-6 py-3">Forma de Pagamento</th>
                    <th class="px-6 py-3">Data de Vencimento</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">

                @foreach($mensalidade->detalhes as $det)
                <tr class="border-t hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        {{ $det->det_mensa_per_vig_pago }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $det->det_mensa_forma_pagamento }}
                    </td>
                    <td class="px-6 py-4">
                        {{ \Carbon\Carbon::parse($det->det_mensa_data_venc)->format('d/m/Y') }}
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    @else

    {{-- ESTADO VAZIO BONITO --}}
    <div class="bg-white rounded-2xl shadow p-10 text-center">
        <div class="text-5xl mb-4">💰</div>
        <h2 class="text-xl font-semibold text-gray-700 mb-2">
            Nenhuma mensalidade cadastrada
        </h2>
        <p class="text-gray-500 mb-6">
            Este aluno ainda não possui informações financeiras.
        </p>

        <a href="#"
            class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] transition">
            Cadastrar Mensalidade
        </a>
    </div>

    @endif

</div>

@endsection