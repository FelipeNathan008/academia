@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')

<h2 class="text-2xl font-bold mb-6">Visão Geral</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="p-6 bg-white rounded-xl shadow-sm">
        <h3 class="font-semibold text-lg mb-2">Total de Alunos</h3>
        <p class="text-2xl font-bold mb-2">{{ $matriculasAtivas }}</p>
        <span class="text-sm text-gray-500">Total Geral</span>
    </div>

    <a href="{{ route('matricula.index') }}"
        class="block p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition">

        <h3 class="font-semibold text-lg mb-2">
            Total de Alunos Não Matriculados
        </h3>

        <p class="text-2xl font-bold mb-2">
            {{ $matriculasNaoAtivas }}
        </p>

        <span class="text-sm text-blue-600 underline hover:text-blue-800 font-medium">
            Ver todos os Alunos →
        </span>
    </a>

    <div class="p-6 bg-white rounded-xl shadow-sm">
        <h3 class="font-semibold text-lg mb-2">Matrículas Ativas</h3>
        <p class="text-2xl font-bold mb-2">{{ $totalMatriculas }}</p>
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


<h2 class="text-xl font-bold mb-4">
    Alunos por Graduação
</h2>

<form method="GET" action="{{ route('dashboard') }}" class="mb-6 flex gap-4">

    <select name="modalidade"
        onchange="this.form.submit()"
        class="border rounded-lg px-4 py-2">

        <option value="">Selecione</option>

        @foreach ($modalidades as $modalidade)
        <option value="{{ $modalidade }}"
            {{ ($modalidadeSelecionada ?? '') == $modalidade ? 'selected' : '' }}>
            {{ $modalidade }}
        </option>
        @endforeach

    </select>

    <select id="tipoGraduacao"
        class="border rounded-lg px-4 py-2">
        <option value="">Selecione</option>
        <option value="adultos">Adultos</option>
        <option value="kids">Kids</option>
    </select>

</form>

<!-- GRADUAÇÕES ADULTOS -->
<div id="graduacoesAdultos">
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:24px;margin-bottom:40px;">

        <a href="{{ route('dashboard.graduacoes', array_filter([
            'modalidade' => request()->query('modalidade') ?? null,
            'faixa' => 'branca'
        ])) }}" class="block">
            <x-graduacao-card
                titulo="Faixa Branca"
                subtitulo="Iniciantes"
                :valor="$graduacaoBranca"
                borderColor="#d1d5db"
                bgColor="bg-gray-50"
                textColor="text-gray-600"
                valueColor="text-gray-700"
                circleStyle="border:2px solid #000;background-color:#ffffff;" />
        </a>

        <a href="{{ route('dashboard.graduacoes', array_filter([
            'modalidade' => request()->query('modalidade') ?? null,
            'faixa' => 'azul'
        ])) }}" class="block">
            <x-graduacao-card
                titulo="Faixa Azul"
                subtitulo="Intermediário"
                :valor="$graduacaoAzul"
                borderColor="#2563eb"
                bgColor="bg-blue-50"
                textColor="text-blue-700"
                valueColor="text-blue-700"
                circleStyle="background-color:#2563eb;" />
        </a>

        <a href="{{ route('dashboard.graduacoes', array_filter([
            'modalidade' => request()->query('modalidade') ?? null,
            'faixa' => 'roxa'
        ])) }}" class="block">
            <x-graduacao-card
                titulo="Faixa Roxa"
                subtitulo="Intermediário"
                :valor="$graduacaoRoxa"
                borderColor="#7c3aed"
                bgColor="bg-purple-50"
                textColor="text-purple-700"
                valueColor="text-purple-700"
                circleStyle="background-color:#7c3aed;" />
        </a>

        <a href="{{ route('dashboard.graduacoes', array_filter([
            'modalidade' => request()->query('modalidade') ?? null,
            'faixa' => 'marrom'
        ])) }}" class="block">
            <x-graduacao-card
                titulo="Faixa Marrom"
                subtitulo="Avançado"
                :valor="$graduacaoMarrom"
                borderColor="#78350f"
                bgColor="bg-yellow-50"
                textColor="text-yellow-800"
                valueColor="text-yellow-900"
                circleStyle="background-color:#78350f;" />
        </a>

        <a href="{{ route('dashboard.graduacoes', array_filter([
            'modalidade' => request()->query('modalidade') ?? null,
            'faixa' => 'preta'
        ])) }}" class="block">
            <x-graduacao-card
                titulo="Faixa Preta"
                subtitulo="Mestres"
                :valor="$graduacaoPreta"
                borderColor="#000000"
                bgColor="bg-gray-100"
                textColor="text-gray-800"
                valueColor="text-black"
                circleStyle="background-color:#000000;" />
        </a>

    </div>
</div>

<!-- GRADUAÇÕES KIDS -->

