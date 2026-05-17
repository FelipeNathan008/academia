@extends('layouts.dashboard')

@section('title', 'Editar Graduação do Aluno')

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
            Editar Graduação
        </li>

    </ol>
</nav>

<!-- TOPO -->
<div class="flex items-center gap-4 mb-10">

    <a href="{{ route('professor-detalhes-aluno.index', Crypt::encrypt($aluno->id_aluno)) }}"
        class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">

        ← Voltar

    </a>

    <h2 class="text-3xl font-extrabold text-gray-800">
        Editar Graduação do Aluno
    </h2>

</div>

<!-- CARD -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#8E251F] rounded-2xl shadow-lg p-6">

        <p class="text-xs uppercase tracking-widest text-gray-500">
            Graduação selecionada
        </p>

        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
            {{ $detalhe->det_gradu_nome_cor }}
        </h3>

        <p class="mt-2 text-sm text-gray-600">
            Aluno:
            <strong class="text-gray-800">
                {{ $aluno->aluno_nome }}
            </strong> <br>

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

<!-- FORM -->
<div class="bg-white rounded-2xl shadow-md p-8">

    <form action="{{ route('professor-detalhes-aluno.update', Crypt::encrypt($detalhe->id_det_aluno)) }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- GRADUAÇÃO -->
            <div>

                <label class="text-sm font-medium text-gray-600">
                    Graduação
                </label>

                <select name="det_gradu_nome_cor"
                    onchange="preencherGraus(this)"
                    class="w-full border rounded-lg px-3 py-2"
                    required>

                    <option value="">
                        Selecione uma graduação
                    </option>

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
                        data-graus="{{ implode(',', $grausDaCor) }}"
                        {{ $detalhe->det_gradu_nome_cor == $g->gradu_nome_cor ? 'selected' : '' }}>

                        {{ $g->gradu_nome_cor }}

                    </option>

                    @endif

                    @endforeach

                </select>

            </div>

            <!-- GRAU -->
            <div>

                <label class="text-sm font-medium text-gray-600">
                    Grau
                </label>

                <select name="det_grau"
                    class="w-full border rounded-lg px-3 py-2 grau-input"
                    required>

                </select>

            </div>

            <!-- MODALIDADE -->
            <div>

                <label class="text-sm font-medium text-gray-600">
                    Modalidade
                </label>

                <select name="det_modalidade"
                    class="w-full border rounded-lg px-3 py-2"
                    required>

                    <option value="">
                        Selecione
                    </option>

                    @foreach($modalidades as $modalidade)

                    <option value="{{ $modalidade->mod_nome }}"
                        {{ $detalhe->det_modalidade == $modalidade->mod_nome ? 'selected' : '' }}>

                        {{ $modalidade->mod_nome }}

                    </option>

                    @endforeach

                </select>

            </div>

            <!-- DATA -->
            <div>

                <label class="text-sm font-medium text-gray-600">
                    Data
                </label>

                <input type="date"
                    name="det_data"
                    value="{{ $detalhe->det_data }}"
                    class="w-full border rounded-lg px-3 py-2"
                    required>

            </div>

            <!-- CERTIFICADO -->
            <div class="md:col-span-2">

                <label class="text-sm font-medium text-gray-600">
                    Certificado (Imagem ou PDF)
                </label>

                <input type="file"
                    name="det_certificado"
                    accept=".jpg,.jpeg,.png,.pdf"
                    class="w-full border rounded-lg px-3 py-2">

                @if($detalhe->det_certificado)

                <div class="mt-4">

                    <a href="{{ route('professor-detalhes-aluno.certificado', ['path' => Crypt::encrypt($detalhe->det_certificado)]) }}"
                        target="_blank"
                        style="background-color: #174ab9; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">

                        Ver Certificado Atual

                    </a>

                </div>

                @endif

            </div>

        </div>

        <!-- BOTÕES -->
        <div class="flex justify-end gap-4 border-t pt-6 mt-8">

            <a href="{{ route('professor-detalhes-aluno.index', Crypt::encrypt($aluno->id_aluno)) }}"
                class="px-5 py-2 border rounded-lg hover:bg-gray-100">

                Cancelar

            </a>

            <button type="submit"
                class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">

                Salvar Alterações

            </button>

        </div>

    </form>

</div>

<script>
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

            if (g == "{{ $detalhe->det_grau }}") {
                option.selected = true;
            }

            grauSelect.appendChild(option);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {

        const selectFaixa = document.querySelector(
            'select[name="det_gradu_nome_cor"]'
        );

        if (selectFaixa) {
            preencherGraus(selectFaixa);
        }
    });
</script>

@endsection