@extends('layouts.dashboard')

@section('title', 'Dashboard Professor')

@section('content')

<h2 class="text-2xl font-bold mb-6">Visão Geral</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

    <!-- TOTAL ALUNOS -->
    <div class="p-6 bg-white rounded-xl shadow-sm">
        <h3 class="font-semibold text-lg mb-2">
            Total de Alunos
        </h3>

        <p class="text-2xl font-bold mb-2">
            {{ $totalAlunos }}
        </p>

        <span class="text-sm text-gray-500">
            Alunos vinculados ao professor
        </span>
    </div>

    <!-- BOLSISTAS -->
    <div class="p-6 bg-white rounded-xl shadow-sm">
        <h3 class="font-semibold text-lg mb-2">
            Alunos Bolsistas
        </h3>

        <p class="text-2xl font-bold mb-2">
            {{ $totalBolsistas }}
        </p>

        <span class="text-sm text-gray-500">
            Total de bolsistas
        </span>
    </div>

    <!-- RECEITA -->
    <div class="p-6 bg-white rounded-xl shadow-sm">
        <h3 class="font-semibold text-lg mb-2">
            Receita Mensal (Pago/Total)
        </h3>

        <p class="text-2xl font-bold mb-2">
            <span class="text-green-600">
                {{ 'R$ ' . number_format($receitaMensalPago, 2, ',', '.') }}
            </span>

            <span class="text-gray-500"> / </span>

            <span class="text-gray-800">
                {{ 'R$ ' . number_format($receitaMensal, 2, ',', '.') }}
            </span>
        </p>

        <span class="text-sm text-gray-500">
            Mês atual
        </span>
    </div>


    <div class="p-6 bg-white rounded-xl shadow-sm">
        <h3 class="font-semibold text-lg mb-2">
            Mensalidades Atrasadas
        </h3>

        <p class="text-2xl font-bold mb-2">
            {{ $mensalidadesAtrasadas }}
        </p>

        <span class="text-sm text-gray-500">
            Total Geral
        </span>
    </div>

</div>

@endsection