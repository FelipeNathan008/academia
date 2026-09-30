@extends('layouts.dashboard')

@section('title', 'SuperAdmin')

@section('content')

<div class="flex justify-between items-center mb-10">

    <div>
        <h2 class="text-2xl font-bold text-gray-800">
            Visão Geral
        </h2>
        <p class="text-gray-500 mt-1">
            Acompanhamento geral da plataforma
        </p>
    </div>

</div>


{{-- RESUMO PRINCIPAL --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">


    <a href="{{ route('superadmin.empresas') }}"
        class="p-6 bg-white rounded-xl shadow-sm border-2 border-[#8E251F] relative overflow-hidden">
        <div class="absolute left-0 top-0 bottom-0 w-2 bg-[#8E251F]"></div>
        <div class="pl-2">
            <div class="flex items-center justify-between gap-3 mb-2">
                <h3 class="font-semibold text-lg text-gray-800">
                    Quantidade de Empresas / Clientes
                </h3>
            </div>
            <p class="text-3xl font-bold text-[#8E251F] mb-2">
                {{ $totalEmpresas }}
            </p>
            <span class="text-sm text-blue-600 underline hover:text-blue-800 font-medium">
                Ver todas Empresas clientes →
            </span>
        </div>
    </a>

    <div class="p-6 bg-white rounded-xl shadow-sm ">
        <h3 class="font-semibold text-lg mb-2">Total de Filiais / Clientes</h3>
        <p class="text-2xl font-bold mb-2">{{ $totalFiliais }}</p>
        <span class="text-sm text-gray-500">Filiais clientes do Sistema</span>
    </div>

    <a href="{{ route('superadmin.mensalidades.atrasadas') }}"
        class="block p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition">
        <h3 class="font-semibold text-lg mb-2">Mensalidades em atraso</h3>
        <p class="text-2xl font-bold mb-2">{{ $mensalidadesAtrasadas }}</p>
        <span class="text-sm text-blue-600 underline hover:text-blue-800 font-medium">
            Quantidade de Mensalidades em atraso →
        </span>
    </a>

    <a href="{{ route('superadmin-dashboard.comprovantes') }}"
        class="block p-6 rounded-xl shadow-sm
            {{ $comprovantesPendentes > 0 ? 'bg-red-50 border border-red-300' : 'bg-white' }}">

        <h3 class="font-semibold text-lg mb-2">
            Comprovantes Pendentes
        </h3>

        <p class="text-2xl font-bold
            {{ $comprovantesPendentes > 0 ? 'text-red-600' : '' }}">
            {{ $comprovantesPendentes }}
        </p>
        @if($comprovantesPendentes > 0)

        <span class="text-red-600 font-semibold">
            🔔 Aguardando aprovação →
        </span>
        @else
        <span class="text-gray-500">
            Nenhum comprovante pendente
        </span>
        @endif

    </a>
</div>

@endsection