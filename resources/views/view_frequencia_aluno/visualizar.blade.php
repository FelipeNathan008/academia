@extends('layouts.dashboard')

@section('title', 'Visualizar Frequência')

@section('content')

<!-- TOPO -->
<div class="flex justify-between items-center mb-8">
    <h2 class="text-3xl font-extrabold text-gray-800">
        Relatório de Frequência
    </h2>

    <a href="{{ route('frequencia.listagem') }}"
        class="px-4 py-2 border rounded-lg hover:bg-gray-100">
        ← Voltar
    </a>
</div>

@php
$totalPresencasGeral = 0;
$totalFaltasGeral = 0;
@endphp

@foreach($grade->matriculas as $matricula)
@php
$totalPresencasGeral += $matricula->frequencias->where('freq_presenca','Presente')->count();
$totalFaltasGeral += $matricula->frequencias->where('freq_presenca','Falta')->count();
@endphp
@endforeach

<!-- GRÁFICO -->
<div class="mb-6">
    <div class="mb-2 text-center">
        <h3 class="text-lg font-bold text-gray-700">
            Resumo Geral de Frequência
        </h3>
        <p class="text-sm text-gray-500">
            Distribuição total de presenças e faltas da turma
        </p>
    </div>

    <canvas
        id="graficoFrequencia"
        style="max-height: 250px;"
        data-presencas="{{ $totalPresencasGeral }}"
        data-faltas="{{ $totalFaltasGeral }}">
    </canvas>
</div>

<!-- CARD DA GRADE -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#174ab9] rounded-2xl shadow-lg p-6">
        <p class="text-xs uppercase tracking-widest text-gray-500">
            Grade selecionada
        </p>

        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
            {{ $grade->grade_modalidade }}
        </h3>

        <div class="mt-4 space-y-3 text-sm text-gray-600">

            <div>
                <span class="font-semibold text-gray-800">Professor:</span><br>
                {{ $grade->professor->prof_nome ?? '-' }}
            </div>

            <div>
                <span class="font-semibold text-gray-800">Horário:</span><br>
                {{ ucfirst($grade->grade_turma) }}
                <span class="text-xs text-gray-500 block">
                    {{ \Carbon\Carbon::parse($grade->grade_inicio)->format('H:i') }}
                    às
                    {{ \Carbon\Carbon::parse($grade->grade_fim)->format('H:i') }}
                </span>
            </div>

        </div>
    </div>
