@extends('layouts.dashboard')

@section('title', 'Financeiro do Aluno')

@section('content')

<x-alert-error />

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2 flex-wrap">
        <li>
            <a href="{{ route('aluno.index') }}" class="hover:text-[#8E251F] transition">
                Alunos
            </a>
        </li>
        <li>/</li>
        <li>
            <a href="{{ route('aluno.show', Crypt::encrypt($aluno->id_aluno)) }}" class="hover:text-[#8E251F] transition">
                {{ $aluno->aluno_nome }}
            </a>
        </li>
        <li>/</li>
        <li class="font-semibold text-gray-700">
            Financeiro
        </li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('aluno.index', Crypt::encrypt($aluno->id_aluno)) }}"
            class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>
        <h2 class="text-3xl font-extrabold text-gray-800">
            Financeiro / Mensalidades
        </h2>
    </div>
</div>

<!-- CARD -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#15803d] rounded-2xl shadow-lg p-6">
        <p class="text-xs uppercase tracking-widest text-gray-500">
            Aluno selecionado
        </p>
        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
            {{ $aluno->aluno_nome }}
        </h3>
    </div>
</div>

<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">
        HISTÓRICO FINANCEIRO
    </h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Plano</th>
                <th class="py-3 px-4">Professor</th>
                <th class="py-3 px-4">Turma</th>
                <th class="py-3 px-4">Modalidade</th>
                <th class="py-3 px-4">Vencimento</th>
                <th class="py-3 px-4">Valor</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mensalidades as $mensalidade)

            @php
            // Verifica se essa mensalidade tem ao menos 1 detalhe pago via Pix
            $temPix = $mensalidade->detalhes->contains(function ($d) {
            return $d->det_mensa_forma_pagamento
            && strtolower(trim($d->det_mensa_forma_pagamento)) === 'pix';
            });
            @endphp

            <tr class="border-b hover:bg-gray-50 transition">
                <td class="py-3 px-4">
                    {{ $mensalidade->matricula->matri_plano ?? '-' }}
                </td>
                <td class="py-3 px-4">
                    {{ $mensalidade->matricula->grade->professor->prof_nome ?? '-' }}
                </td>
                <td class="py-3 px-4">
                    @if($mensalidade->matricula && $mensalidade->matricula->grade)
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
                <td class="py-3 px-4 font-semibold text-gray-800">
                    R$ {{ number_format($mensalidade->mensa_valor, 2, ',', '.') }}
                </td>
                <td class="py-3 px-4">
                    <button type="button"
                        data-id="{{ $mensalidade->id_mensalidade }}"
                        class="btn-ver px-4 py-2 rounded-lg shadow text-white"
                        style="background-color: #174ab9;">
                        Ver Mensalidades
                    </button>
                </td>
            </tr>

            <!-- DETALHES -->
            <tr id="detalhe-{{ $mensalidade->id_mensalidade }}" class="hidden bg-gray-50">
                <td colspan="7" class="px-6 py-6">
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
                                        @if($temPix)
                                        <th class="px-4 py-3 text-center">Ações</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($mensalidade->detalhes as $detalhe)

                                    @php
                                    $formaPix = $detalhe->det_mensa_forma_pagamento
                                    && strtolower(trim($detalhe->det_mensa_forma_pagamento)) === 'pix';
                                    $estaPago = $detalhe->det_mensa_status === 'Pago';
                                    @endphp

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
                                            @case('Comprovante enviado')
                                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg"
                                                style="background-color: #2563eb;">
                                                Comprovante enviado
                                            </span>
                                            @break
                                            @case('Pago')
                                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg"
                                                style="background-color: #15803d;">
                                                Pago
                                            </span>
                                            @break
                                            @case('Atrasado')
                                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg"
                                                style="background-color: #dc2626;">
                                                Atrasado
                                            </span>
                                            @break
                                            @case('Rejeitado')
                                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg shadow transition duration-200"
                                                style="background-color: #e71f1f;">
                                                Rejeitado
                                            </span>
                                            @break
                                            @case('Em aberto')
                                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg"
                                                style="background-color: #ca8a04;">
                                                Em aberto
                                            </span>
                                            @break
                                            @default
                                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg bg-gray-400">
                                                {{ $detalhe->det_mensa_status }}
                                            </span>
                                            @endswitch
                                        </td>
                                        <td class="px-4 py-3 text-right font-semibold text-gray-800">
                                            R$ {{ number_format($detalhe->det_mensa_valor, 2, ',', '.') }}
                                        </td>

                                        @if($temPix)
                                        <td class="px-4 py-3 text-center">
                                            @if(!$formaPix)
                                            <span class="text-gray-400 text-xs">-</span>

                                            @elseif($estaPago)
                                            <span title="Pagamento já efetuado"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 text-gray-400 cursor-not-allowed mx-auto">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </span>
                                            @else
                                            <div class="flex items-center justify-center gap-2">
                                                <a
                                                    href="{{ route('aluno-financeiro.pagamento.show', Crypt::encrypt($detalhe->id_detalhes_mensalidade)) }}"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-full shadow text-white transition hover:scale-105"
                                                    style="background:#059669;"
                                                    title="Pagar via Pix">

                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-5 h-5"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor">

                                                        <path stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v1m0 7v1m9-5a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>

                                                </a>

                                            </div>
                                            @endif
                                        </td>
                                        @endif
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
                <td colspan="7" class="text-center py-6 text-gray-500">
                    Nenhuma mensalidade cadastrada
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>


<script>
    document.addEventListener("DOMContentLoaded", () => {

        document.querySelectorAll(".btn-ver").forEach(botao => {

            botao.addEventListener("click", () => {

                const detalhe = document.getElementById(
                    `detalhe-${botao.dataset.id}`
                );

                detalhe.classList.toggle("hidden");

            });

        });

    });
</script>

@endsection