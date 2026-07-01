@extends('layouts.dashboard')

@section('title', 'Dashboard Aluno')

@section('content')
@php
use Illuminate\Support\Facades\Crypt;
@endphp
<h2 class="text-2xl font-bold mb-6">
    Visão Geral
</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

    <!-- TOTAL DE ALUNOS -->
    <div class="p-6 bg-white rounded-xl shadow-sm">
        <h3 class="font-semibold text-lg mb-2">
            Total de Alunos
        </h3>

        <p class="text-2xl font-bold mb-2">
            {{ $totalAlunos }}
        </p>

        <span class="text-sm text-gray-500">
            Alunos vinculados ao responsável
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
            Mensalidades (Pago/Total)
        </h3>

        <p class="text-2xl font-bold mb-2">

            <span class="text-green-600">
                {{ 'R$ ' . number_format($receitaMensalPago, 2, ',', '.') }}
            </span>

            <span class="text-gray-500">
                /
            </span>

            <span class="text-gray-800">
                {{ 'R$ ' . number_format($receitaMensal, 2, ',', '.') }}
            </span>

        </p>

        <span class="text-sm text-gray-500">
            Mês atual
        </span>
    </div>

    <a href="{{ route('dashboard-aluno.mensalidadesAtrasadas') }}"
        class="block p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition">

        <h3 class="font-semibold text-lg mb-2">
            Mensalidades Atrasadas
        </h3>

        <p class="text-2xl font-bold mb-2">
            {{ $mensalidadesAtrasadas }}
        </p>

        <span class="text-sm text-blue-600 underline hover:text-blue-800 font-medium">
            Ver mensalidades atrasadas →
        </span>
    </a>

</div>
<!-- CARD DE FREQUÊNCIA -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

        <div>
            <h3 class="text-2xl font-bold text-gray-800">
                Frequência do Aluno
            </h3>

            <p class="text-sm text-gray-500 mt-1">
                Acompanhe a presença e faltas dos alunos.
            </p>
        </div>

        <form method="GET">

            <div class="flex flex-col md:flex-row gap-4">

                <!-- ALUNO -->
                <select
                    name="aluno_id"
                    id="aluno_id"
                    onchange="this.form.submit()"
                    class="border border-gray-300 rounded-xl px-4 py-3 min-w-[280px]">

                    <option value="">Selecione um aluno</option>

                    @foreach($alunos as $aluno)
                    <option
                        value="{{ encrypt($aluno->id_aluno) }}"
                        {{ $alunoSelecionado == $aluno->id_aluno ? 'selected' : '' }}>
                        {{ $aluno->aluno_nome }}
                    </option>
                    @endforeach

                </select>

                <!-- MATRÍCULA -->
                @if($alunoSelecionado)
                <select
                    name="matricula_id"
                    onchange="this.form.submit()"
                    class="border border-gray-300 rounded-xl px-4 py-3 min-w-[280px]">

                    <option value="">Selecione uma matrícula</option>

                    @foreach($matriculas as $matricula)
                    <option
                        value="{{ encrypt($matricula->id_matricula) }}"
                        {{ $matriculaSelecionada == $matricula->id_matricula ? 'selected' : '' }}>
                        {{ $matricula->grade->grade_modalidade }}
                        -
                        {{ $matricula->grade->grade_dia_semana }}
                        -
                        {{ \Carbon\Carbon::parse($matricula->grade->grade_inicio)->format('H:i') }}
                        às
                        {{ \Carbon\Carbon::parse($matricula->grade->grade_fim)->format('H:i') }}
                    </option>
                    @endforeach

                </select>
                @endif
            </div>

        </form>

    </div>

    @if($matriculaSelecionada)

    <div class="grid md:grid-cols-2 gap-8 items-center">

        <!-- GRÁFICO -->
        <div class="mx-auto" style="width:250px; height:250px;">
            <canvas
                id="graficoFrequencia"
                data-presencas="{{ $presencas }}"
                data-faltas="{{ $faltas }}">
            </canvas>
        </div>

        <!-- RESUMO -->
        <div>

            <div class="mb-6">
                <p class="text-sm text-gray-500">
                    Taxa de presença
                </p>
                <h4 class="text-5xl font-extrabold text-green-600">
                    {{ $percentualPresenca }}%
                </h4>
            </div>

            <div class="space-y-4">

                <div class="flex justify-between items-center p-4 rounded-xl bg-green-50 border border-green-200">
                    <span class="font-medium text-gray-700">
                        Presenças
                    </span>
                    <span class="text-xl font-bold text-green-600">
                        {{ $presencas }}
                    </span>
                </div>
                <div class="flex justify-between items-center p-4 rounded-xl bg-red-50 border border-red-200">
                    <span class="font-medium text-gray-700">
                        Faltas
                    </span>

                    <span class="text-xl font-bold text-red-600">
                        {{ $faltas }}
                    </span>
                </div>
                @if($matriculaSelecionada)

                <div class="p-4 rounded-xl bg-yellow-50 border border-yellow-200">

                    <div class="flex justify-between items-center mb-2">

                        <span class="font-medium text-gray-700">
                            Meta de Graduação
                        </span>

                        <span
                            class="font-bold meta-texto"
                            data-barra="{{ $barraMeta }}">
                            {{ $presencas }}/{{ $meta }}
                            ({{ $barraMeta }}%)
                        </span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-3">

                        <div
                            class="w-full bg-gray-200 rounded-full h-3 progress-meta"
                            data-barra="{{ $barraMeta }}">

                            <div
                                class="h-3 rounded-full transition-all duration-500 progress-meta-bar">
                            </div>

                        </div>

                    </div>

                </div>

                @endif
            </div>
        </div>
    </div>

    @else

    <div class="py-16 text-center">

        <h4 class="text-xl font-semibold text-gray-700 mb-2">
            Selecione uma matrícula
        </h4>

    </div>

    @endif

</div>


@if($matriculaSelecionada)

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const canvas = document.getElementById('graficoFrequencia');

        if (!canvas) return;

        const presencas = parseInt(canvas.dataset.presencas);
        const faltas = parseInt(canvas.dataset.faltas);

        new Chart(canvas, {
            type: 'pie',
            data: {
                labels: ['Presenças', 'Faltas'],
                datasets: [{
                    data: [presencas, faltas],
                    backgroundColor: ['#22c55e', '#ef4444']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        document.querySelectorAll(".progress-meta").forEach(function(container) {
            const barra = parseInt(container.dataset.barra);
            const progressBar = container.querySelector(".progress-meta-bar");
            progressBar.style.width = barra + "%";
            progressBar.classList.add(barra >= 100 ? "bg-green-600" : "bg-yellow-500");
        });

        document.querySelectorAll(".meta-texto").forEach(function(element) {
            const barra = parseInt(element.dataset.barra);
            element.style.color = barra >= 100 ? "#16a34a" : "#a16207";
        });

    });
</script>

@endif

@endsection