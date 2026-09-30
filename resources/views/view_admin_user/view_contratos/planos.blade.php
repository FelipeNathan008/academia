@extends('layouts.dashboard')

@section('title', 'Planos')

@section('content')
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">
            Planos Disponíveis
        </h2>

        <p class="text-gray-500 mt-2">
            O plano ideal para a quantidade de matrículas da sua academia.
        </p>
    </div>
</div>

<div class="bg-[#8E251F]/5 border border-[#8E251F]/20 rounded-xl px-5 py-4 mb-10">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">

        <div>
            <p class="text-sm text-gray-500">
                Matrículas atuais da academia
            </p>

            <p class="text-2xl font-bold text-[#8E251F]">
                {{ $contagemMatriculas }}
            </p>
        </div>

        <div class="text-sm text-gray-500">
            Considerando matrículas
            <strong>Matriculadas</strong> e
            <strong>Pausadas</strong>
        </div>

    </div>
</div>
@if($contrato)

<div class="bg-white rounded-2xl shadow-md border-l-8 border-[#8E251F] p-6 mb-10">

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-5">

        <div>

            <p class="text-xs uppercase tracking-widest text-gray-500">
                Plano Atual
            </p>

            <p class="text-gray-600 mt-2">
                Status:

                <span class="font-semibold
                @if($contrato->contrato_status == 'Ativo')
                    text-green-600
                @elseif($contrato->contrato_status == 'Pendente')
                    text-yellow-600
                @elseif($contrato->contrato_status == 'Suspenso')
                    text-orange-600
                @else
                    text-red-600
                @endif">

                    {{ $contrato->contrato_status }}

                </span>

            </p>

            @if($contrato->detalheAtual)

            <div class="mt-3 space-y-1">

                <p>
                    <strong>Plano:</strong>
                    {{ $contrato->detalheAtual->det_plano_nome }}
                </p>

                <p>
                    <strong>Valor:</strong>
                    R$ {{ number_format($contrato->detalheAtual->det_plano_valor,2,',','.') }}
                </p>

                <p>
                    <strong>Limite:</strong>
                    @if($contrato->detalheAtual->det_plano_tipo == 'financeiro')
                    Pacote adicional
                    @else
                    {{ $contrato->detalheAtual->det_plano_quantidade }}
                    matrículas
                    @endif
                </p>
            </div>
            @endif

            <p class="text-sm text-gray-500 mt-3">
                Contratado em:
                {{ $contrato->created_at->format('d/m/Y') }}
            </p>
        </div>

        @if($alertaFinanceiro)

        <div class="flex-1 flex justify-center">

            <div class="rounded-xl px-5 py-4 max-w-sm w-full
        @switch($alertaFinanceiro['cor'])
            @case('red')
                bg-red-50 border border-red-200
            @break

            @case('blue')
                bg-blue-50 border border-blue-200
            @break

            @case('yellow')
                bg-yellow-50 border border-yellow-200
            @break
        @endswitch">

                <p class="text-xs uppercase tracking-widest text-gray-500 mb-1">
                    Financeiro
                </p>

                <p class="font-semibold
            @switch($alertaFinanceiro['cor'])
                @case('red')
                    text-red-700
                @break

                @case('blue')
                    text-blue-700
                @break

                @case('yellow')
                    text-yellow-700
                @break
            @endswitch">

                    {{ $alertaFinanceiro['texto'] }}
                </p>
            </div>
        </div>

        @endif
        <div class="flex flex-col sm:flex-row gap-3">

            <a href="{{ route('contrato.show', Crypt::encrypt($contrato->id_contrato)) }}"
                class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920] text-center">
                Ver contrato
            </a>

            <a href="{{ route('contrato.show', Crypt::encrypt($contrato->id_contrato)) }}#pagamentos"
                class="px-5 py-2 border border-[#8E251F] text-[#8E251F] rounded-lg hover:bg-[#8E251F] hover:text-white transition text-center">
                Ver pagamentos
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

