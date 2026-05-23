{{-- resources/views/view_aulas/index.blade.php --}}

@extends('layouts.dashboard')

@section('title', 'Aulas')

@section('content')

<x-alert-error />

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('professores') }}" class="hover:text-[#8E251F] transition">
                Professores
            </a>
        </li>
        <li>/</li>
        <li class="text-gray-400">{{ $grade->professor->prof_nome }}</li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Aulas</li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('professores') }}"
            class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>

        <h2 class="text-3xl font-extrabold text-gray-800">
            Aulas do Professor
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
        <p class="text-xs uppercase tracking-widest text-gray-500">Professor selecionado</p>

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

<!-- FORMULÁRIO -->
<div id="cadastroForm" class="hidden mb-10">

    <form action="{{ route('aulas.store', Crypt::encrypt($grade->id_grade)) }}"
        method="POST"
        onsubmit="bloquearSubmit(event, this)">

        @csrf

        <div class="bg-white rounded-2xl shadow-md p-8">

            <h3 class="text-xl font-bold mb-6 text-gray-700">
                Cadastrar Aula
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- GRADE SELECIONADA -->
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-600">
                        Grade Selecionada
                    </label>

                    <div class="w-full border rounded-lg px-4 py-3 mt-1 bg-gray-100 text-gray-700">
                        @php
                        $dias = [
                        1 => 'Domingo',
                        2 => 'Segunda',
                        3 => 'Terça',
                        4 => 'Quarta',
                        5 => 'Quinta',
                        6 => 'Sexta',
                        7 => 'Sábado'
                        ];

                        $diasSelecionados = collect(explode(',', $grade->grade_dia_semana))
                        ->map(fn($dia) => $dias[(int)$dia] ?? $dia)
                        ->implode(' e ');
                        @endphp

                        {{ $grade->grade_modalidade }}
                        -
                        {{ $diasSelecionados }}
                        -
                        {{ \Carbon\Carbon::parse($grade->grade_inicio)->format('H:i') }}
                        às
                        {{ \Carbon\Carbon::parse($grade->grade_fim)->format('H:i') }}
                    </div>
                </div>

                <!-- POSIÇÃO -->
                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Posição Ensino
                    </label>

                    <input type="text"
                        name="aula_posicao_ensino"
                        maxlength="150"
                        required
                        placeholder="Ex: Iniciante"
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                </div>

                <!-- DATA INICIAL -->
                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Período Inicial
                    </label>

                    <input type="date"
                        name="aula_periodo_inicial"
                        required
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                </div>

                <!-- DATA FINAL -->
                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Período Final
                    </label>

                    <input type="date"
                        name="aula_periodo_final"
                        required
                        class="w-full border rounded-lg px-4 py-2 mt-1">
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

    <table class="w-full text-left border-collapse">

        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Posição</th>
                <th class="py-3 px-4">Modalidade</th>
                <th class="py-3 px-4">Dia</th>
                <th class="py-3 px-4">Período</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>

            @forelse ($aulas as $aula)

            <tr class="border-b hover:bg-gray-50 transition">

                <td class="py-3 px-4">
                    {{ $aula->aula_posicao_ensino }}
                </td>

                <td class="py-3 px-4">
                    {{ $aula->gradeHorario->grade_modalidade ?? '-' }}
                </td>

                <td class="py-3 px-4">
                    {{ $aula->gradeHorario->grade_dia_semana ?? '-' }}
                </td>

                <td class="py-3 px-4">
                    {{ \Carbon\Carbon::parse($aula->aula_periodo_inicial)->format('d/m/Y') }}
                    até
                    {{ \Carbon\Carbon::parse($aula->aula_periodo_final)->format('d/m/Y') }}
                </td>

                <td class="py-3 px-4 flex gap-2">

                    <a href="{{ route('aulas.edit', Crypt::encrypt($aula->id_aula)) }}"
                        style="background-color: #8E251F; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                        Editar
                    </a>

                    <form action="{{ route('aulas.destroy', Crypt::encrypt($aula->id_aula)) }}"
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
                    Nenhuma aula cadastrada para este professor
                </td>
            </tr>

            @endforelse

        </tbody>

    </table>
</div>

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
</script>

@endsection