<div id="graduacoesKids">
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:24px;margin-bottom:40px;">

        <x-graduacao-card
            titulo="Cinza e Branca"
            subtitulo="Graduação Inicial"
            :valor="$graduacaoCinzaBranca"
            borderColor="#9ca3af"
            bgColor="bg-gray-50"
            textColor="text-gray-600"
            valueColor="text-gray-700"
            circleStyle="border:2px solid #000;background:linear-gradient(to right,#9ca3af 50%,#ffffff 50%);" />

        <x-graduacao-card
            titulo="Cinza"
            subtitulo="Graduação"
            :valor="$graduacaoCinza"
            borderColor="#6b7280"
            bgColor="bg-gray-100"
            textColor="text-gray-700"
            valueColor="text-gray-800"
            circleStyle="background-color:#6b7280;" />

        <x-graduacao-card
            titulo="Cinza e Preta"
            subtitulo="Evolução"
            :valor="$graduacaoCinzaPreta"
            borderColor="#4b5563"
            bgColor="bg-gray-50"
            textColor="text-gray-800"
            valueColor="text-gray-900"
            circleStyle="background:linear-gradient(to right,#6b7280 50%,#000000 50%);" />

        <x-graduacao-card
            titulo="Amarela e Branca"
            subtitulo="Intermediário"
            :valor="$graduacaoAmarelaBranca"
            borderColor="#facc15"
            bgColor="bg-yellow-50"
            textColor="text-yellow-700"
            valueColor="text-yellow-700"
            circleStyle="border:2px solid #000;background:linear-gradient(to right,#facc15 50%,#ffffff 50%);" />

        <x-graduacao-card
            titulo="Amarela"
            subtitulo="Intermediário"
            :valor="$graduacaoAmarela"
            borderColor="#eab308"
            bgColor="bg-yellow-100"
            textColor="text-yellow-800"
            valueColor="text-yellow-800"
            circleStyle="background-color:#eab308;" />

        <x-graduacao-card
            titulo="Amarela e Preta"
            subtitulo="Evolução"
            :valor="$graduacaoAmarelaPreta"
            borderColor="#ca8a04"
            bgColor="bg-yellow-200"
            textColor="text-yellow-900"
            valueColor="text-yellow-900"
            circleStyle="background:linear-gradient(to right,#facc15 50%,#000000 50%);" />

        <x-graduacao-card
            titulo="Laranja e Branca"
            subtitulo="Intermediário"
            :valor="$graduacaoLaranjaBranca"
            borderColor="#fb923c"
            bgColor="bg-orange-50"
            textColor="text-orange-700"
            valueColor="text-orange-700"
            circleStyle="border:2px solid #000;background:linear-gradient(to right,#f97316 50%,#ffffff 50%);" />

        <x-graduacao-card
            titulo="Laranja"
            subtitulo="Intermediário"
            :valor="$graduacaoLaranja"
            borderColor="#f97316"
            bgColor="bg-orange-100"
            textColor="text-orange-800"
            valueColor="text-orange-800"
            circleStyle="background-color:#f97316;" />

        <x-graduacao-card
            titulo="Laranja e Preta"
            subtitulo="Evolução"
            :valor="$graduacaoLaranjaPreta"
            borderColor="#ea580c"
            bgColor="bg-orange-200"
            textColor="text-orange-900"
            valueColor="text-orange-900"
            circleStyle="background:linear-gradient(to right,#f97316 50%,#000000 50%);" />

        <x-graduacao-card
            titulo="Verde e Branca"
            subtitulo="Intermediário"
            :valor="$graduacaoVerdeBranca"
            borderColor="#4ade80"
            bgColor="bg-green-50"
            textColor="text-green-700"
            valueColor="text-green-700"
            circleStyle="border:2px solid #000;background:linear-gradient(to right,#22c55e 50%,#ffffff 50%);" />

        <x-graduacao-card
            titulo="Verde"
            subtitulo="Intermediário"
            :valor="$graduacaoVerde"
            borderColor="#22c55e"
            bgColor="bg-green-100"
            textColor="text-green-800"
            valueColor="text-green-800"
            circleStyle="background-color:#22c55e;" />

        <x-graduacao-card
            titulo="Verde e Preta"
            subtitulo="Evolução"
            :valor="$graduacaoVerdePreta"
            borderColor="#15803d"
            bgColor="bg-green-200"
            textColor="text-green-900"
            valueColor="text-green-900"
            circleStyle="background:linear-gradient(to right,#22c55e 50%,#000000 50%);" />

    </div>
</div>

<script>
    document.querySelectorAll('.bolinha-faixa-dashboard').forEach(bolinha => {
        const faixa = bolinha.dataset.faixa;
        let cor = 'transparent';

        if (faixa.includes('cinza e branca')) cor = '#808080';
        else if (faixa.includes('branca')) cor = '#ffffff';
        else if (faixa.includes('amarela')) cor = '#facc15';
        else if (faixa.includes('laranja')) cor = '#f97316';
        else if (faixa.includes('verde')) cor = '#22c55e';
        else if (faixa.includes('azul')) cor = '#2563eb';
        else if (faixa.includes('roxa')) cor = '#7c3aed';
        else if (faixa.includes('marrom')) cor = '#78350f';
        else if (faixa.includes('preta')) cor = '#000000';

        bolinha.style.backgroundColor = cor;
    });

    const selectTipo = document.getElementById('tipoGraduacao');
    const blocoKids = document.getElementById('graduacoesKids');
    const blocoAdultos = document.getElementById('graduacoesAdultos');

    function atualizarVisualizacao() {
        if (selectTipo.value === 'adultos') {
            blocoAdultos.style.display = 'grid';
            blocoKids.style.display = 'none';
        } else {
            blocoAdultos.style.display = 'none';
            blocoKids.style.display = 'grid';
        }
    }

    selectTipo.addEventListener('change', atualizarVisualizacao);

    // inicializa
    atualizarVisualizacao();


    document.addEventListener("DOMContentLoaded", function() {

        document.querySelectorAll('.graduacao-card').forEach(card => {

            // aplica border-left
            const borderColor = card.dataset.borderColor;
            if (borderColor) {
                card.style.borderLeft = `8px solid ${borderColor}`;
            }

            // aplica estilo do círculo
            const circle = card.querySelector('.graduacao-circle');
            const circleStyle = circle?.dataset.style;

            if (circle && circleStyle) {
                circle.style.cssText = `
                width:22px;
                height:22px;
                border-radius:50%;
                ${circleStyle}
            `;
            }
        });

    });
</script>

@endsection