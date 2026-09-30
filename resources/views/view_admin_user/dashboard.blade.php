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

    @php
    $hoje = now()->startOfDay();
    $fimDegustacao = $empresa->emp_degustacao_fim?->startOfDay();

    $diasRestantes = $fimDegustacao
    ? $hoje->diffInDays($fimDegustacao, false)
    : null;
    @endphp

    {{-- AVISO: faltam 5 dias ou menos --}}
    @if($diasRestantes !== null && $diasRestantes >= 0 && $diasRestantes <= 5)

        <div class="mx-6 mt-6 p-4 rounded-xl border border-yellow-300 bg-yellow-50">
        <div class="flex items-start gap-3">

            <div class="text-yellow-600 text-xl">
                ⚠️
            </div>

            <div>
                <h4 class="font-bold text-yellow-800">
                    Atenção: período de degustação chegando ao fim
                </h4>

                <p class="text-sm text-yellow-700 mt-1">
                    @if($diasRestantes == 0)
                    Seu período de degustação termina <strong>hoje</strong>.
                    @elseif($diasRestantes == 1)
                    Falta apenas <strong>1 dia</strong> para o fim do período de degustação.
                    @else
                    Faltam apenas <strong>{{ $diasRestantes }} dias</strong> para o fim do período de degustação.
                    @endif
                </p>

                <p class="text-sm text-yellow-700 mt-1">
                    Para continuar utilizando a plataforma, é necessário realizar a
                    <strong>contratação de um plano e formalizar o contrato</strong>.
                </p>

            </div>

        </div>
</div>

@endif

<div class="grid md:grid-cols-4 gap-6 p-6">

    <div>
        <p class="text-xs uppercase text-gray-500">
            Início
        </p>
        <p class="font-semibold">
            {{ $empresa->emp_degustacao_inicio?->format('d/m/Y') ?? 'N/D' }}
        </p>
    </div>

    <div>
        <p class="text-xs uppercase text-gray-500">
            Fim
        </p>

        <p class="font-semibold">
            {{ $empresa->emp_degustacao_fim?->format('d/m/Y') ?? 'N/D' }}
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

    <div class="flex items-center">
        <a href="{{ route('admin.planos') }}"
            class="w-full text-center px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920] transition font-semibold">
            Ver Planos
        </a>
    </div>

</div>
</div>

@elseif($contrato)

@if(
$contrato &&
$contrato->detalheAtual &&
$contrato->detalheAtual->det_plano_tipo != 'financeiro' &&
$contagemMatriculas >= $contrato->detalheAtual->det_plano_quantidade
)

<div class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3 mb-4">
    <div class="flex items-center justify-between gap-4">

        <div class="flex items-center gap-2">
            <span class="text-yellow-600">⚠️</span>

            <p class="text-sm text-yellow-800">
                O limite de matrículas do seu plano foi excedido.
                <strong>
                    {{ $contagemMatriculas }}/{{ $contrato->detalheAtual->det_plano_quantidade }}
                </strong>
                matrículas utilizadas.
            </p>

        </div>

        <a href="{{ route('admin.planos') }}"
            class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">
            Atualizar contrato
        </a>

    </div>
</div>

@endif

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

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

    <div class="p-6 bg-white rounded-xl shadow-sm border-2 border-[#8E251F] relative overflow-hidden">

        <!-- Destaque lateral -->
        <div class="absolute left-0 top-0 bottom-0 w-2 bg-[#8E251F]"></div>

        <div class="pl-2">

            <div class="flex items-center justify-between gap-3 mb-2">

                <h3 class="font-semibold text-lg text-gray-800">
                    Quantidade de Matrículas da Academia
                </h3>

                <span class="px-2.5 py-1 text-xs font-bold uppercase tracking-wide
                         text-[#8E251F] bg-[#8E251F]/10 rounded-full">
                    Contrato
                </span>

            </div>

            <p class="text-3xl font-bold text-[#8E251F] mb-2">
                {{ $contagemMatriculas }}
            </p>

            <span class="text-sm text-gray-500">
                Total de Matrículas "Matriculado" e "Pausada"
            </span>

        </div>

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

    <div class="p-6 bg-white rounded-xl shadow-sm ">
        <h3 class="font-semibold text-lg mb-2">Total de Alunos Matriculados</h3>
        <p class="text-2xl font-bold mb-2">{{ $totalAlunosMatriculados }}</p>
        <span class="text-sm text-gray-500">Total de Matrículas "Matriculado"</span>
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