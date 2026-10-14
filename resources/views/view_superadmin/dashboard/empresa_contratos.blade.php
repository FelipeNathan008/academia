<!-- empresa_contratos.blade.php -->
@extends('layouts.dashboard')

@section('title', 'Contratos da Empresa')

@section('content')

<x-alert-error />

<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex gap-2">
        <li><a href="{{ route('dashboard-superadmin') }}" class="hover:text-[#8E251F]">Dashboard</a></li>
        <li>/</li>
        <li><a href="{{ route('superadmin.empresas') }}" class="hover:text-[#8E251F]">Empresas Clientes</a></li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Contratos</li>
    </ol>
</nav>

<div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-8">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">
            Contratos - {{ $empresa->emp_nome }}
        </h2>
        <p class="text-gray-500 mt-2">
            Visualização completa dos dados da empresa e seus contratos.
        </p>
    </div>
    <a href="{{ route('superadmin.empresas') }}" class="px-5 py-2 border rounded-lg hover:bg-gray-100">
        ← Voltar
    </a>
</div>

@if($contratos->isEmpty())

<div class="bg-white rounded-2xl shadow-md border-l-8 border-[#8E251F] p-6 mb-8">
    <p class="text-xs uppercase tracking-widest text-gray-500">Contratos</p>
    <p class="text-xl font-bold text-gray-700 mt-1">
        Esta empresa não possui contratos cadastrados.
    </p>
</div>
@endif

@if($contratos->isNotEmpty())
@foreach($contratos as $contrato)

