@extends('layouts.dashboard')

@section('title', 'Editar Aula')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">

<x-alert-error />

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li class="text-gray-400">{{ $aula->gradeHorario->professor->prof_nome ?? '-' }}</li>
        <li>/</li>
        <li>
            <a href="{{ route('professor-aulas', Crypt::encrypt($aula->gradeHorario->id_grade)) }}"
                class="hover:text-[#8E251F] transition">
                Aulas
            </a>
        </li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Editar Aula</li>
    </ol>
</nav>

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

    $diasPermitidos = collect(explode(',', $aula->gradeHorario->grade_dia_semana))
        ->map(fn($d) => (int) trim($d))
        ->filter()
        ->values();

    $diasSelecionados = $diasPermitidos
        ->map(fn($dia) => $diasNomes[$dia] ?? $dia)
        ->implode(' e ');
@endphp

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-8"
    id="editarAulaForm"
    data-dias-permitidos="{{ $diasPermitidos->implode(',') }}">

    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Editar Aula – {{ $aula->aula_nome_exercicio }}
    </h2>

    <!-- GRADE SELECIONADA -->
    <div class="mb-6">
        <label class="text-sm font-medium text-gray-600">
            Grade Selecionada
        </label>

        <div class="w-full border rounded-lg px-4 py-3 mt-1 bg-gray-100 text-gray-700">
            {{ $aula->gradeHorario->grade_modalidade }}
            -
            {{ $diasSelecionados }}
            -
            {{ \Carbon\Carbon::parse($aula->gradeHorario->grade_inicio)->format('H:i') }}
            às
            {{ \Carbon\Carbon::parse($aula->gradeHorario->grade_fim)->format('H:i') }}
        </div>

        <p class="text-xs text-gray-500 mt-1">
            As datas de início e fim da aula só podem cair em: <strong>{{ $diasSelecionados }}</strong>.
        </p>
    </div>

    <form action="{{ route('professor-aulas.update', Crypt::encrypt($aula->id_aula)) }}"
        method="POST"
        onsubmit="return validarSubmit(event, this)">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6">

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
                        value="{{ old('aula_nome_exercicio', $aula->aula_nome_exercicio) }}"
                        placeholder="Ex: Armlock"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                </div>

                <div style="flex: 1;">
                    <label class="text-sm font-medium text-gray-600">
                        Status
                    </label>

                    <select name="aula_status"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                        <option value="ativo" {{ old('aula_status', $aula->aula_status) == 'ativo' ? 'selected' : '' }}>Ativo</option>
                        <option value="inativo" {{ old('aula_status', $aula->aula_status) == 'inativo' ? 'selected' : '' }}>Inativo</option>
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
                    value="{{ old('aula_caract_exercicio', $aula->aula_caract_exercicio) }}"
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
                        value="{{ old('aula_inicio', $aula->aula_inicio ? \Carbon\Carbon::parse($aula->aula_inicio)->format('Y-m-d') : '') }}"
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
                        value="{{ old('aula_fim', $aula->aula_fim ? \Carbon\Carbon::parse($aula->aula_fim)->format('Y-m-d') : '') }}"
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
                    value="{{ old('aula_link', $aula->aula_link) }}"
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
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">{{ old('aula_desc', $aula->aula_desc) }}</textarea>
            </div>

        </div>

        <div class="flex justify-end gap-4 mt-8">
            <a href="{{ route('professor-aulas', Crypt::encrypt($aula->gradeHorario->id_grade)) }}"
                class="px-5 py-2 border rounded-lg hover:bg-gray-100">
                Voltar
            </a>

            <button type="submit"
                class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/pt.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const editarAulaForm = document.getElementById('editarAulaForm');

        const diasPermitidos = editarAulaForm.dataset.diasPermitidos
            ? editarAulaForm.dataset.diasPermitidos.split(',').map(Number).filter(Boolean)
            : [];

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

        const valorInicioOriginal = document.getElementById('aula_inicio').value;
        const valorFimOriginal = document.getElementById('aula_fim').value;

        const inicioPicker = flatpickr('#aula_inicio', {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            allowInput: false,
            defaultDate: valorInicioOriginal || null,
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
            defaultDate: valorFimOriginal || null,
            disable: [
                function(date) {
                    return !diaPermitido(date);
                }
            ],
            onChange: function() {
                document.getElementById('erro_aula_fim').classList.add('hidden');
            }
        });

        if (valorInicioOriginal) {
            fimPicker.set('minDate', valorInicioOriginal);
        }

        window._aulaValidacao = {
            diasPermitidos,
            diasNomes,
            inicioPicker,
            fimPicker
        };
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