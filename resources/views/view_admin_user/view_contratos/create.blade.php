@extends('layouts.dashboard')

@section('title', 'Contrato do Plano')

@section('content')

<x-alert-error />

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2 flex-wrap">
        <li>
            <a href="{{ route('admin.planos') }}"
                class="hover:text-[#8E251F] transition">
                Planos
            </a>
        </li>

        <li>/</li>

        <li class="font-semibold text-gray-700">
            Contrato
        </li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">

    <div>
        <h2 class="text-3xl font-bold text-gray-800">
            Contrato do Plano
        </h2>

        <p class="text-gray-500 mt-2">
            Preencha os dados abaixo para gerar o contrato do plano escolhido.
        </p>
    </div>

    <a href="{{ route('admin.planos') }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
        ← Voltar
    </a>

</div>


@if($contrato)

<div class="mb-8">
    <div class="bg-white rounded-2xl shadow-md border-l-8 border-[#8E251F] p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
            <div>

                <p class="text-xs uppercase tracking-widest text-gray-500">
                    Contrato atual
                </p>


                <p class="text-gray-600 mt-2">
                    Status:
                    <span class="font-semibold
                    @if($contrato->contrato_status == 'Ativo')
                    text-green-600
                   @elseif($contrato->contrato_status == 'Pendente')
                    text-yellow-600
                   @else
                    text-red-600
                   @endif">
                        {{ $contrato->contrato_status }}
                    </span>
                </p>

                <p class="text-sm text-gray-500 mt-1">
                    Criado em:
                    {{ $contrato->created_at?->format('d/m/Y') }}
                </p>

            </div>

            <div>
                <a href="{{ route('contrato.show', Crypt::encrypt($contrato->id_contrato)) }}"
                    class="inline-flex items-center px-5 py-2 rounded-lg bg-[#8E251F] text-white hover:bg-[#732920] transition">

                    Ver contrato
                </a>
            </div>
        </div>
    </div>
</div>

@endif

