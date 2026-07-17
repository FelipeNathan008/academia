{{-- resources/views/view_professor_user/aula/index.blade.php --}}

@extends('layouts.dashboard')

@section('title', 'Aulas')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">

<x-alert-error />

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li class="text-gray-400">{{ $grade->professor->prof_nome }}</li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Aulas</li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('professor-frequencia') }}"
            class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>

        <h2 class="text-3xl font-extrabold text-gray-800">
            Minhas Aulas
        </h2>
    </div>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] transition">
        + Cadastrar Aula
    </button>
</div>

<!-- CARD PROFESSOR -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#174ab9] rounded-2xl shadow-lg p-6">
        <p class="text-xs uppercase tracking-widest text-gray-500">Professor</p>

        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
            {{ $grade->professor->prof_nome }}
        </h3>

        <p class="mt-2 text-sm text-gray-600">
            Telefone:
            <strong class="text-gray-800">
                {{ $grade->professor->prof_telefone
                    ? preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $grade->professor->prof_telefone)
                    : '-' }}
            </strong>
        </p>
    </div>
</div>

@php
$diasNomes = [
1 => 'Domingo',
2 => 'Segunda',
3 => 'Terça',
4 => 'Quarta',
5 => 'Quinta',
6 => 'Sexta',
7 => 'Sábado',
];

// Dias permitidos pela grade (1=Domingo ... 7=Sábado)
$diasPermitidos = collect(explode(',', $grade->grade_dia_semana))
->map(fn($d) => (int) trim($d))
->filter()
->values();

$diasSelecionados = $diasPermitidos
->map(fn($dia) => $diasNomes[$dia] ?? $dia)
->implode(' e ');
@endphp