<div class="mb-8">
    <div class="mb-4">
        <h3 class="text-xl font-bold text-gray-700">
            Contrato #{{ $contrato->id_contrato }}
        </h3>
    </div>

    <div class="bg-white rounded-2xl shadow-md border-l-8 border-[#8E251F] p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-widest text-gray-500">Status do contrato</p>
                <p class="text-2xl font-bold mt-1
            @if($contrato->contrato_status == 'Ativo') text-green-600
            @elseif($contrato->contrato_status == 'Pendente') text-yellow-600
            @else text-red-600 @endif">
                    {{ $contrato->contrato_status ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase tracking-widest text-gray-500 mb-2">Financeiro</p>
                <a href="#pagamentos-{{ $contrato->id_contrato }}"
                    class="inline-flex items-center px-5 py-2 bg-[#8E251F] text-white rounded-lg font-semibold hover:bg-[#732920] transition">
                    Pagamentos
                </a>
            </div>

            <div>
                <p class="text-xs uppercase tracking-widest text-gray-500">Assinado em</p>
                <p class="text-lg font-semibold mt-1">
                    {{ $contrato->contrato_assinado_em?->format('d/m/Y H:i:i') ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase tracking-widest text-gray-500">Dia de vencimento</p>
                <p class="text-lg font-semibold mt-1">
                    Todo dia {{ $contrato->contrato_dia_vencimento ?? '-' }}
                </p>
            </div>
        </div>
    </div>

</div>
@endforeach
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-white rounded-2xl shadow-md p-8">
        <h3 class="text-xl font-bold text-gray-700 mb-6">Dados da Empresa</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="text-sm text-gray-500">Empresa</label>
                <p class="font-semibold">{{ $empresa->emp_nome ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Email</label>
                <p class="font-semibold">{{ $empresa->emp_email_responsavel ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Responsável</label>
                <p class="font-semibold">{{ $empresa->emp_nome_responsavel ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">CPF/CNPJ</label>
                <p class="font-semibold">{{ $empresa->emp_cpf ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Telefone</label>
                <p class="font-semibold">{{ $empresa->emp_telefone_responsavel ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Tipo</label>
                <p class="font-semibold">{{ $empresa->emp_tipo ?? '-' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-8">
        <h3 class="text-xl font-bold text-gray-700 mb-6">Endereço</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="text-sm text-gray-500">CEP</label>
                <p class="font-semibold">{{ $empresa->emp_end_cep ?? '-' }}</p>
            </div>
            <div class="sm:col-span-2">
                <label class="text-sm text-gray-500">Logradouro</label>
                <p class="font-semibold">
                    {{ $empresa->emp_end_logradouro ?? '-' }},
                    {{ $empresa->emp_end_numero ?? 's/n' }}
                    @if($empresa->emp_end_complemento)
                    - {{ $empresa->emp_end_complemento }}
                    @endif
                </p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Bairro</label>
                <p class="font-semibold">{{ $empresa->emp_end_bairro ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Cidade / Estado</label>
                <p class="font-semibold">
                    {{ $empresa->emp_end_cidade ?? '-' }} / {{ $empresa->emp_end_estado ?? '-' }}
                </p>
            </div>
        </div>
    </div>

</div>

<div class="bg-white rounded-2xl shadow-md p-8 mb-8">
    <h3 class="text-xl font-bold text-gray-700 mb-6">Dados de Pagamento</h3>


    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div>
            <label class="text-sm text-gray-500">Forma de pagamento</label>
            <p class="font-semibold">{{ $contratos->first()?->contrato_forma_pagamento ?? '-' }}</p>
        </div>
        <div>
            <label class="text-sm text-gray-500">Chave PIX</label>
            <p class="font-semibold">{{ $empresa->emp_chave_pix ?? '-' }}</p>
        </div>
        <div>
            <label class="text-sm text-gray-500">Recebedor</label>
            <p class="font-semibold">{{ $empresa->emp_nome_recebedor ?? '-' }}</p>
        </div>
        <div>
            <label class="text-sm text-gray-500">Cidade (PIX)</label>
            <p class="font-semibold">{{ $empresa->emp_cidade ?? '-' }}</p>
        </div>
    </div>

</div>

@if($contratos->isNotEmpty())
@foreach($contratos as $contrato)

<div class="mb-10">
    <div class="bg-white rounded-2xl shadow-md p-8 mb-8">
        <h3 class="text-xl font-bold text-gray-700 mb-6">Plano Contratado</h3>

        @forelse($contrato->detalhes as $detalhe)
        <div class="border rounded-xl p-5 mb-4 @if(!$loop->last) opacity-60 @endif">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                <div>
                    <h4 class="text-lg font-bold">{{ $detalhe->det_plano_nome }}</h4>
                    <p class="text-gray-600 mt-2">
                        Valor mensal:
                        <strong>R$ {{ number_format($detalhe->det_plano_valor, 2, ',', '.') }}</strong>
                    </p>
                    <p class="text-gray-600 mt-1">
                        Quantidade:
                        <strong>
                            @if($detalhe->det_plano_tipo === 'financeiro')
                            Pacote adicional
                            @else
                            {{ $detalhe->det_plano_quantidade }}
                            @endif
                        </strong>
                    </p>
                    <p class="text-sm text-gray-400 mt-2">
                        Vigente desde {{ $detalhe->det_data_vigente?->format('d/m/Y') }}
                        @if($detalhe->det_data_fim)
                        até {{ $detalhe->det_data_fim->format('d/m/Y') }}
                        @endif
                    </p>
                </div>
                <div>
                    @if(is_null($detalhe->det_data_fim))
                    <span class="px-4 py-2 rounded-full bg-green-100 text-green-700 text-sm font-semibold">Vigente</span>
                    @else
                    <span class="px-4 py-2 rounded-full bg-gray-100 text-gray-500 text-sm font-semibold">Encerrado</span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <p class="text-gray-500">Nenhum detalhe encontrado.</p>
        @endforelse
    </div>

    <div id="pagamentos-{{ $contrato->id_contrato }}"
        class="bg-white rounded-2xl shadow-md p-8 scroll-mt-6">
        <h3 class="text-xl font-bold text-gray-700 mb-6">Mensalidades</h3>

        @php
        $mensalidades = $contrato->detalhes->flatMap->mensalidades->sortBy('mensa_contrato_vencimento');
        @endphp

        @if($mensalidades->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b text-gray-500">
                        <th class="py-3 pr-4">Referência</th>
                        <th class="py-3 pr-4">Vencimento</th>
                        <th class="py-3 pr-4">Valor</th>
                        <th class="py-3 pr-4">Status</th>
                        <th class="py-3 pr-4">Pagamento</th>
                        <th class="py-3 pr-4 text-center">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($mensalidades as $mensalidade)
                    <tr class="border-b last:border-0">

                        <td class="py-3 pr-4 font-medium">
                            {{ $mensalidade->mensa_contrato_referencia }}
                        </td>

                        <td class="py-3 pr-4">
                            {{ $mensalidade->mensa_contrato_vencimento?->format('d/m/Y') }}
                        </td>

                        <td class="py-3 pr-4 font-semibold text-green-600">
                            R$ {{ number_format($mensalidade->mensa_contrato_valor, 2, ',', '.') }}
                        </td>

                        <td class="py-3 pr-4">
                            @switch($mensalidade->mensa_contrato_status)

                            @case('Comprovante enviado')
                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg"
                                style="background-color:#2563eb;">
                                Comprovante enviado
                            </span>
                            @break

                            @case('Pago')
                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg"
                                style="background-color:#15803d;">
                                Pago
                            </span>
                            @break

                            @case('Atrasado')
                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg"
                                style="background-color:#dc2626;">
                                Atrasado
                            </span>
                            @break

                            @case('Rejeitado')
                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg"
                                style="background-color:#e71f1f;">
                                Rejeitado
                            </span>
                            @break

                            @case('Pendente')
                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg"
                                style="background-color:#ca8a04;">
                                Pendente
                            </span>
                            @break

                            @default
                            <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg bg-gray-400">
                                {{ $mensalidade->mensa_contrato_status ?? '-' }}
                            </span>

                            @endswitch
                        </td>

                        <td class="py-3 pr-4">
                            {{ $mensalidade->mensa_contrato_pagamento?->format('d/m/Y') ?? '-' }}
                        </td>

                        <td class="py-3 pr-4">
                            <div class="flex justify-center gap-2 flex-wrap">

                                @if($mensalidade->mensa_contrato_status === 'Comprovante enviado')

                                <a href="{{ route('superadmin-comprovante', Crypt::encrypt($mensalidade->id_mensalidade_contrato)) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    style="background-color:#174ab9;color:white;"
                                    class="px-4 py-2 rounded-lg shadow hover:bg-blue-800 transition">
                                    Comprovante
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('superadmin-comprovante.aprovar', Crypt::encrypt($mensalidade->id_mensalidade_contrato)) }}"
                                    onsubmit="return confirm('Deseja aprovar este comprovante?')">

                                    @csrf
                                    @method('PUT')

                                    <button
                                        type="submit"
                                        class="px-4 py-2 rounded-lg shadow text-white hover:bg-[#166534] transition"
                                        style="background-color:#15803d;">
                                        Aprovar
                                    </button>

                                </form>

                                <form
                                    method="POST"
                                    action="{{ route('superadmin-comprovante.rejeitar', Crypt::encrypt($mensalidade->id_mensalidade_contrato)) }}"
                                    onsubmit="return confirm('Deseja rejeitar este comprovante?')">

                                    @csrf
                                    @method('PUT')
                                    <button
                                        type="submit"
                                        class="px-4 py-2 rounded-lg shadow text-white hover:bg-[#b91c1c] transition"
                                        style="background-color:#dc2626;">
                                        Rejeitar
                                    </button>
                                </form>

                                @endif

                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-gray-500">Nenhuma mensalidade gerada.</p>
        @endif
    </div>

</div>
@endforeach
@endif

@endsection