<form
    action="{{ route('contrato.store', Crypt::encrypt($plano->id_plano)) }}"
    method="POST"
    id="formContrato">

    @csrf

    <!-- CARD DO PLANO -->
    <div class="bg-white rounded-2xl shadow-md p-6 mb-8">

        <h3 class="text-xl font-bold text-gray-700 mb-6">
            Plano Selecionado
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div>

                <label class="text-sm font-medium text-gray-500">
                    Plano
                </label>

                <div class="mt-1 text-lg font-semibold">
                    {{ $plano->plano_nome }}
                </div>

            </div>

            <div>

                <label class="text-sm font-medium text-gray-500">
                    Valor Mensal
                </label>

                <div class="mt-1 text-lg font-semibold text-green-600">
                    R$
                    {{ number_format($plano->plano_valor,2,',','.') }}
                </div>

            </div>

            <div>

                <label class="text-sm font-medium text-gray-500">
                    Limite
                </label>

                <div class="mt-1 text-lg font-semibold">

                    @if($plano->plano_tipo == 'financeiro')

                    Pacote adicional

                    @else

                    {{ $plano->plano_quantidade }}
                    matrículas

                    @endif

                </div>

            </div>

        </div>

    </div>

    <!-- DADOS -->
    <div class="bg-white rounded-2xl shadow-md p-8">

        <h3 class="text-xl font-bold text-gray-700 mb-8">
            Dados para Contrato
        </h3>

        <div class="mb-8 bg-amber-50 border-l-4 border-amber-500 rounded-xl p-5">

            <div class="flex items-start gap-3">

                <svg class="w-6 h-6 text-amber-600 mt-0.5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z" />
                </svg>

                <div>

                    <h4 class="font-semibold text-amber-800">
                        Importante
                    </h4>

                    <ul class="mt-2 text-sm text-amber-700 space-y-2 list-disc list-inside">

                        <li>
                            Todos os dados da empresa (identificação e endereço) são preenchidos automaticamente com base no cadastro atual e ficam registrados no contrato, preservando as informações existentes no momento da assinatura.
                        </li>

                        <li>
                            Caso algum dado esteja incorreto, atualize primeiro o cadastro da empresa antes de gerar o contrato.
                        </li>

                        <li>
                            Após a geração do contrato, o sistema cria automaticamente <strong>13 cobranças</strong>, sendo:
                            <ul class="list-disc list-inside ml-5 mt-1 space-y-1">
                                <li><strong>1 cobrança de entrada</strong> com vencimento na data da assinatura.</li>
                                <li><strong>12 mensalidades</strong> utilizando o dia de vencimento selecionado.</li>
                            </ul>
                        </li>

                        <li>
                            O contrato é criado inicialmente com o status <strong>Pendente</strong> e permanecerá assim até a conclusão do processo de aprovação.
                        </li>

                        <li>
                            Confira atentamente a forma de pagamento e o dia de vencimento antes de confirmar, pois essas informações serão utilizadas para gerar todas as mensalidades do contrato.
                        </li>

                    </ul>

                </div>

            </div>

        </div>

        <div class="mb-8 bg-green-50 border-l-4 border-green-600 rounded-xl p-5">

            <div class="flex items-start gap-3">

                <svg class="w-6 h-6 text-green-600 mt-0.5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8c-1.657 0-3 1.343-3 3v1H8a2 2 0 0 0-2 2v3h12v-3a2 2 0 0 0-2-2h-1v-1c0-1.657-1.343-3-3-3z" />

                </svg>

                <div>

                    <h4 class="font-semibold text-green-800">
                        Dados para pagamento
                    </h4>

                    <p class="text-sm text-green-700 mt-2">
                        Forma de pagamento:
                        <strong>
                            PIX
                        </strong>
                    </p>

                    <p class="text-sm text-green-700 mt-1">

                        Chave PIX:
                        <strong>
                            {{ $empresaPS->emp_chave_pix ?? 'Não cadastrada' }}
                        </strong>

                    </p>

                    <p class="text-sm text-green-700 mt-1">

                        Recebedor:
                        <strong>
                            {{ $empresaPS->emp_nome_recebedor ?? '-' }}
                        </strong>

                    </p>

                    <p class="text-sm text-green-700 mt-1">

                        Cidade:
                        <strong>
                            {{ $empresaPS->emp_cidade ?? '-' }}
                        </strong>

                    </p>

                </div>

            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>

                <label class="text-sm font-medium text-gray-600">
                    Nome / Razão Social
                </label>

                <input
                    type="text"
                    name="cont_nome"
                    readonly
                    value="{{ old('cont_nome', $empresa->emp_nome) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100">

            </div>

            <div>

                <label class="text-sm font-medium text-gray-600">
                    Email
                </label>

                <input
                    type="email"
                    name="cont_email"
                    readonly
                    value="{{ old('cont_email', $empresa->emp_email_responsavel) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100">

            </div>

            <div>

                <label class="text-sm font-medium text-gray-600">
                    CPF / CNPJ
                </label>

                <input
                    type="text"
                    name="cont_cpf_cnpj"
                    readonly
                    value="{{ old('cont_cpf_cnpj', $empresa->emp_cpf) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100">

            </div>

            <div>

                <label class="text-sm font-medium text-gray-600">
                    Telefone
                </label>

                <input
                    type="text"
                    name="cont_telefone"
                    readonly
                    value="{{ old('cont_telefone', $empresa->emp_telefone_responsavel) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100">

            </div>

            <!-- CEP -->
            <div>
                <label class="text-sm font-medium text-gray-600">CEP</label>
                <input
                    type="text"
                    id="cont_cep"
                    name="cont_cep"
                    readonly
                    value="{{ old('cont_cep', $empresa->emp_end_cep) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100">
            </div>

            <!-- Número -->
            <div>
                <label class="text-sm font-medium text-gray-600">Número</label>
                <input
                    type="text"
                    name="cont_numero"
                    readonly
                    value="{{ old('cont_numero', $empresa->emp_end_numero) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100">
            </div>

            <!-- Endereço -->
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-600">Logradouro</label>
                <input
                    type="text"
                    name="cont_endereco"
                    readonly
                    value="{{ old('cont_endereco', $empresa->emp_end_logradouro) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100">
            </div>


            <!-- Complemento -->
            <div>
                <label class="text-sm font-medium text-gray-600">Complemento</label>
                <input
                    type="text"
                    name="cont_complemento"
                    readonly
                    value="{{ old('cont_complemento', $empresa->emp_end_complemento)}}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100">
            </div>

            <!-- Bairro -->
            <div>
                <label class="text-sm font-medium text-gray-600">Bairro</label>
                <input
                    type="text"
                    name="cont_bairro"
                    readonly
                    value="{{ old('cont_bairro', $empresa->emp_end_bairro) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100">
            </div>

            <!-- Cidade -->
            <div>
                <label class="text-sm font-medium text-gray-600">Cidade</label>
                <input
                    type="text"
                    name="cont_cidade"
                    readonly
                    value="{{ old('cont_cidade', $empresa->emp_end_cidade) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100">
            </div>

            <!-- Estado -->
            <div>
                <label class="text-sm font-medium text-gray-600">Estado</label>
                <input
                    type="text"
                    name="cont_estado"
                    readonly
                    value="{{ old('cont_estado', $empresa->emp_end_estado) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100">
            </div>
        </div>

        <!-- Forma de pagamento e vencimento -->
        <div class="border-t pt-6 mt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="text-sm text-gray-600">
                        Forma de Pagamento
                    </label>

                    <select
                        id="contrato_forma_pagamento"
                        name="contrato_forma_pagamento"
                        required
                        class="w-full border rounded-lg px-4 py-2 mt-1">

                        <option value="">Selecione...</option>

                        <option value="PIX"
                            {{ old('contrato_forma_pagamento') == 'PIX' ? 'selected' : '' }}>
                            PIX
                        </option>

                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-600">
                        Dia do Vencimento
                    </label>

                    <select
                        id="contrato_dia_vencimento"
                        name="contrato_dia_vencimento"
                        required
                        class="w-full border rounded-lg px-4 py-2 mt-1">

                        <option value="">Selecione...</option>

                        <option value="8"
                            {{ old('contrato_dia_vencimento') == 8 ? 'selected' : '' }}>
                            Dia 08
                        </option>

                        <option value="20"
                            {{ old('contrato_dia_vencimento') == 20 ? 'selected' : '' }}>
                            Dia 20
                        </option>

                        <option value="30"
                            {{ old('contrato_dia_vencimento') == 30 ? 'selected' : '' }}>
                            Dia 30
                        </option>

                    </select>

                    <!-- INFORMAÇÃO DA PRIMEIRA PARCELA -->
                    <div
                        id="infoPrimeiraParcela"
                        class="hidden mt-3 bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">

                        <div class="flex items-start gap-3">

                            <svg
                                class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z" />

                            </svg>

                            <div class="text-sm text-blue-800">

                                <p class="font-semibold">
                                    Primeira mensalidade
                                </p>

                                <p class="mt-1">
                                    A entrada será gerada na data da assinatura.
                                    A primeira mensalidade vencerá em
                                    <strong id="dataPrimeiraParcela"></strong>.
                                </p>

                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>


        <div class="mt-10">

            <label class="flex items-start gap-3">

                <input
                    type="checkbox"
                    required
                    name="cont_aceite"
                    value="1"
                    class="mt-1">

                <span class="text-sm text-gray-700">
                    Declaro que li e concordo com os termos do contrato de prestação
                    de serviços e estou ciente das condições do plano escolhido.
                </span>

            </label>

        </div>

        <div class="flex justify-end gap-4 border-t pt-6 mt-10">

            <a href="{{ route('admin.planos') }}"
                class="px-5 py-2 border rounded-lg hover:bg-gray-100">
                Cancelar
            </a>

            <button
                type="submit"
                id="btnGerarContrato"
                data-plano="{{ $plano->plano_nome }}"
                data-valor="{{ number_format($plano->plano_valor,2,',','.') }}"
                class="px-6 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">
                Gerar Contrato
        </div>
    </div>