<!-- FORMULÁRIO -->
<div id="cadastroForm"
    class="hidden mb-10 {{ $errors->any() ? '' : 'hidden' }}"
    data-dias-permitidos="{{ $diasPermitidos->implode(',') }}">

    <form action="{{ route('professor-aulas.store', Crypt::encrypt($grade->id_grade)) }}"
        method="POST"
        onsubmit="return validarSubmit(event, this)">

        @csrf

        <div class="bg-white rounded-2xl shadow-md p-8">

            <h3 class="text-xl font-bold mb-6 text-gray-700">
                Cadastrar Aula
            </h3>

            <div class="grid grid-cols-1 gap-6">

                <!-- GRADE SELECIONADA -->
                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Grade Selecionada
                    </label>

                    <div class="w-full border rounded-lg px-4 py-3 mt-1 bg-gray-100 text-gray-700">
                        {{ $grade->grade_modalidade }}
                        -
                        {{ $diasSelecionados }}
                        -
                        {{ \Carbon\Carbon::parse($grade->grade_inicio)->format('H:i') }}
                        às
                        {{ \Carbon\Carbon::parse($grade->grade_fim)->format('H:i') }}
                    </div>

                    <p class="text-xs text-gray-500 mt-1">
                        As datas de início e fim da aula só podem cair em: <strong>{{ $diasSelecionados }}</strong>.
                    </p>
                </div>

                <!-- Nome do Exercício + Status -->
                <div style="display: flex; gap: 4%;">
                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">
                            Nome do Exercício
                        </label>

                        <input type="text"
                            name="aula_nome_exercicio"
                            maxlength="150"
                            required
                            value="{{ old('aula_nome_exercicio') }}"
                            placeholder="Ex: Armlock"
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                    </div>

                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">
                            Status
                        </label>

                        <select name="aula_status"
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                            <option value="ativo" {{ old('aula_status', 'ativo') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="inativo" {{ old('aula_status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>
                </div>

                <!-- Característica -->
                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Característica do Exercício
                    </label>

                    <input type="text"
                        name="aula_caract_exercicio"
                        maxlength="255"
                        required
                        value="{{ old('aula_caract_exercicio') }}"
                        placeholder="Ex: Técnica de finalização"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                </div>

                <!-- Início e Fim -->
                <div style="display: flex; gap: 4%;">
                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">
                            Início
                        </label>

                        <input type="text"
                            id="aula_inicio"
                            name="aula_inicio"
                            required
                            autocomplete="off"
                            value="{{ old('aula_inicio') }}"
                            placeholder="Selecione a data"
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] bg-white">

                        <p id="erro_aula_inicio" class="text-red-600 text-sm mt-1 hidden"></p>
                    </div>

                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">
                            Fim
                        </label>

                        <input type="text"
                            id="aula_fim"
                            name="aula_fim"
                            required
                            autocomplete="off"
                            value="{{ old('aula_fim') }}"
                            placeholder="Selecione a data"
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] bg-white">

                        <p id="erro_aula_fim" class="text-red-600 text-sm mt-1 hidden"></p>
                    </div>
                </div>

                <!-- Link -->
                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Link (opcional)
                    </label>

                    <input type="url"
                        name="aula_link"
                        maxlength="255"
                        value="{{ old('aula_link') }}"
                        placeholder="https://..."
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                </div>

                <!-- Descrição -->
                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Descrição
                    </label>

                    <textarea name="aula_desc"
                        maxlength="255"
                        required
                        rows="3"
                        placeholder="Descreva a aula"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">{{ old('aula_desc') }}</textarea>
                </div>

            </div>

            <div class="flex justify-end gap-4 border-t pt-6 mt-8">

                <button type="button"
                    onclick="fecharCadastro()"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-100">
                    Cancelar
                </button>

                <button type="submit"
                    class="px-5 py-2 bg-[#8E251F] text-white rounded-lg">
                    Salvar
                </button>

            </div>
        </div>
    </form>
</div>

<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6 mb-6">

    <h3 class="text-xl font-bold mb-6 text-gray-700">
        AULAS CADASTRADAS
    </h3>

    <!-- FILTRO POR NOME -->
    <div class="flex justify-center mb-8">
        <div class="flex flex-wrap gap-4 items-end justify-center">

            <div class="flex flex-col w-[300px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Nome do Exercício
                </label>

                <input type="text"
                    id="filtroNomeAula"
                    autocomplete="off"
                    placeholder="Buscar por nome..."
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">
            </div>

            <!-- Limpar -->
            <button id="limparFiltroNome"
                class="h-[48px] px-6 rounded-xl bg-gray-300
                       text-gray-800 font-semibold hover:bg-gray-400
                       transition shadow-md">
                Limpar filtro
            </button>

        </div>
    </div>

    <table class="w-full text-left border-collapse">

        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Exercício</th>
                <th class="py-3 px-4">Característica</th>
                <th class="py-3 px-4">Período</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>

            @forelse ($aulas as $aula)

            <tr class="border-b hover:bg-gray-50 transition linha-aula"
                data-nome="{{ strtolower($aula->aula_nome_exercicio ?? '') }}">

                <td class="py-3 px-4">
                    {{ $aula->aula_nome_exercicio }}
                </td>

                <td class="py-3 px-4">
                    {{ $aula->aula_caract_exercicio }}
                </td>

                <td class="py-3 px-4">
                    {{ \Carbon\Carbon::parse($aula->aula_inicio)->format('d/m/Y') }}
                    até
                    {{ \Carbon\Carbon::parse($aula->aula_fim)->format('d/m/Y') }}
                </td>

                <td class="py-3 px-4">
                    @if($aula->aula_status === 'ativo')
                    <span style="padding:2px 8px; font-size:0.75rem;
                font-weight:600; border-radius:9999px;
                color:#166534; background-color:#bbf7d0;"> Ativo
                    </span>
                    @else
                    <span style="padding:2px 8px; font-size:0.75rem;
                font-weight:600; border-radius:9999px;
                color:#7f1d1d; background-color:#fecaca;">
                        Inativo
                    </span>
                    @endif
                </td>

                <td class="py-3 px-4 flex gap-2">

                    <a href="{{ route('professor-aulas.show', Crypt::encrypt($aula->id_aula)) }}"
                        style="background-color: #174ab9; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition duration-200 text-center">
                        Ver
                    </a>

                    <a href="{{ route('professor-aulas.edit', Crypt::encrypt($aula->id_aula)) }}"
                        style="background-color: #8E251F; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                        Editar
                    </a>

                    <form action="{{ route('professor-aulas.destroy', Crypt::encrypt($aula->id_aula)) }}"
                        method="POST"
                        onsubmit="return confirm('Deseja excluir esta aula?');">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            style="background-color: #c02600; color: white;"
                            class="px-4 py-2 rounded-lg shadow hover:bg-[#D65A3E] transition duration-200">
                            Excluir
                        </button>

                    </form>

                </td>
            </tr>

            @empty

            <tr>
                <td colspan="5"
                    class="text-center py-6 text-gray-500">
                    Nenhuma aula cadastrada para esta grade
                </td>
            </tr>

            @endforelse

            <tr id="msgNenhumaAulaFiltro" class="hidden">
                <td colspan="5" class="text-center py-6 text-gray-500">
                    Nenhuma aula encontrada com esse nome
                </td>
            </tr>

        </tbody>

    </table>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/pt.js"></script>

<script>
    function toggleCadastro() {
        document.getElementById('cadastroForm').classList.toggle('hidden');

        document.getElementById('cadastroForm').scrollIntoView({
            behavior: 'smooth'
        });
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {

        const cadastroForm = document.getElementById('cadastroForm');

        const diasPermitidos = cadastroForm.dataset.diasPermitidos ? cadastroForm.dataset.diasPermitidos.split(',').map(Number).filter(Boolean) : [];

        const diasNomes = {
            1: 'Domingo',
            2: 'Segunda',
            3: 'Terça',
            4: 'Quarta',
            5: 'Quinta',
            6: 'Sexta',
            7: 'Sábado'
        };

        flatpickr.localize(flatpickr.l10ns.pt);

        function diaPermitido(date) {
            const diaSemana = date.getDay() + 1;
            return diasPermitidos.includes(diaSemana);
        }

        const inicioPicker = flatpickr('#aula_inicio', {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            allowInput: false,
            disable: [
                function(date) {
                    return !diaPermitido(date);
                }
            ],
            onChange: function(selectedDates, dateStr) {
                document.getElementById('erro_aula_inicio').classList.add('hidden');

                if (dateStr) {
                    fimPicker.set('minDate', dateStr);
                }
            }
        });

        const fimPicker = flatpickr('#aula_fim', {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            allowInput: false,
            disable: [
                function(date) {
                    return !diaPermitido(date);
                }
            ],
            onChange: function() {
                document.getElementById('erro_aula_fim').classList.add('hidden');
            }
        });

        window._aulaValidacao = {
            diasPermitidos,
            diasNomes,
            inicioPicker,
            fimPicker
        };

        // ======================================
        // FILTRO POR NOME NA LISTAGEM
        // ======================================

        const filtroNome = document.getElementById('filtroNomeAula');
        const limparNome = document.getElementById('limparFiltroNome');
        const linhasAulas = document.querySelectorAll('.linha-aula');
        const msgNenhumaAula = document.getElementById('msgNenhumaAulaFiltro');

        function aplicarFiltroNome() {

            const nome = filtroNome.value.toLowerCase().trim();

            let algumaVisivel = false;

            linhasAulas.forEach(linha => {

                const nomeAula = linha.dataset.nome || '';
                const mostrar = !nome || nomeAula.includes(nome);

                linha.style.display = mostrar ? '' : 'none';

                if (mostrar) {
                    algumaVisivel = true;
                }
            });

            if (msgNenhumaAula) {
                msgNenhumaAula.classList.toggle('hidden', algumaVisivel || linhasAulas.length === 0);
            }
        }

        if (filtroNome) {
            filtroNome.addEventListener('input', aplicarFiltroNome);
        }

        if (limparNome) {
            limparNome.addEventListener('click', function() {
                filtroNome.value = '';
                aplicarFiltroNome();
            });
        }

    });

    function validarSubmit(event, form) {

        const {
            diasPermitidos,
            diasNomes
        } = window._aulaValidacao;

        const inicioInput = document.getElementById('aula_inicio');
        const fimInput = document.getElementById('aula_fim');
        const erroInicio = document.getElementById('erro_aula_inicio');
        const erroFim = document.getElementById('erro_aula_fim');

        erroInicio.classList.add('hidden');
        erroFim.classList.add('hidden');

        let valido = true;

        const nomesPermitidos = diasPermitidos.map(d => diasNomes[d]).join(', ');

        if (!inicioInput.value) {
            erroInicio.innerText = 'Selecione uma data válida (' + nomesPermitidos + ').';
            erroInicio.classList.remove('hidden');
            valido = false;
        }

        if (!fimInput.value) {
            erroFim.innerText = 'Selecione uma data válida (' + nomesPermitidos + ').';
            erroFim.classList.remove('hidden');
            valido = false;
        }

        if (inicioInput.value && fimInput.value && fimInput.value < inicioInput.value) {
            erroFim.innerText = 'A data final não pode ser anterior à data inicial.';
            erroFim.classList.remove('hidden');
            valido = false;
        }

        if (!form.checkValidity() || !valido) {
            event.preventDefault();
            return false;
        }

        const btn = form.querySelector('button[type="submit"]');

        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Salvando...';
        }

        return true;
    }
</script>

@endsection