@extends('layouts.dashboard')

@section('title', 'Financeiro do Aluno')

@section('content')

<x-alert-error />

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
            <a href="{{ route('alunos', Crypt::encrypt($aluno->responsavel->id_responsavel)) }}"
                class="hover:text-[#8E251F] transition">
                {{ $aluno->responsavel->resp_nome }}
            </a>
        </li>
        <li>/</li>
        <li>
            <a href="{{ route('alunos', Crypt::encrypt($aluno->responsavel_id_responsavel)) }}"
                class="hover:text-[#8E251F] transition">
                Alunos
            </a>
        </li>
        <li>/</li>
        <li class="text-gray-400">{{ $aluno->aluno_nome }}</li>
        <li>/</li>
        <li>
            <a href="{{ route('detalhes-aluno.index', Crypt::encrypt($aluno->id_aluno)) }}" class="hover:text-[#8E251F] transition">
                Graduações
            </a>
        </li>
        <li>/</li>
        <li>
            <a href="{{ route('matricula', Crypt::encrypt($aluno->id_aluno)) }}"
                class="hover:text-[#8E251F] transition">
                Matrícula
            </a>
        </li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Financeiro</li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('alunos', Crypt::encrypt($aluno->responsavel_id_responsavel)) }}"
            class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>

        <h2 class="text-3xl font-extrabold text-gray-800">
            Matrículas / Financeiro /Mensalidades
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

                                            @case('Comprovante enviado')
                                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg"
                                                style="background-color: #2563eb;">
                                                Comprovante enviado
                                            </span>
                                            @break

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

                                            @case('Rejeitado')
                                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg shadow transition duration-200"
                                                style="background-color: #e71f1f;">
                                                Rejeitado
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

                                            @if($detalhe->det_mensa_status === 'Comprovante enviado')
                                            <button
                                                type="button"
                                                class="btn-analisar px-4 py-2 rounded-lg shadow text-white hover:bg-blue-800 transition"
                                                style="background-color:#174ab9;"
                                                data-modal="modal-{{ $detalhe->id_detalhes_mensalidade }}">
                                                Analisar
                                            </button>
                                            @endif
                                            {{-- Dar baixa --}}
                                            @if($detalhe->det_mensa_status !== 'Pago' && $detalhe->det_mensa_status !== 'Comprovante enviado')
                                            <form action="{{ route('mensalidade.darBaixa', Crypt::encrypt($detalhe->id_detalhes_mensalidade)) }}"
                                                method="POST"
                                                onsubmit="return confirm('Confirmar baixa da parcela?')">
                                                @csrf
                                                @method('PUT')

                                                <button type="submit"
                                                    style="background-color:#15803d;color:white;"
                                                    class="px-4 py-2 rounded-lg shadow hover:bg-[#166534] transition duration-200">
                                                    Dar Baixa
                                                </button>
                                            </form>
                                            @endif

                                            {{-- Desfazer --}}
                                            @if(in_array($detalhe->det_mensa_status, ['Pago', 'Rejeitado']))
                                            <form action="{{ route('mensalidade.desfazerBaixa', Crypt::encrypt($detalhe->id_detalhes_mensalidade)) }}"
                                                method="POST"
                                                onsubmit="return confirm('Deseja desfazer esta operação?')">
                                                @csrf
                                                @method('PUT')

                                                <button type="submit"
                                                    style="background-color:#af2020;color:white;"
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

</div> {{-- fecha o card "LISTAGEM DE MENSALIDADES" --}}

{{-- MODAIS DE ANÁLISE DE COMPROVANTE (fora da tabela, um por detalhe) --}}
@foreach ($mensalidades as $mensalidade)
@foreach ($mensalidade->detalhes as $detalhe)
@if($detalhe->det_mensa_status === 'Comprovante enviado')
<div
    id="modal-{{ $detalhe->id_detalhes_mensalidade }}"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-xl p-8 relative">
        <button
            class="fechar-modal absolute top-4 right-5 text-2xl text-gray-500 hover:text-red-600">
            ×
        </button>
        <h2 class="text-2xl font-bold text-[#174ab9] mb-6">
            Analisar Comprovante
        </h2>
        <div class="space-y-3 mb-8">
            <div class="flex justify-between">
                <span class="font-semibold">Aluno</span>
                <span>{{ $aluno->aluno_nome }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">Mês</span>
                <span>{{ ucfirst($detalhe->det_mensa_mes_vigente) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">Valor</span>
                <span>
                    R$ {{ number_format($detalhe->det_mensa_valor,2,',','.') }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">Status</span>
                <span class="text-blue-600 font-bold">
                    {{ $detalhe->det_mensa_status }}
                </span>
            </div>
        </div>

        <a href="{{ route('mensalidade.comprovante', Crypt::encrypt($detalhe->id_detalhes_mensalidade)) }}"
            target="_blank"
            class="block w-full text-center mb-6 px-4 py-3 rounded-lg bg-[#174ab9] text-white hover:bg-blue-800 transition">
            Ver comprovante
        </a>
        <div class="flex gap-3">
            <form
                class="flex-1"
                method="POST"
                action="{{ route('mensalidade.aprovar', Crypt::encrypt($detalhe->id_detalhes_mensalidade)) }}">
                @csrf
                @method('PUT')
                <button
                    onclick="return confirm('Aprovar comprovante?')"
                    class="w-full py-3 rounded-lg text-white font-semibold bg-green-600 hover:bg-green-700">
                    Aprovar
                </button>
            </form>
            <form
                class="flex-1"
                method="POST"
                action="{{ route('mensalidade.rejeitar', Crypt::encrypt($detalhe->id_detalhes_mensalidade)) }}">
                @csrf
                @method('PUT')
                <button
                    onclick="return confirm('Rejeitar comprovante?')"
                    class="w-full py-3 rounded-lg text-white font-semibold bg-red-600 hover:bg-red-700">
                    Rejeitar
                </button>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach
@endforeach

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

    document.querySelectorAll('.btn-analisar').forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = document.getElementById(this.dataset.modal);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    document.querySelectorAll('.fechar-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.fixed');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        });

    });

    document.querySelectorAll('.fixed').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('flex');
                this.classList.add('hidden');
            }
        });
    });
</script>

@endsection