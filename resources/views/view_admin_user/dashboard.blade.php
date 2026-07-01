@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')


<div class="flex justify-between items-center mb-10">
    <h2 class="text-2xl font-bold mb-6">Visão Geral</h2>

    <a href="{{ route('admin.principal') }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
        ← Voltar
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

    <div class="p-6 bg-white rounded-xl shadow-sm">
        <h3 class="font-semibold text-lg mb-2">Total de Alunos Matriculados</h3>
        <p class="text-2xl font-bold mb-2">{{ $totalAlunosMatriculados }}</p>
        <span class="text-sm text-gray-500">Total Geral</span>
    </div>

    <a href="{{ route('matricula.index') }}"
        class="block p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition">

        <h3 class="font-semibold text-lg mb-2">
            Total de Alunos Não Matriculados
        </h3>

        <p class="text-2xl font-bold mb-2">
            {{ $totalAlunosNaoMatriculados }}
        </p>

        <span class="text-sm text-blue-600 underline hover:text-blue-800 font-medium">
            Ver todos os Alunos →
        </span>
    </a>

    <div class="p-6 bg-white rounded-xl shadow-sm">
        <h3 class="font-semibold text-lg mb-2">Matrículas Ativas</h3>
        <p class="text-2xl font-bold mb-2">{{ $totalMatriculasAtivas }}</p>
        <span class="text-sm text-gray-500">Total de Matrículas Cadastradas</span>
    </div>

    <a href="{{ route('matricula.index') }}"
        class="block p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition">

        <h3 class="font-semibold text-lg mb-2">
            Total de Alunos Bolsistas
        </h3>

        <p class="text-2xl font-bold mb-2">
            {{ $totalBolsista }}
        </p>

        <span class="text-sm text-blue-600 underline hover:text-blue-800 font-medium">
            Ver todos os Alunos →
        </span>
    </a>
    <div class="p-6 bg-white rounded-xl shadow-sm">
        <h3 class="font-semibold text-lg mb-2">Receita Mensal (Pago/Total)</h3>
        <p class="text-2xl font-bold mb-2">
            <span class="text-green-600">
                {{ 'R$ ' . number_format($receitaMensalPago, 2, ',', '.') }}
            </span>
            <span class="text-gray-500"> / </span>
            <span class="text-gray-800">
                {{ 'R$ ' . number_format($receitaMensal, 2, ',', '.') }}
            </span>
        </p>
        <span class="text-sm text-gray-500">Mês atual</span>
    </div>
    <a href="{{ route('dashboard.mensalidadesAtrasadas') }}"
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



<div style="
    background: white;
    padding: 14px;
    border-radius: 10px;
    margin-bottom: 20px;
">

    <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 10px;">
        Evolução Matrículas por Mês
    </h3>

    <form method="GET" action="/dashboard" style="margin-bottom: 15px;">
        <label style="font-size: 14px; font-weight: bold; display: block; margin-bottom: 5px;">
            Selecione Ano
        </label>

        <select name="ano" onchange="this.form.submit()"
            style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">

            @foreach($anosDisponiveis as $ano)
            <option value="{{ $ano }}" {{ $ano == $anoSelecionado ? 'selected' : '' }}>
                {{ $ano }}
            </option>
            @endforeach
        </select>
    </form>

    <!-- altura reduzida ~30% (500px -> 350px) -->
    <div style="width: 100%; height: 350px;">
        <canvas
            id="graficoMatriculas"
            data-labels='@json($graficoLabels)'
            data-dados='@json($graficoDados)'
            data-dados-encerrados='@json($graficoDadosEncerrados)'
            data-dados-pausados='@json($graficoDadosPausados)'
            style="width: 100%; height: 100%;">
        </canvas>
    </div>

</div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const canvas = document.getElementById('graficoMatriculas');

        if (canvas) {
            const labels = JSON.parse(canvas.dataset.labels);
            const dados = JSON.parse(canvas.dataset.dados);
            const dadosEncerrados = JSON.parse(canvas.dataset.dadosEncerrados);
            const dadosPausados = JSON.parse(canvas.dataset.dadosPausados);

            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Matrículas Cadastradas',
                            data: dados,
                            backgroundColor: 'rgba(22, 101, 52, 0.7)',
                            borderColor: 'rgba(22, 101, 52, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Matrículas Pausadas',
                            data: dadosPausados,
                            backgroundColor: 'rgba(234, 88, 12, 0.7)',
                            borderColor: 'rgba(234, 88, 12, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Matrículas Encerradas',
                            data: dadosEncerrados,
                            backgroundColor: 'rgba(220, 38, 38, 0.7)',
                            borderColor: 'rgba(220, 38, 38, 1)',
                            borderWidth: 1
                        }
                    ]
                }
            });
        }


    });
</script>

@endsection