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

@if($empresa->emp_status == 'Degustação')

<div class="bg-white rounded-2xl shadow-md overflow-hidden mb-10">

    <div class="px-6 py-4 border-b bg-yellow-50">
        <h3 class="text-lg font-bold text-yellow-700">
            Período de Degustação
        </h3>
    </div>

    <div class="grid md:grid-cols-3 gap-6 p-6">

        <div>
            <p class="text-xs uppercase text-gray-500">
                Início
            </p>

            <p class="font-semibold">
                {{ $empresa->emp_degustacao_inicio->format('d/m/Y') }}
            </p>
        </div>

        <div>
            <p class="text-xs uppercase text-gray-500">
                Fim
            </p>

            <p class="font-semibold">
                {{ $empresa->emp_degustacao_fim->format('d/m/Y') }}
            </p>
        </div>

        <div>
            <p class="text-xs uppercase text-gray-500">
                Status
            </p>

            <span class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-700 font-semibold">
                Degustação
            </span>
        </div>

    </div>

</div>

@elseif($contrato)

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

        <a href="{{ route('contrato.show', Crypt::encrypt($contrato->id_contrato)) }}"
            class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">
            Ver contrato
        </a>
    </div>

</div>

@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

    <div class="p-6 bg-white rounded-xl shadow-sm">
        <h3 class="font-semibold text-lg mb-2">Total de Alunos Matriculados</h3>
        <p class="text-2xl font-bold mb-2">{{ $totalAlunosMatriculados }}</p>
        <span class="text-sm text-gray-500">Total Geral</span>
    </div>

    <a href="{{ route('alunos.index', ['matricula' => 'Encerrada']) }}"
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

    <a href="{{ route('alunos.index', ['bolsista' => 'sim']) }}"
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

    <a href="{{ route('dashboard.comprovantes') }}"
        class="block p-6 rounded-xl shadow-sm
          {{ $comprovantesPendentes > 0
                ? 'bg-red-50 border border-red-300'
                : 'bg-white' }}">

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