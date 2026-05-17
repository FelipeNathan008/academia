{{-- resources/views/view_professor_user/graduacoes_aluno.blade.php --}}

@extends('layouts.dashboard')

@section('title', 'Graduações do Aluno')

@section('content')

<x-alert-error />

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2 flex-wrap">

        <li>
            <a href="{{ route('professor-aluno.index') }}"
                class="hover:text-[#8E251F] transition">
                Meus Alunos
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

        <a href="{{ route('professor-aluno.hub', Crypt::encrypt($aluno->id_aluno)) }}"
            class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>

        <h2 class="text-3xl font-extrabold text-gray-800">
            Graduações do Aluno
        </h2>

    </div>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">
        + Cadastrar Graduação
    </button>

</div>

<!-- CARD DO ALUNO -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#8E251F] rounded-2xl shadow-lg p-6">
        <p class="text-xs uppercase tracking-widest text-gray-500">
            Aluno selecionado
        </p>

        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
            {{ $aluno->aluno_nome }}
        </h3>

        <p class="mt-2 text-sm text-gray-600">
            Data de nascimento:
            <strong class="text-gray-800">
                {{ \Carbon\Carbon::parse($aluno->aluno_nascimento)->format('d/m/Y') }}
            </strong> <br>

            Idade:
            <strong class="text-gray-800">
                {{ $aluno->aluno_nascimento ? \Carbon\Carbon::parse($aluno->aluno_nascimento)->age : '-' }}
            </strong>
        </p>
    </div>
</div>

<!-- FORMULÁRIO -->
<div id="cadastroForm" class="hidden mb-10">

    <form action="{{ route('professor-detalhes-aluno.store', Crypt::encrypt($aluno->id_aluno)) }}"
        method="POST"
        enctype="multipart/form-data"
        onsubmit="bloquearSubmit(event, this)">

        @csrf

        <div class="bg-white rounded-2xl shadow-md p-8">

            <h3 class="text-xl font-bold mb-6 text-gray-700">
                Cadastrar Graduação do Aluno
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Graduação
                    </label>

                    <select name="det_gradu_nome_cor"
                        onchange="preencherGraus(this)"
                        class="w-full border rounded-lg px-3 py-2"
                        required>

                        <option value="">Selecione uma graduação</option>

                        @php $cores = []; @endphp

                        @foreach($graduacoesTotais as $g)

                        @if(!in_array($g->gradu_nome_cor, $cores))

                        @php
                        $cores[] = $g->gradu_nome_cor;

                        $grausDaCor = $graduacoesTotais
                        ->filter(fn($x) => $x->gradu_nome_cor == $g->gradu_nome_cor)
                        ->pluck('gradu_grau')
                        ->sort()
                        ->values()
                        ->all();
                        @endphp

                        <option value="{{ $g->gradu_nome_cor }}"
                            data-graus="{{ implode(',', $grausDaCor) }}">

                            {{ $g->gradu_nome_cor }}

                        </option>

                        @endif

                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Grau
                    </label>

                    <select name="det_grau"
                        class="w-full border rounded-lg px-3 py-2 grau-input"
                        required>

                        <option value="">
                            Selecione primeiro uma graduação
                        </option>

                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Modalidade
                    </label>

                    <select name="det_modalidade"
                        class="w-full border rounded-lg px-3 py-2"
                        required>

                        <option value="">Selecione</option>

                        @foreach($modalidades as $modalidade)

                        <option value="{{ $modalidade->mod_nome }}">
                            {{ $modalidade->mod_nome }}
                        </option>

                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Data
                    </label>

                    <input type="date"
                        name="det_data"
                        class="w-full border rounded-lg px-3 py-2"
                        required>
                </div>

                <div class="md:col-span-2">

                    <label class="text-sm font-medium text-gray-600">
                        Certificado (Imagem ou PDF)
                    </label>

                    <input type="file"
                        name="det_certificado"
                        accept=".jpg,.jpeg,.png,.pdf"
                        class="w-full border rounded-lg px-3 py-2">

                </div>

            </div>

            <div class="flex justify-end gap-4 border-t pt-6 mt-8">

                <button type="button"
                    onclick="fecharCadastro()"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-100">

                    Cancelar

                </button>

                <button type="submit"
                    class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">

                    Salvar

                </button>

            </div>

        </div>

    </form>

</div>

<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">

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
                        <th class="py-3 px-4">Ações</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($lista as $det)

                    <tr class="border-b hover:bg-gray-50 transition">

                        <td class="py-3 px-4">

                            <span class="bolinha-faixa"
                                data-faixa="{{ strtolower($det->det_gradu_nome_cor) }}"
                                style="display:inline-block; width:16px; height:16px; border-radius:50%; margin-right:8px; vertical-align:middle; border:2px solid #000;">
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


                        <td class="py-3 px-4 flex gap-2">

                            @if($det->det_certificado)

                            <a href="{{ route('professor-detalhes-aluno.certificado', ['path' => Crypt::encrypt($det->det_certificado)]) }}"
                                target="_blank"
                                style="background-color: #174ab9; color: white;"
                                class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                                Ver Certificado
                            </a>

                            @endif

                            <a href="{{ route('professor-detalhes-aluno.edit', Crypt::encrypt($det->id_det_aluno)) }}"
                                style="background-color: #8E251F; color: white;"
                                class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                                Editar
                            </a>

                            <form action="{{ route('professor-detalhes-aluno.destroy', Crypt::encrypt($det->id_det_aluno)) }}"
                                method="POST"
                                onsubmit="return confirm('Deseja remover esta graduação?');">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                    Excluir
                                </button>

                            </form>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500">
                            Nenhuma graduação cadastrada
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

</div>

<script>
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

    function toggleCadastro() {

        const form = document.getElementById('cadastroForm');

        form.classList.toggle('hidden');

        form.scrollIntoView({
            behavior: 'smooth'
        });
    }

    function fecharCadastro() {

        document
            .getElementById('cadastroForm')
            .classList
            .add('hidden');
    }

    function bloquearSubmit(event, form) {

        if (!form.checkValidity()) {
            return;
        }

        const btn = form.querySelector('button[type="submit"]');

        if (btn) {

            btn.disabled = true;
            btn.innerText = 'Salvando...';
        }
    }

    function preencherGraus(select) {

        const grauSelect = select
            .closest('form')
            .querySelector('.grau-input');

        grauSelect.innerHTML = '';

        if (!select.value) {

            grauSelect.innerHTML =
                '<option value="">Selecione primeiro uma graduação</option>';

            return;
        }

        const graus = select.selectedOptions[0]
            .dataset
            .graus
            .split(',')
            .sort((a, b) => a - b);

        grauSelect.innerHTML =
            '<option value="">Selecione um grau</option>';

        graus.forEach(g => {

            const option = document.createElement('option');

            option.value = g;
            option.textContent = g;

            grauSelect.appendChild(option);
        });
    }
</script>

@endsection