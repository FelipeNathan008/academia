@extends('layouts.dashboard')

@section('title', 'Financeiro do Aluno')

@section('content')

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('responsaveis') }}"
                class="hover:text-[#8E251F] transition">
                Responsáveis
            </a>
        </li>
        <li>/</li>
        <li>
            <a href="{{ route('alunos', $aluno->responsavel->id_responsavel) }}"
                class="hover:text-[#8E251F] transition">
                {{ $aluno->responsavel->resp_nome }}
            </a>
        </li>
        <li>/</li>
        <li>
            <a href="{{ route('alunos', $aluno->responsavel_id_responsavel) }}"
                class="hover:text-[#8E251F] transition">
                Alunos
            </a>
        </li>
        <li>/</li>
        <li class="text-gray-400">{{ $aluno->aluno_nome }}</li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Financeiro</li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('alunos', $aluno->responsavel_id_responsavel) }}"
            class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>

        <h2 class="text-3xl font-extrabold text-gray-800">
            Mensalidades
        </h2>
    </div>

</div>


<!-- CARD DO ALUNO -->
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

<!-- LISTAGEM DE MENSALIDADES -->
<div class="bg-white rounded-2xl shadow-md p-6">

    <h3 class="text-xl font-bold mb-6 text-gray-700">
        Histórico Financeiro
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

                <td class="py-3 px-4 flex gap-2">

                    <button type="button"
                        data-id="{{ $mensalidade->id_mensalidade }}"
                        class="btn-ver px-4 py-2 rounded-lg shadow text-white"
                        style="background-color: #174ab9;">
                        Ver Mensalidades
                    </button>

                    <button type="button"
                        data-edit="{{ $mensalidade->id_mensalidade }}"
                        class="btn-editar px-4 py-2 rounded-lg shadow text-white"
                        style="background-color: #ca8a04;">
                        Editar Forma
                    </button>

                </td>
            </tr>

            {{-- LINHA OCULTA PARA EDITAR FORMA PAGAMENTO --}}
            <tr id="editar-forma-{{ $mensalidade->id_mensalidade }}" class="hidden bg-yellow-50">
                <td colspan="7" class="px-6 py-6">

                    <form action="{{ route('mensalidade.editarForma') }}"
                        method="POST"
                        onsubmit="return confirm('Confirmar alteração da forma de pagamento para TODAS as parcelas?')">

                        @csrf
                        @method('PUT')

                        <input type="hidden" name="mensalidade_id"
                            value="{{ $mensalidade->id_mensalidade }}">

                        <div class="flex flex-col md:flex-row md:items-end gap-6">

                            {{-- Forma Atual --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Forma Atual
                                </label>

                                <div class="px-3 py-2 border rounded-md bg-gray-50 text-gray-700 text-sm">
                                    {{ $mensalidade->detalhes->first()->det_mensa_forma_pagamento ?? 'Não definida' }}
                                </div>
                            </div>

                            {{-- Nova Forma --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nova Forma
                                </label>

                                <select name="nova_forma"
                                    class="px-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-gray-400"
                                    required>
                                    <option value="">Selecione</option>
                                    <option value="Boleto">Boleto</option>
                                    <option value="Pix">Pix</option>
                                    <option value="Cartão">Cartão</option>
                                </select>
                            </div>

                            {{-- Botão --}}
                            <div>
                                <button type="submit"
                                    class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg shadow transition duration-200"
                                    style="background-color: #15803d;">
                                    Confirmar
                                </button>
                            </div>

                        </div>

                    </form>

                </td>
            </tr>

            {{-- LINHA OCULTA DOS DETALHES --}}
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
                                        <th class="px-4 py-3 text-center">Ações</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-100">

                                    @forelse($mensalidade->detalhes as $detalhe)

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
                                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg shadow transition duration-200"
                                                style="background-color: #15803d;">
                                                Pago
                                            </span>
                                            @break

                                            @case('Atrasado')
                                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg shadow transition duration-200"
                                                style="background-color: #dc2626;">
                                                Atrasado
                                            </span>
                                            @break

                                            @case('Em aberto')
                                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg shadow transition duration-200"
                                                style="background-color: #ca8a04;">
                                                Em aberto
                                            </span>
                                            @break

                                            @case('Bloqueado')
                                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg shadow transition duration-200"
                                                style="background-color: #6b7280;">
                                                Bloqueado
                                            </span>
                                            @break

                                            @default
                                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg shadow transition duration-200"
                                                style="background-color: #9ca3af;">
                                                {{ $detalhe->det_mensa_status }}
                                            </span>

                                            @endswitch
                                        </td>


                                        <td class="px-4 py-3 text-right font-semibold text-gray-800">
                                            R$ {{ number_format($detalhe->det_mensa_valor, 2, ',', '.') }}
                                        </td>

                                        {{-- COLUNA AÇÕES --}}
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
                                        <td colspan="6" class="px-4 py-4 text-center text-gray-400">
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
    document.addEventListener("DOMContentLoaded", function() {

        const botoes = document.querySelectorAll(".btn-ver");

        botoes.forEach(botao => {
            botao.addEventListener("click", function() {

                const id = this.dataset.id;
                const detalhe = document.getElementById("detalhe-" + id);

                if (detalhe.classList.contains("hidden")) {
                    detalhe.classList.remove("hidden");
                } else {
                    detalhe.classList.add("hidden");
                }

            });
        });

    });

    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', function() {

            const id = this.getAttribute('data-edit');
            const linha = document.getElementById('editar-forma-' + id);

            linha.classList.toggle('hidden');
        });
    });
</script>

@endsection