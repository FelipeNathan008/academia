@extends('layouts.dashboard')

@section('title', 'Mensalidades Atrasadas')

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
            Mensalidades Atrasadas
        </li>
    </ol>
</nav>

<div class="flex justify-between items-center mb-10">
    <h2 class="text-3xl font-extrabold text-red-600">
        Mensalidades Atrasadas
    </h2>

    <a href="{{ route('dashboard') }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
        ← Voltar
    </a>
</div>

<div class="mb-8">
    <div class="bg-white border-l-8 border-red-600 rounded-2xl shadow-lg p-6 ">
        <p class="text-xs uppercase tracking-widest text-gray-500">
            Total de Mensalidades com Atraso
        </p>

        <h3 class="text-2xl font-extrabold text-red-600 mt-1">
            {{ $mensalidadesAtrasadas}}
        </h3>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-md p-6">

    <h3 class="text-xl font-bold mb-6 text-gray-700">
        Listagem Geral
    </h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Aluno</th>
                <th class="py-3 px-4">Responsável</th>
                <th class="py-3 px-4">Plano</th>
                <th class="py-3 px-4">Professor</th>
                <th class="py-3 px-4">Turma</th>
                <th class="py-3 px-4">Modalidade</th>
                <th class="py-3 px-4">Vencimento</th>
                <th class="py-3 px-4 text-right">Valor</th>
                <th class="py-3 px-4 text-center">Ações</th>
            </tr>
        </thead>

        <tbody>

            @forelse($mensalidades as $mensalidade)

            @php
            $parcelaAtrasada = $mensalidade->detalhes
            ->where('det_mensa_status', 'Atrasado')
            ->sortBy('det_mensa_data_venc')
            ->first();

            @endphp
            <tr class="border-b hover:bg-red-50 transition">

                <td class="py-3 px-4 font-semibold">
                    {{ $mensalidade->matricula->aluno->aluno_nome ?? '-' }}
                </td>

                <td class="py-3 px-4">
                    {{ $mensalidade->matricula->aluno->responsavel->resp_nome ?? '-' }}
                </td>

                <td class="py-3 px-4">
                    {{ $mensalidade->matricula->matri_plano ?? '-' }}
                </td>
                <td class="py-3 px-4">
                    {{ $mensalidade->matricula->professor->prof_nome ?? '-' }}
                </td>


                <td class="py-3 px-4">
                    @if($mensalidade->matricula)
                    {{ ucfirst($mensalidade->matricula->grade->grade_turma) }}
                    <span class="text-xs text-gray-500 block">
                        {{ \Carbon\Carbon::parse($mensalidade->matricula->grade->grade_inicio)->format('H:i') }}
                        às
                        {{ \Carbon\Carbon::parse($mensalidade->matricula->grade->grade_fim)->format('H:i') }}
                    </span>
                    @else
                    -
                    @endif
                </td>


                <td class="py-3 px-4">
                    {{ $mensalidade->matricula->grade->grade_modalidade ?? '-' }}
                </td>

                <td class="py-3 px-4">
                    Dia {{ $mensalidade->mensa_dia_venc }}
                </td>

                <td class="py-3 px-4 text-right font-bold text-red-600">
                    R$ {{ number_format($parcelaAtrasada->det_mensa_valor ?? 0, 2, ',', '.') }}
                </td>

                {{-- Ações --}}
                <td class="py-3 px-4 text-center">
                    <button type="button"
                        data-id="{{ $mensalidade->id_mensalidade }}"
                        class="btn-ver px-4 py-2 rounded-lg shadow text-white"
                        style="background-color: #174ab9;"> Ver Detalhes
                    </button>
                    <a href="{{ route('mensalidade', $mensalidade->aluno->id_aluno) }}"
                        style="background-color: #15803d; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#166534] transition duration-200 text-center">
                        Financeiro
                    </a>
                </td>


            </tr>

            <!-- LINHA OCULTA DETALHES-->
            <tr id="detalhe-{{ $mensalidade->id_mensalidade }}" class="hidden bg-gray-50">
                <td colspan="10" class="px-6 py-6">

                    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">

                        <div class="px-4 py-3 bg-gray-100 border-b">
                            <h4 class="text-sm font-semibold text-gray-700">
                                Detalhamento das Parcelas
                            </h4>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                                    <tr>
                                        <th class="px-4 py-3">Mês</th>
                                        <th class="px-4 py-3">Vencimento</th>
                                        <th class="px-4 py-3">Pagamento</th>
                                        <th class="px-4 py-3">Forma</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3 text-right">Valor</th>
                                        <th class="px-4 py-3 text-center">Ações</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-100">

                                    @forelse($mensalidade->detalhes->sortBy('det_mensa_data_venc') as $detalhe)

                                    <tr class="hover:bg-gray-50 transition">

                                        <td class="px-4 py-3">
                                            {{ $detalhe->det_mensa_mes_vigente }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ \Carbon\Carbon::parse($detalhe->det_mensa_data_venc)->format('d/m/Y') }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $detalhe->det_mensa_data_pagamento 
                                    ? \Carbon\Carbon::parse($detalhe->det_mensa_data_pagamento)->format('d/m/Y') 
                                    : 'Sem Pagamento' }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $detalhe->det_mensa_forma_pagamento ?? '-' }}
                                        </td>

                                        <td class="px-4 py-3">
                                            @switch($detalhe->det_mensa_status)

                                            @case('Pago')
                                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg shadow"
                                                style="background-color: #15803d;">
                                                Pago
                                            </span>
                                            @break

                                            @case('Atrasado')
                                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg shadow"
                                                style="background-color: #dc2626;">
                                                Atrasado
                                            </span>
                                            @break

                                            @case('Em aberto')
                                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg shadow"
                                                style="background-color: #ca8a04;">
                                                Em aberto
                                            </span>
                                            @break

                                            @case('Bloqueado')
                                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg shadow"
                                                style="background-color: #6b7280;">
                                                Bloqueado
                                            </span>
                                            @break

                                            @default
                                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg shadow"
                                                style="background-color: #9ca3af;">
                                                {{ $detalhe->det_mensa_status }}
                                            </span>
                                            @endswitch
                                        </td>

                                        <td class="px-4 py-3 text-right font-semibold text-gray-800">
                                            R$ {{ number_format($detalhe->det_mensa_valor, 2, ',', '.') }}
                                        </td>

                                        <td class="px-4 py-3 text-center">

                                            @if($detalhe->det_mensa_status != 'Pago')

                                            <form action="{{ route('mensalidade.darBaixa', ['id' => $detalhe->id_detalhes_mensalidade]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Confirmar baixa da parcela?')">

                                                @csrf
                                                @method('PUT')

                                                <button type="submit"
                                                    style="background-color: #15803d; color: white;"
                                                    class="px-4 py-2 rounded-lg shadow hover:bg-[#166534] transition duration-200">
                                                    Dar Baixa
                                                </button>
                                            </form>

                                            @else

                                            <form action="{{ route('mensalidade.desfazerBaixa', ['id' => $detalhe->id_detalhes_mensalidade]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Deseja desfazer a baixa desta parcela?')">

                                                @csrf
                                                @method('PUT')

                                                <button type="submit"
                                                    style="background-color: #af2020; color: white;"
                                                    class="px-4 py-2 rounded-lg shadow hover:bg-[#b91c1c] transition duration-200">
                                                    Desfazer
                                                </button>
                                            </form>

                                            @endif

                                        </td>

                                    </tr>

                                    @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-4 text-center text-gray-400">
                                            Nenhum detalhe cadastrado.
                                        </td>
                                    </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>

                    </div>

                </td>
            </tr>

            @empty
            <tr>
                <td colspan="5" class="text-center py-6 text-gray-500">
                    Nenhuma mensalidade em atraso 🎉
                </td>
            </tr>
            @endforelse

        </tbody>
    </table>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        document.querySelectorAll(".btn-ver").forEach(botao => {
            botao.addEventListener("click", function() {

                const id = this.dataset.id;
                const detalhe = document.getElementById("detalhe-" + id);

                detalhe.classList.toggle("hidden");

            });
        });

    });
</script>

@endsection