</form>

<script>
    function bloquearSubmit(event, form) {

        if (!form.checkValidity()) {
            return; // deixa a validação normal do HTML
        }

        const plano = document.getElementById('btnGerarContrato').dataset.plano;
        const valor = document.getElementById('btnGerarContrato').dataset.valor;
        const pagamento = document.getElementById('contrato_forma_pagamento').value;

        const confirmar = confirm(
            `Confirma a geração do contrato?\n\n` +
            `Plano: ${plano}\n` +
            `Valor mensal: R$ ${valor}\n` +
            `Forma de pagamento: ${pagamento}\n\n` +
            `Deseja continuar?`
        );

        if (!confirmar) {
            event.preventDefault();
            return;
        }

        const btn = form.querySelector('button[type="submit"]');

        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Salvando...';
        }
    }

    document.getElementById('formContrato').addEventListener('submit', function(event) {
        bloquearSubmit(event, this);
    });

    function calcularPrimeiraParcela() {

        const select = document.getElementById('contrato_dia_vencimento');
        const info = document.getElementById('infoPrimeiraParcela');
        const dataElement = document.getElementById('dataPrimeiraParcela');

        const diaVencimento = parseInt(select.value);

        if (!diaVencimento) {
            info.classList.add('hidden');
            return;
        }

        // Data de hoje
        const hoje = new Date();

        const anoAtual = hoje.getFullYear();
        const mesAtual = hoje.getMonth();

        // Último dia do mês atual
        const ultimoDiaMesAtual = new Date(
            anoAtual,
            mesAtual + 1,
            0
        ).getDate();

        // Mesmo comportamento do Carbon:
        // min(dia escolhido, último dia do mês)
        let dia = Math.min(
            diaVencimento,
            ultimoDiaMesAtual
        );

        let primeiraData = new Date(
            anoAtual,
            mesAtual,
            dia
        );

        if (primeiraData <= hoje) {

            let proximoMes = mesAtual + 1;
            let proximoAno = anoAtual;

            if (proximoMes > 11) {
                proximoMes = 0;
                proximoAno++;
            }

            const ultimoDiaProximoMes = new Date(
                proximoAno,
                proximoMes + 1,
                0
            ).getDate();

            dia = Math.min(
                diaVencimento,
                ultimoDiaProximoMes
            );

            primeiraData = new Date(
                proximoAno,
                proximoMes,
                dia
            );
        }

        // Formatação brasileira
        const dataFormatada = primeiraData.toLocaleDateString(
            'pt-BR'
        );

        dataElement.textContent = dataFormatada;

        info.classList.remove('hidden');
    }

    document
        .getElementById('contrato_dia_vencimento')
        .addEventListener('change', calcularPrimeiraParcela);

    // Se houver valor antigo após erro de validação,
    // já mostra a informação automaticamente.
    document.addEventListener('DOMContentLoaded', function() {
        calcularPrimeiraParcela();
    });
</script>
@endsection