@php
$planoAtual = $contrato?->detalheAtual;
$contratoAtrasado = $contrato?->possuiMensalidadeAtrasada() ?? false;
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
            @php
            $planoLotado = $plano->plano_tipo !== 'financeiro'
            && $contagemMatriculas > $plano->plano_quantidade;

            $planoPodeAlterar = $plano->plano_tipo !== 'financeiro'
            && !$planoLotado;

            $planoAtualSelecionado = $contrato
            && $planoAtual
            && $plano->id_plano == $planoAtual->plano_id;
            @endphp
            @if($plano->plano_tipo == 'financeiro')

            <div class="mb-5 bg-green-50 rounded-lg px-3 py-2 text-sm text-green-700 font-medium">
                Complemento (Pacote adicional)
            </div>

            @else

            {{-- LIMITE DO PLANO --}}
            <div class="mb-5 rounded-lg px-3 py-2 text-sm
        {{ $planoLotado
            ? 'bg-red-50 text-red-700 border border-red-200'
            : 'bg-gray-50 text-gray-700' }}">

                <strong>Até</strong>

                <strong>
                    {{ $plano->plano_quantidade ?? 'Ilimitadas' }}
                </strong>

                matrículas

            </div>

            {{-- STATUS PARA ALTERAÇÃO --}}
            @if($contrato && $planoAtual)

            @if($planoAtualSelecionado)

            <div class="mb-5 text-sm text-[#8E251F] font-bold">
                ✓ Plano atual
            </div>

            @elseif($planoPodeAlterar)

            <div class="mb-5 text-sm text-green-600 font-bold">
                ✓ Disponível para alteração
            </div>

            @else

            <div class="mb-5 text-sm text-red-600 font-bold">
                Limite insuficiente para suas matrículas
            </div>

            @endif

            @endif

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
            @if($plano->plano_tipo == 'financeiro' && !$contrato)

            <button
                type="button"
                disabled
                class="mt-6 w-full py-2.5 rounded-lg font-semibold text-sm bg-gray-200 text-gray-500 cursor-not-allowed">
                Necessário contratar um plano primeiro
            </button>

            @elseif($contrato && $plano->plano_tipo !== 'financeiro' && $plano->id_plano == $planoAtual?->plano_id)

            <button
                type="button"
                disabled
                class="mt-6 w-full py-2.5 rounded-lg font-semibold text-sm bg-gray-200 text-gray-500 cursor-not-allowed">
                Plano atual
            </button>

            @elseif($contrato && $plano->plano_tipo !== 'financeiro' && $contratoAtrasado)

            <button
                type="button"
                disabled
                class="mt-6 w-full py-2.5 rounded-lg font-semibold text-sm bg-red-100 text-red-600 cursor-not-allowed">
                Contrato em atraso
            </button>

            @elseif($plano->plano_tipo !== 'financeiro' && $planoLotado)

            <button
                type="button"
                disabled
                class="mt-6 w-full py-2.5 rounded-lg font-semibold text-sm bg-red-100 text-red-600 cursor-not-allowed">
                Limite de matrículas insuficiente
            </button>

            @elseif($contrato && $plano->plano_tipo !== 'financeiro' && !$planoPodeAlterar)

            <button
                type="button"
                disabled
                class="mt-6 w-full py-2.5 rounded-lg font-semibold text-sm bg-red-100 text-red-600 cursor-not-allowed">
                Não comporta suas matrículas
            </button>

            @else

            <a href="{{ $contrato && $plano->plano_tipo !== 'financeiro'
        ? route('contrato.alterar', Crypt::encrypt($plano->id_plano))
        : route('contrato.create', Crypt::encrypt($plano->id_plano)) }}"
                class="mt-6 w-full py-2.5 rounded-lg font-semibold text-sm bg-gray-100 text-gray-800 hover:bg-gray-200 transition text-center">

                @if($plano->plano_tipo == 'financeiro')
                Adicionar pacote
                @elseif($contrato)
                Alterar para este plano
                @else
                Escolher plano
                @endif

            </a>

            @endif

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