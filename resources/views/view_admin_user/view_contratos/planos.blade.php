@extends('layouts.dashboard')

@section('title', 'Planos')

@section('content')
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">
            Planos Disponíveis
        </h2>

        <p class="text-gray-500 mt-2">
            O plano ideal para a quantidade de matrículas da sua academia.
        </p>
    </div>

</div>

@if($contrato)

<div class="mb-8">
    <div class="bg-white rounded-2xl shadow-md border-l-8 border-[#8E251F] p-6">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-5">
            <div>
                <p class="text-xs uppercase tracking-widest text-gray-500">
                    Contrato atual da empresa
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

                @if($contrato->detalheAtual)

                <div class="mt-3 space-y-1">

                    <p class="text-gray-700">
                        <span class="font-semibold">Plano:</span>
                        {{ $contrato->detalheAtual->det_plano_nome }}
                    </p>

                    <p class="text-gray-700">
                        <span class="font-semibold">Valor:</span>
                        R$ {{ number_format($contrato->detalheAtual->det_plano_valor,2,',','.') }}
                    </p>

                    <p class="text-gray-700">
                        <span class="font-semibold">Limite:</span>

                        @if($contrato->detalheAtual->det_plano_tipo == 'financeiro')
                        Pacote adicional
                        @else
                        {{ $contrato->detalheAtual->det_plano_quantidade }} matrículas
                        @endif
                    </p>

                </div>

                @endif

                <p class="text-sm text-gray-500 mt-1">
                    Criado em:
                    {{ $contrato->created_at?->format('d/m/Y') }}
                </p>

            </div>
            <a href="{{ route('contrato.show', Crypt::encrypt($contrato->id_contrato)) }}"
                class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">
                Ver contrato
            </a>

        </div>

    </div>
</div>

@endif

{{-- Componente de check reutilizável --}}
@php
$check = '<svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
</svg>';
@endphp
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

    @forelse ($planos as $plano)

    <div
        class="plano-card relative flex flex-col bg-white rounded-2xl border border-gray-200 shadow-md hover:shadow-xl overflow-hidden transition-all duration-300"
        data-plano="{{ $plano->plano_nome }}"
        data-plano-tipo="{{ $plano->plano_tipo }}">

        <div class="plano-header p-5">
            <h3 class="plano-titulo text-lg font-bold">
                {{ $plano->plano_nome }}
            </h3>
        </div>

        <div class="flex flex-col flex-1 p-6">

            <div class="mb-5">

                <p class="plano-preco text-4xl font-extrabold leading-none">

                    @if($plano->plano_tipo == 'financeiro')
                    + R$ {{ number_format($plano->plano_valor, 2, ',', '.') }}
                    @else
                    R$ {{ number_format($plano->plano_valor, 2, ',', '.') }}
                    @endif

                </p>

                <p class="text-gray-400 text-sm mt-1">

                    @if($plano->plano_tipo == 'financeiro')
                    adicional por mês
                    @else
                    por mês
                    @endif

                </p>

            </div>

            @if($plano->plano_tipo == 'financeiro')

            <div class="mb-5 bg-green-50 rounded-lg px-3 py-2 text-sm text-green-700 font-medium">
                Complemento (Pacote adicional)
            </div>

            @else

            <div class="mb-5 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-700 font-medium">
                Até <strong>{{ $plano->plano_quantidade }}</strong> matrículas
            </div>

            @endif

            <hr class="mb-5">

            <ul class="space-y-3 text-sm text-gray-600 flex-1">

                @foreach ($plano->plano_desc as $recurso)

                <li class="flex items-start gap-2">

                    <span class="plano-check mt-0.5">
                        {!! $check !!}
                    </span>

                    <span>{{ $recurso }}</span>

                </li>

                @endforeach

            </ul>
<!--
            @if($plano->plano_tipo == 'financeiro' && !$contrato)

            <button
                type="button"
                disabled
                class="mt-6 w-full py-2.5 rounded-lg font-semibold text-sm bg-gray-200 text-gray-500 cursor-not-allowed">
                Necessário contratar um plano primeiro
            </button>

            @else

            <a href="{{ route('contrato.create', Crypt::encrypt($plano->id_plano)) }}"
                class="mt-6 w-full py-2.5 rounded-lg font-semibold text-sm bg-gray-100 text-gray-800 hover:bg-gray-200 transition text-center">

                @if($plano->plano_tipo == 'financeiro')
                Adicionar pacote
                @else
                Escolher plano
                @endif

            </a>

            @endif
-->
        </div>

    </div>


    @empty

    <div class="col-span-full text-center py-10 text-gray-500">
        Nenhum plano encontrado.
    </div>

    @endforelse

</div>

<script src="{{ asset('js/planos-cores.js') }}"></script>
@endsection