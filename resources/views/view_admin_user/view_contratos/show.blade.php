@extends('layouts.dashboard')

@section('title', 'Visualizar Contrato')

@section('content')

<x-alert-error />

<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex gap-2">
        <li><a href="{{ route('admin.planos') }}" class="hover:text-[#8E251F]">Planos</a></li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Contrato</li>
    </ol>
</nav>

<div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-8">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">
            Contrato #{{ $contrato->id_contrato }}
        </h2>
        <p class="text-gray-500 mt-2">
            Visualização completa dos dados do contrato.
        </p>
    </div>

    <a href="{{ route('admin.planos') }}" class="px-5 py-2 border rounded-lg hover:bg-gray-100">
        ← Voltar
    </a>
</div>

<!-- STATUS -->
<div class="bg-white rounded-2xl shadow-md border-l-8 border-[#8E251F] p-6 mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-xs uppercase tracking-widest text-gray-500">Status do contrato</p>
            <p class="text-2xl font-bold mt-1
                @if($contrato->contrato_status == 'Ativo') text-green-600
                @elseif($contrato->contrato_status == 'Pendente') text-yellow-600
                @else text-red-600 @endif">
                {{ $contrato->contrato_status }}
            </p>
        </div>

        <div>
            <p class="text-xs uppercase tracking-widest text-gray-500">Situação financeira</p>
            <p class="text-lg font-semibold mt-1">
                {{ $contrato->contrato_situacao_financeira }}
            </p>
        </div>

        <div>
            <p class="text-xs uppercase tracking-widest text-gray-500">Assinado em</p>
            <p class="text-lg font-semibold mt-1">
                {{ $contrato->contrato_assinado_em?->format('d/m/Y H:i:i') }}
            </p>
        </div>

        <div>
            <p class="text-xs uppercase tracking-widest text-gray-500">Dia de vencimento</p>
            <p class="text-lg font-semibold mt-1">
                Todo dia {{ $contrato->contrato_dia_vencimento }}
            </p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

    <!-- DADOS DA EMPRESA -->
    <div class="bg-white rounded-2xl shadow-md p-8">
        <h3 class="text-xl font-bold text-gray-700 mb-6">Dados da Empresa</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="text-sm text-gray-500">Empresa</label>
                <p class="font-semibold">{{ $contrato->contrato_empresa_nome }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Email</label>
                <p class="font-semibold">{{ $contrato->contrato_empresa_email }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Responsável</label>
                <p class="font-semibold">{{ $contrato->contrato_empresa_responsavel }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">CPF/CNPJ</label>
                <p class="font-semibold">{{ $contrato->contrato_empresa_cpf }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Telefone</label>
                <p class="font-semibold">{{ $contrato->contrato_empresa_telefone }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Tipo</label>
                <p class="font-semibold">{{ $contrato->contrato_empresa_tipo }}</p>
            </div>
        </div>
    </div>

    <!-- ENDEREÇO -->
    <div class="bg-white rounded-2xl shadow-md p-8">
        <h3 class="text-xl font-bold text-gray-700 mb-6">Endereço</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="text-sm text-gray-500">CEP</label>
                <p class="font-semibold">{{ $contrato->contrato_cep ?? '-' }}</p>
            </div>

            <div class="sm:col-span-2">
                <label class="text-sm text-gray-500">Logradouro</label>
                <p class="font-semibold">
                    {{ $contrato->contrato_endereco ?? '-' }}, {{ $contrato->contrato_numero ?? 's/n' }}
                    @if($contrato->contrato_complemento)
                    - {{ $contrato->contrato_complemento }}
                    @endif
                </p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Bairro</label>
                <p class="font-semibold">{{ $contrato->contrato_bairro ?? '-' }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Cidade / Estado</label>
                <p class="font-semibold">
                    {{ $contrato->contrato_cidade ?? '-' }} / {{ $contrato->contrato_estado ?? '-' }}
                </p>
            </div>
        </div>
    </div>

</div>

<!-- PAGAMENTO -->
<div class="bg-white rounded-2xl shadow-md p-8 mb-8">
    <h3 class="text-xl font-bold text-gray-700 mb-6">Dados de Pagamento</h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div>
            <label class="text-sm text-gray-500">Forma de pagamento</label>
            <p class="font-semibold">{{ $contrato->contrato_forma_pagamento }}</p>
        </div>

        <div>
            <label class="text-sm text-gray-500">Chave PIX</label>
            <p class="font-semibold">{{ $contrato->contrato_pix_chave ?? '-' }}</p>
        </div>

        <div>
            <label class="text-sm text-gray-500">Recebedor</label>
            <p class="font-semibold">{{ $contrato->contrato_pix_recebedor ?? '-' }}</p>
        </div>

        <div>
            <label class="text-sm text-gray-500">Cidade (PIX)</label>
            <p class="font-semibold">{{ $contrato->contrato_pix_cidade ?? '-' }}</p>
        </div>
    </div>
</div>

<!-- PLANO (SNAPSHOT) -->
<div class="bg-white rounded-2xl shadow-md p-8 mb-8">
    <h3 class="text-xl font-bold text-gray-700 mb-6">Plano Contratado</h3>

    @forelse($contrato->detalhes as $detalhe)
    <div class="border rounded-xl p-5 mb-4 @if(!$loop->last) opacity-60 @endif">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3">
            <div>
                <h4 class="text-lg font-bold">{{ $detalhe->det_plano_nome }}</h4>

                <p class="text-gray-600 mt-2">
                    Valor mensal:
                    <strong>R$ {{ number_format($detalhe->det_plano_valor,2,',','.') }}</strong>
                </p>

                <p class="text-gray-600 mt-1">
                    Quantidade: <strong>{{ $detalhe->det_plano_quantidade }}</strong>
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
                <span class="px-4 py-2 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                    Vigente
                </span>
                @else
                <span class="px-4 py-2 rounded-full bg-gray-100 text-gray-500 text-sm font-semibold">
                    Encerrado
                </span>
                @endif
            </div>
        </div>
    </div>
    @empty
    <p class="text-gray-500">Nenhum detalhe encontrado.</p>
    @endforelse
</div>

<!-- MENSALIDADES -->
<div class="bg-white rounded-2xl shadow-md p-8">
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
                        R$ {{ number_format($mensalidade->mensa_contrato_valor,2,',','.') }}
                    </td>

                    <td class="py-3 pr-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($mensalidade->mensa_contrato_status == 'Pago') bg-green-100 text-green-700
                                    @elseif($mensalidade->mensa_contrato_status == 'Pendente') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-700 @endif">
                            {{ $mensalidade->mensa_contrato_status }}
                        </span>
                    </td>

                    <td class="py-3 pr-4">
                        {{ $mensalidade->mensa_contrato_pagamento?->format('d/m/Y') ?? '-' }}
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

@endsection