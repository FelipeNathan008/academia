@extends('layouts.dashboard')

@section('title', 'Planos')

@section('content')
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Planos Disponíveis</h2>
        <p class="text-gray-500 mt-2">
            O plano ideal para a quantidade de matrículas da sua academia.
        </p>
    </div>

    <a href="{{ route('superadmin.planos') }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition self-start md:self-auto">
        ← Voltar
    </a>
</div>

@php
$check = '<svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
</svg>';
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
    @foreach ($planos as $plano)
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

            @if($plano->plano_tipo != 'financeiro')
            <div class="mb-5 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-700 font-medium">
                Até
                <strong>{{ $plano->plano_quantidade ?? 'Ilimitadas' }}</strong>
                matrículas
            </div>
            @else
            <div class="mb-5 bg-green-50 rounded-lg px-3 py-2 text-sm text-green-700 font-medium">
                Complemento (Pacote adicional)
            </div>
            @endif

            <hr class="mb-5">

            <ul class="space-y-3 text-sm text-gray-600 flex-1">
                @foreach ($plano->plano_desc as $recurso)
                <li class="flex items-start gap-2">
                    <span class="plano-check mt-0.5">{!! $check !!}</span>
                    <span>{{ $recurso }}</span>
                </li>
                @endforeach
            </ul>

            <button
                type="button"
                class="mt-6 w-full py-2.5 rounded-lg font-semibold text-sm bg-gray-100 text-gray-800 hover:bg-gray-200 transition">

                @if($plano->plano_tipo == 'financeiro')
                Adicionar pacote
                @else
                Escolher plano
                @endif

            </button>

        </div>
    </div>
    @endforeach

</div>

<script src="{{ asset('js/planos-cores.js') }}"></script>
@endsection