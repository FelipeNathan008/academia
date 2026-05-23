@extends('layouts.dashboard')

@section('title', 'Graduações do Aluno')

@section('content')

<x-alert-error />

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2 flex-wrap">

        <li>
            <a href="{{ route('aluno.index') }}"
                class="hover:text-[#8E251F] transition">
                Alunos
            </a>
        </li>

        <li>/</li>

        <li class="text-gray-400">
            {{ $aluno->aluno_nome }}
        </li>

        <li>/</li>

        <li class="font-semibold text-gray-700">
            Graduações
        </li>

    </ol>
</nav>

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">

    <div class="flex items-center gap-4">

        <a href="{{ route('aluno.index', Crypt::encrypt($aluno->id_aluno)) }}"
            class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>

        <h2 class="text-3xl font-extrabold text-gray-800">
            Graduações do Aluno
        </h2>

    </div>

</div>

<!-- CARD DO ALUNO -->
<div class="mb-8">

    <div class="bg-white border-l-8 border-[#174ab9] rounded-2xl shadow-lg p-6">

        <p class="text-xs uppercase tracking-widest text-gray-500">
            Aluno selecionado
        </p>

        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
            {{ $aluno->aluno_nome }}
        </h3>

        <p class="mt-2 text-sm text-gray-600">
            Idade:
            <strong class="text-gray-800">
                {{ $aluno->aluno_nascimento ? \Carbon\Carbon::parse($aluno->aluno_nascimento)->age : '-' }}
            </strong>
        </p>

    </div>

</div>

<!-- FILTROS -->
<div class="bg-white rounded-2xl shadow-md p-6 overflow-x-auto mb-8">

    <div class="flex justify-center">

        <div class="flex flex-wrap gap-6 items-end justify-center">

            <!-- Graduação -->
            <div class="flex flex-col w-[250px]">

                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Graduação
                </label>

                <select id="filtroGraduacao"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">

                    <option value="">Todas</option>

                    @foreach($graduacoesTotais->pluck('gradu_nome_cor')->unique() as $cor)

                    <option value="{{ strtolower($cor) }}">
                        {{ $cor }}
                    </option>

                    @endforeach

                </select>

            </div>

            <!-- Modalidade -->
            <div class="flex flex-col w-[250px]">

                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Modalidade
                </label>

                <select id="filtroModalidade"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">

                    <option value="">Todas</option>

                    @foreach($modalidades as $modalidade)

                    <option value="{{ strtolower($modalidade->mod_nome) }}">
                        {{ $modalidade->mod_nome }}
                    </option>

                    @endforeach

                </select>

            </div>

            <!-- LIMPAR -->
            <button id="limparFiltrosGraduacao"
                class="h-[48px] px-6 rounded-xl bg-gray-300
                       text-gray-800 font-semibold hover:bg-gray-400
                       transition shadow-md">

                Limpar filtros

            </button>

        </div>

    </div>

</div>

<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6 mb-6">

    <h3 class="text-xl font-bold mb-6 text-gray-700">
        GRADUAÇÕES CADASTRADAS
    </h3>

    @php

    $ordem = [
        'cinza e branca' => 1,
        'cinza' => 2,
        'cinza e preta' => 3,

        'amarela e branca' => 4,
        'amarela' => 5,
        'amarela e preta' => 6,

        'laranja e branca' => 7,
        'laranja' => 8,
        'laranja e preta' => 9,

        'verde e branca' => 10,
        'verde' => 11,
        'verde e preta' => 12,

        'branca' => 13,
        'azul' => 14,
        'roxa' => 15,
        'marrom' => 16,
        'preta' => 17,
    ];

    $lista = $graduacoes->sort(function ($a, $b) use ($ordem) {

        $faixaA = strtolower($a->det_gradu_nome_cor);
        $faixaB = strtolower($b->det_gradu_nome_cor);

        $ordA = $ordem[$faixaA] ?? 99;
        $ordB = $ordem[$faixaB] ?? 99;

        $grauA = intval($a->det_grau);
        $grauB = intval($b->det_grau);

        return $ordA === $ordB
            ? $grauB <=> $grauA
            : $ordB <=> $ordA;
    });

    @endphp

    <table class="w-full text-left border-collapse">

        <thead>

            <tr class="border-b text-gray-600 text-sm">

                <th class="py-3 px-4">Graduação</th>
                <th class="py-3 px-4">Grau</th>
                <th class="py-3 px-4">Modalidade</th>
                <th class="py-3 px-4">Data</th>
                <th class="py-3 px-4">Certificado</th>

            </tr>

        </thead>

        <tbody>

            @forelse ($lista as $det)

            <tr class="border-b hover:bg-gray-50 transition linha-graduacao"
                data-graduacao="{{ strtolower($det->det_gradu_nome_cor) }}"
                data-modalidade="{{ strtolower($det->det_modalidade) }}">

                <td class="py-3 px-4">

                    <span class="bolinha-faixa"
                        data-faixa="{{ strtolower($det->det_gradu_nome_cor) }}"
                        style="display:inline-block; width:16px; height:16px; border-radius:50%; margin-right:8px; vertical-align:middle; border:2px solid #000; background-color:transparent;">
                    </span>

                    {{ $det->det_gradu_nome_cor }}

                </td>

                <td class="py-3 px-4">
                    {{ $det->det_grau }}
                </td>

                <td class="py-3 px-4">
                    {{ $det->det_modalidade }}
                </td>

                <td class="py-3 px-4">
                    {{ \Carbon\Carbon::parse($det->det_data)->format('d/m/Y') }}
                </td>

                <td class="py-3 px-4">

                    @if($det->det_certificado)

                    <a href="{{ route('aluno-detalhes.showCertificado', ['path' => Crypt::encrypt($det->det_certificado)]) }}"
                        target="_blank"
                        style="background-color: #174ab9; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#12398f] transition duration-200 text-center inline-block">

                        Ver Certificado

                    </a>

                    @else

                    <span class="text-gray-400">
                        Não enviado
                    </span>

                    @endif

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="5"
                    class="text-center py-6 text-gray-500">

                    Nenhuma graduação cadastrada

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>

<script>

    document.addEventListener('DOMContentLoaded', function() {

        const filtroGraduacao = document.getElementById('filtroGraduacao');
        const filtroModalidade = document.getElementById('filtroModalidade');
        const limparBtn = document.getElementById('limparFiltrosGraduacao');
        const linhas = document.querySelectorAll('.linha-graduacao');

        function aplicarFiltro() {

            const graduacao = filtroGraduacao.value;
            const modalidade = filtroModalidade.value;

            linhas.forEach(linha => {

                const graduacaoLinha = linha.dataset.graduacao || '';
                const modalidadeLinha = linha.dataset.modalidade || '';

                let mostrar = true;

                if (graduacao && graduacaoLinha !== graduacao) {
                    mostrar = false;
                }

                if (modalidade && modalidadeLinha !== modalidade) {
                    mostrar = false;
                }

                linha.style.display = mostrar ? '' : 'none';
            });
        }

        filtroGraduacao.addEventListener('change', aplicarFiltro);
        filtroModalidade.addEventListener('change', aplicarFiltro);

        limparBtn.addEventListener('click', function() {

            filtroGraduacao.value = '';
            filtroModalidade.value = '';

            aplicarFiltro();

        });

    });

    // BOLINHAS
    document.querySelectorAll('.bolinha-faixa').forEach(bolinha => {

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

</script>

@endsection