</div>
<!-- FILTROS -->
<div class="bg-white rounded-2xl shadow-md p-6 mb-8">

    <div class="flex justify-center">
        <div class="flex flex-wrap gap-6 items-end justify-center">

            <!-- Ordenação por Presença -->
            <div class="flex flex-col w-[250px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Ordenar por Presenças
                </label>
                <select id="filtroOrdenacao"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                       focus:ring-2 focus:ring-[#174ab9] focus:outline-none text-center">
                    <option value="">Padrão</option>
                    <option value="maior">Maior número de presenças</option>
                    <option value="menor">Menor número de presenças</option>
                </select>
            </div>

            <!-- Meta Concluída -->
            <div class="flex flex-col w-[200px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Meta
                </label>
                <select id="filtroMeta"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                       focus:ring-2 focus:ring-[#174ab9] focus:outline-none text-center">
                    <option value="">Todos</option>
                    <option value="100">Meta Concluída (100%)</option>
                </select>
            </div>

            <!-- Limpar -->
            <button id="limparFiltrosFrequencia"
                class="h-[48px] px-6 rounded-xl bg-gray-300
                   text-gray-800 font-semibold hover:bg-gray-400
                   transition shadow-md">
                Limpar filtros
            </button>

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
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @foreach($grade->matriculas as $matricula)

            @php
            $totalAulas = $matricula->frequencias->count();
            $presencas = $matricula->frequencias->where('freq_presenca', 'Presente')->count();
            $faltas = $matricula->frequencias->where('freq_presenca', 'Falta')->count();

            $percentual = $totalAulas > 0
            ? round(($presencas / $totalAulas) * 100)
            : 0;

            $detalhes = \App\Models\DetalhesAluno::where('aluno_id_aluno', $matricula->aluno->id_aluno)
            ->where('det_modalidade', $grade->grade_modalidade)
            ->ordenarPorFaixaInverso()
            ->orderByDesc('det_grau')
            ->first();

            $meta = 0;

            if ($detalhes) {
            $metaGraduacao = \App\Models\Graduacao::where('gradu_nome_cor', $detalhes->det_gradu_nome_cor)
            ->where('gradu_grau', $detalhes->det_grau)
            ->first();

            $meta = $metaGraduacao->gradu_meta ?? 0;
            }

            $barra = $meta > 0
            ? min(100, round(($presencas / $meta) * 100))
            : 0;
            @endphp

            <tr class="border-b hover:bg-gray-50 transition linha-aluno"
                data-presencas="{{ $presencas }}"
                data-barra="{{ $barra }}">
                <td class="py-3 px-4 font-semibold">
                    {{ $matricula->aluno->aluno_nome ?? '-' }}
                </td>

                <td class="py-3 px-4">{{ $totalAulas }}</td>

                <td class="py-3 px-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold"
                        style="background-color: #dcfce7; color: #166534;">
                        {{ $presencas }}
                    </span>
                </td>

                <td class="py-3 px-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold"
                        style="background-color: #fee2e2; color: #991b1b;">
                        {{ $faltas }}
                    </span>
                </td>

                <td class="py-3 px-4 font-semibold">
                    {{ $percentual }}%
                </td>

                <!-- META -->
                <td class="py-3 px-4">

                    <div class="mb-2">
                        @if($barra >= 100)
                        <span class="px-2 py-1 rounded-full text-xs font-semibold"
                            style="background-color: #dcfce7; color: #166534;">
                            {{ $presencas }}/{{ $meta }} ({{ $barra }}%)
                        </span>
                        @else
                        <span class="px-2 py-1 rounded-full text-xs font-semibold"
                            style="background-color: #fef9c3; color: #854d0e;">
                            {{ $presencas }}/{{ $meta }} ({{ $barra }}%)
                        </span>
                        @endif
                    </div>

                    <div
                        class="w-full bg-gray-200 rounded-full h-3 progress-container"
                        data-barra="{{ $barra }}">
                        <div class="h-3 rounded-full transition-all duration-500 progress-bar"></div>
                    </div>

                </td>

                <td class="py-3 px-4 text-center">
                    @if($matricula->aluno)
                    <a href="{{ route('detalhes-aluno.index', $matricula->aluno->id_aluno) }}"
                        class="px-4 py-2 rounded-lg shadow text-white"
                        style="background-color: #174ab9;">
                        Graduações
                    </a>
                    @endif
                </td>

            </tr>

            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const filtroOrdenacao = document.getElementById('filtroOrdenacao');
    const filtroMeta = document.getElementById('filtroMeta');
    const limparBtnFreq = document.getElementById('limparFiltrosFrequencia');
    const tbody = document.querySelector('tbody');
    const linhasAlunos = document.querySelectorAll('.linha-aluno');

    function aplicarFiltroFrequencia() {

        const ordenacao = filtroOrdenacao.value;
        const meta = filtroMeta.value;

        let linhasArray = Array.from(linhasAlunos);

        // FILTRO META 100%
        linhasArray.forEach(linha => {
            const barra = parseInt(linha.dataset.barra);

            let mostrar = true;

            if (meta === "100" && barra < 100) {
                mostrar = false;
            }

            linha.style.display = mostrar ? '' : 'none';
        });

        // ORDENAÇÃO
        if (ordenacao) {

            linhasArray.sort((a, b) => {
                const presA = parseInt(a.dataset.presencas);
                const presB = parseInt(b.dataset.presencas);

                return ordenacao === "maior" ?
                    presB - presA :
                    presA - presB;
            });

            linhasArray.forEach(linha => tbody.appendChild(linha));
        }
    }

    if (filtroOrdenacao && filtroMeta) {

        filtroOrdenacao.addEventListener('change', aplicarFiltroFrequencia);
        filtroMeta.addEventListener('change', aplicarFiltroFrequencia);

        limparBtnFreq.addEventListener('click', function() {
            filtroOrdenacao.value = '';
            filtroMeta.value = '';
            aplicarFiltroFrequencia();
        });
    }

    document.addEventListener("DOMContentLoaded", function() {

        // GRÁFICO
        const canvas = document.getElementById('graficoFrequencia');

        if (canvas) {
            new Chart(canvas, {
                type: 'pie',
                data: {
                    labels: ['Presenças', 'Faltas'],
                    datasets: [{
                        data: [
                            canvas.dataset.presencas,
                            canvas.dataset.faltas
                        ],
                        backgroundColor: ['#16a34a', '#dc2626']
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
        }

        // BARRAS DE PROGRESSO
        document.querySelectorAll(".progress-container").forEach(function(container) {

            const barra = container.dataset.barra;
            const bar = container.querySelector(".progress-bar");
            const text = container.parentElement.querySelector(".progress-text");

            bar.style.width = barra + "%";

            if (barra >= 100) {
                bar.style.backgroundColor = "#15803d";
                text.classList.add("text-green-700");
            } else {
                bar.style.backgroundColor = "#facc15";
                text.classList.add("text-yellow-700");
            }

        });

    });
</script>

@endsection