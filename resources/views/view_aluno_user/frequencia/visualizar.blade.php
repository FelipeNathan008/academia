@extends('layouts.dashboard')

@section('title', 'Relatório de Frequência')

@section('content')

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2 flex-wrap">
        <li>
            <a href="{{ route('aluno.index') }}" class="hover:text-[#8E251F] transition">
                Alunos
            </a>
        </li>
        <li>/</li>
        <li>
            <a href="{{ route('aluno.show', Crypt::encrypt($matricula->aluno->id_aluno)) }}"
                class="hover:text-[#8E251F] transition">
                {{ $matricula->aluno->aluno_nome }}
            </a>
        </li>
        <li>/</li>
        <li class="font-semibold text-gray-700">
            Frequência
        </li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('aluno.index') }}"
            class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>

        <h2 class="text-3xl font-extrabold text-gray-800">
            Relatório da Frequência
        </h2>
    </div>

    <!-- GRÁFICO -->
    <div class="mb-6 bg-white rounded-2xl shadow-md p-6 w-full md:w-[900px] mx-auto">

        <form method="GET" class="mb-4 flex justify-center">
            <div class="flex flex-col w-[220px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Selecione Ano
                </label>

                <select name="ano"
                    onchange="this.form.submit()"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                   focus:ring-2 focus:ring-[#174ab9] focus:outline-none text-center">

                    @foreach($anosDisponiveis as $ano)
                    <option value="{{ $ano }}"
                        {{ $ano == $anoSelecionado ? 'selected' : '' }}>
                        {{ $ano }}
                    </option>
                    @endforeach

                </select>
            </div>
        </form>

        <div class="mb-4 text-center">
            <h3 class="text-lg font-bold text-gray-700">
                Frequência Mensal
            </h3>

            <p class="text-sm text-gray-500">
                Comparativo mensal de presenças e faltas
            </p>
        </div>

        <div class="w-full h-[450px]">
            <canvas id="graficoBarras"></canvas>
        </div>
    </div>
</div>

<!-- CARD -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#174ab9] rounded-2xl shadow-lg p-6">
        <p class="text-xs uppercase tracking-widest text-gray-500">
            Aluno selecionado
        </p>

        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
            {{ $matricula->aluno->aluno_nome }}
        </h3>

        <div class="mt-4 space-y-3 text-sm text-gray-600">
            <div>
                <span class="font-semibold text-gray-800">Professor:</span><br>
                {{ $grade->professor->prof_nome ?? '-' }}
            </div>

            <div>
                <span class="font-semibold text-gray-800">Turma / Horário:</span><br>
                {{ ucfirst($grade->grade_turma) }}
                <span class="text-xs text-gray-500 block">
                    {{ \Carbon\Carbon::parse($grade->grade_inicio)->format('H:i') }}
                    às
                    {{ \Carbon\Carbon::parse($grade->grade_fim)->format('H:i') }}
                </span>
            </div>

            <div>
                <span class="font-semibold text-gray-800">Modalidade:</span><br>
                {{ $grade->grade_modalidade ?? '-' }}
            </div>
        </div>
    </div>
</div>

<!-- TABELA -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Aluno</th>
                <th class="py-3 px-4">Total Aulas</th>
                <th class="py-3 px-4">Presenças</th>
                <th class="py-3 px-4">Faltas</th>
                <th class="py-3 px-4">Presença (%)</th>
                <th class="py-3 px-4">Meta</th>
            </tr>
        </thead>

        <tbody>
            <tr class="border-b hover:bg-gray-50 transition">
                <td class="py-3 px-4 font-semibold">
                    {{ $matricula->aluno->aluno_nome ?? '-' }}
                </td>

                <td class="py-3 px-4">
                    {{ $totalAulas }}
                </td>

                <td class="py-3 px-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold"
                        style="background-color: #dcfce7; color: #166534;">
                        {{ $totalPresencasGeral }}
                    </span>
                </td>

                <td class="py-3 px-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold"
                        style="background-color: #fee2e2; color: #991b1b;">
                        {{ $totalFaltasGeral }}
                    </span>
                </td>

                <td class="py-3 px-4 font-semibold">
                    {{ $percentual }}%
                </td>

                <td class="py-3 px-4">
                    <div class="mb-2">
                        @if($barra >= 100)
                        <span class="px-2 py-1 rounded-full text-xs font-semibold"
                            style="background-color: #dcfce7; color: #166534;">
                            {{ $totalPresencasGeral }}/{{ $meta }} ({{ $barra }}%)
                        </span>
                        @else
                        <span class="px-2 py-1 rounded-full text-xs font-semibold"
                            style="background-color: #fef9c3; color: #854d0e;">
                            {{ $totalPresencasGeral }}/{{ $meta }} ({{ $barra }}%)
                        </span>
                        @endif
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-3 progress-container"
                        data-barra="{{ $barra }}">
                        <div class="h-3 rounded-full transition-all duration-500 progress-bar"></div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // =========================
        // DADOS DO GRÁFICO
        // =========================

        const dadosBackend = @json($frequenciaMensal);

        const nomesMeses = [
            'Jan', 'Fev', 'Mar', 'Abr',
            'Mai', 'Jun', 'Jul', 'Ago',
            'Set', 'Out', 'Nov', 'Dez'
        ];

        const mesesMap = {};
        const presencas = {};
        const faltas = {};

        dadosBackend.forEach(item => {

            const nomeMes = nomesMeses[item.mes - 1];

            mesesMap[nomeMes] = true;

            if (!presencas[nomeMes]) {
                presencas[nomeMes] = 0;
            }

            if (!faltas[nomeMes]) {
                faltas[nomeMes] = 0;
            }

            if (item.freq_presenca === 'Presente') {
                presencas[nomeMes] = item.total;
            }

            if (item.freq_presenca === 'Falta') {
                faltas[nomeMes] = item.total;
            }

        });

        const labels = Object.keys(mesesMap);

        const dadosPresenca = labels.map(mes => presencas[mes] || 0);
        const dadosFalta = labels.map(mes => faltas[mes] || 0);

        // =========================
        // GRÁFICO
        // =========================

        const canvas = document.getElementById('graficoBarras');

        if (canvas) {

            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Presenças',
                            data: dadosPresenca,
                            backgroundColor: '#16a34a'
                        },
                        {
                            label: 'Faltas',
                            data: dadosFalta,
                            backgroundColor: '#dc2626'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });

        }

        // =========================
        // BARRA DE PROGRESSO
        // =========================

        document.querySelectorAll(".progress-container").forEach(function(container) {

            const barra = parseInt(container.dataset.barra);
            const bar = container.querySelector(".progress-bar");

            bar.style.width = barra + "%";

            bar.style.backgroundColor = barra >= 100 ? "#15803d" : "#facc15";

        });

    });
</script>

@endsection