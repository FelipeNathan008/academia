@extends('layouts.dashboard')

@section('title', 'Frequência por Dia')

@section('content')

<x-alert-error />

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('frequencia.listagem') }}" class="hover:text-[#8E251F] transition">
                Frequência e Aulas
            </a>
        </li>
        <li>/</li>
        <li class="text-gray-400">{{ $grade->professor->prof_nome }}</li>
        <li>/</li>
        <li class="font-semibold text-gray-700">
            Dias Registrados
        </li>
    </ol>
</nav>
<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('frequencia.listagem') }}"
            class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>

        <h2 class="text-3xl font-extrabold text-gray-800">
            Frequência - Dias
        </h2>
    </div>

    <!-- BOTÃO CADASTRAR (abre aba abaixo) -->
    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] transition">
        + Cadastrar Frequência
    </button>
</div>

<!-- ABA DE CADASTRO (OCULTA) -->
<div id="cadastroForm" class="hidden mb-10">

    <div class="bg-white rounded-2xl shadow-md p-6">

        <h3 class="text-xl font-bold mb-6 text-gray-700">
            Registrar Frequência
        </h3>

        <form action="{{ route('frequencia.store') }}" method="POST" onsubmit="bloquearSubmit(event, this)">
            @csrf

            <input type="hidden" name="grade_id" value="{{ $grade->id_grade }}">

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700">
                    Data da Aula
                </label>
                <input type="date" name="data_aula"
                    class="w-full border rounded-lg p-2"
                    required>
            </div>

            <table class="w-full text-left border-collapse border border-black">
                <thead class="bg-black text-white">
                    <tr class="border-b border-black text-sm">
                        <th class="py-3 px-4 border-r border-black">Data</th>
                        <th class="py-3 px-4 border-r border-black">Registros</th>
                        <th class="py-3 px-4 text-center">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($grade->matriculas as $matricula)
                    <tr class="border-b hover:bg-red-50 transition">
                        <td class="py-3 px-4 font-semibold border border-black">
                            <div class="flex items-center gap-2">
                                <span>
                                    {{ $matricula->aluno->aluno_nome ?? '-' }}
                                </span>
                            </div>
                        </td>

                        <td class="py-3 px-4 text-center">
                            <input type="hidden"
                                name="presenca_aluno[{{ $matricula->id_matricula }}]"
                                value="Falta">

                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox"
                                    name="presenca_aluno[{{ $matricula->id_matricula }}]"
                                    value="Presente"
                                    checked
                                    class="w-5 h-5 text-green-600 border-gray-300 rounded">

                                <span class="text-sm text-gray-700">
                                    Presente
                                </span>
                            </label>
                        </td>

                        <td class="py-3 px-4 border border-black">
                            <input type="text"
                                name="observacao_aluno[{{ $matricula->id_matricula }}]"
                                class="border rounded-lg p-2 w-full"
                                placeholder="Observação (opcional)">
                        </td>
                    </tr>
                    @empty
                    @endforelse

                    @forelse($grade->visitanteGrades as $visitanteGrade)
                    <tr class="border-b hover:bg-purple-50 transition">
                        <td class="py-3 px-4 font-semibold">
                            <div class="flex items-center gap-2">
                                <span>
                                    {{ $visitanteGrade->visitante->vis_nome ?? '-' }}
                                </span>

                                <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-700">
                                    Visitante
                                </span>
                            </div>
                        </td>

                        <td class="py-3 px-4 text-center border border-black">|
                            <input type="hidden"
                                name="presenca_visitante[{{ $visitanteGrade->id_visitante_grade }}]"
                                value="Falta">

                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox"
                                    name="presenca_visitante[{{ $visitanteGrade->id_visitante_grade }}]"
                                    value="Presente"
                                    checked
                                    class="w-5 h-5 text-green-600 border-gray-300 rounded">

                                <span class="text-sm text-gray-700">
                                    Presente
                                </span>
                            </label>
                        </td>

                        <td class="py-3 px-4">
                            <input type="text"
                                name="observacao_visitante[{{ $visitanteGrade->id_visitante_grade }}]"
                                class="border rounded-lg p-2 w-full"
                                placeholder="Observação (opcional)">
                        </td>
                    </tr>
                    @empty
                    @endforelse

                    @if($grade->matriculas->isEmpty() && $grade->visitanteGrades->isEmpty())
                    <tr>
                        <td colspan="3" class="text-center py-6 text-gray-500">
                            Nenhum aluno ou visitante ativo vinculado a esta grade.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                <button type="button" onclick="fecharCadastro()"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-100">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-[#8E251F] text-white rounded-lg">
                    Salvar
                </button>
            </div>

        </form>

    </div>
</div>

<!-- CARD DA GRADE -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#174ab9] rounded-2xl shadow-lg p-6">
        <p class="text-xs uppercase tracking-widest text-gray-500">
            Grade selecionada
        </p>

        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
            {{ $grade->grade_modalidade ?? 'Modalidade não definida' }}
        </h3>

        <div class="mt-4 space-y-3 text-sm text-gray-600">

            <div>
                <span class="font-semibold text-gray-800">Professor:</span><br>
                {{ $grade->professor->prof_nome ?? '-' }}
            </div>

            <div>
                <span class="font-semibold text-gray-800">Horário:</span><br>

                @if($grade)
                {{ ucfirst($grade->grade_turma) }}
                <span class="text-xs text-gray-500 block">
                    {{ \Carbon\Carbon::parse($grade->grade_inicio)->format('H:i') }}
                    às
                    {{ \Carbon\Carbon::parse($grade->grade_fim)->format('H:i') }}
                </span>
                @else
                -
                @endif
            </div>

        </div>
    </div>
</div>

<!-- LISTAGEM DE DIAS -->
@if($dias->isEmpty())
<div class="bg-white rounded-2xl shadow-md p-6 text-center text-gray-500">
    Nenhuma frequência registrada ainda.
</div>
@endif

<div class="bg-white rounded-2xl shadow-md p-6">

    <form method="GET" action="{{ route('frequencia.dias', Crypt::encrypt($grade->id_grade)) }}"
        class="mb-6 flex justify-center">
        <div class="flex flex-wrap gap-4 items-end justify-center">

            <div>
                <label class="block text-sm font-semibold text-gray-700">
                    Filtrar por Data
                </label>

                <input type="date"
                    name="data"
                    value="{{ $filtroData ?? '' }}"
                    class="w-full border rounded-lg p-2">
            </div>

            <button type="submit"
                class="h-[48px] px-6 rounded-xl bg-[#8E251F] text-white hover:bg-[#732920] transition shadow-md">
                Filtrar
            </button>

            <a href="{{ route('frequencia.dias', Crypt::encrypt($grade->id_grade)) }}"
                class="h-[40px] px-6 rounded-lg bg-gray-300 text-gray-800 font-semibold hover:bg-gray-400 transition shadow-md flex items-center">
                Limpar
            </a>

        </div>
    </form>

    <table class="w-full text-left border-collapse border border-black">
        <thead class="bg-black text-white">
            <tr class="border-b border-black text-sm">
                <th class="py-3 px-4 border-r border-black">Data</th>
                <th class="py-3 px-4 border-r border-black">Registros</th>
                <th class="py-3 px-4 text-center">Ações</th>
            </tr>
        </thead>

        <h3 class="text-xl font-bold mb-6 text-gray-700 flex items-center gap-4 flex-wrap">
            <span>FREQUÊNCIAS CADASTRADAS</span>
        </h3>

        <tbody class="divide-y divide-gray-100">

            @forelse($dias as $data => $registros)

            <tr class="border-b hover:bg-gray-50 transition linha-dia"
                data-dia="{{ $data }}">

                <td class="py-3 px-4 font-semibold border border-black">
                    {{ \Carbon\Carbon::parse($data)->format('d/m/Y') }}
                </td>

                <td class="py-3 px-4 border border-black">
                    {{ $registros->count() }} registros
                </td>

                <td class="py-3 px-4 text-center border border-black">
                    <div class="flex justify-center gap-2">

                        <button type="button"
                            data-id="{{ $data }}"
                            class="btn-ver px-4 py-2 rounded-lg shadow text-white"
                            style="background-color: #174ab9;">
                            Abrir
                        </button>

                        <a href="{{ route('frequencia.relatorio.pdf', ['data' => $data]) }}"
                            target="_blank"
                            class="px-4 py-2 rounded-lg shadow text-white"
                            style="background-color: #8E251F;">
                            Relatório
                        </a>

                        <button type="button"
                            data-edit="{{ $data }}"
                            class="btn-editar px-4 py-2 rounded-lg shadow text-white"
                            style="background-color: #ca8a04;">
                            Editar
                        </button>

                    </div>
                </td>
            </tr>

            <!-- EDITAR DATA -->
            <tr id="editar-dia-{{ $data }}" class="hidden bg-yellow-50">
                <td colspan="3" class="px-6 py-6">

                    <form action="{{ route('frequencia.alterarData') }}" method="POST"
                        onsubmit="return confirm('Alterar a data de TODOS os registros deste dia?')">

                        @csrf
                        @method('PUT')

                        <input type="hidden" name="grade_id" value="{{ $grade->id_grade }}">
                        <input type="hidden" name="data_atual" value="{{ $data }}">

                        <div class="flex flex-col md:flex-row md:items-end gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Data Atual
                                </label>

                                <div class="px-3 py-2 border rounded-md bg-gray-50 text-gray-700 text-sm">
                                    {{ \Carbon\Carbon::parse($data)->format('d/m/Y') }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nova Data
                                </label>

                                <input type="date"
                                    name="nova_data"
                                    class="px-3 py-2 border rounded-md text-sm"
                                    required>
                            </div>

                            <div>
                                <button type="submit"
                                    class="px-4 py-2 text-xs font-semibold text-white rounded-lg shadow"
                                    style="background-color: #15803d;">
                                    Confirmar
                                </button>
                            </div>

                        </div>

                    </form>

                </td>
            </tr>

            <!-- DETALHES DO DIA -->
            <tr id="detalhe-{{ $data }}" class="hidden bg-gray-50">
                <td colspan="3" class="px-6 py-6">

                    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">

                        <div class="px-4 py-3 bg-gray-100 border-b">
                            <h4 class="text-sm font-semibold text-gray-700">
                                Presenças do dia {{ \Carbon\Carbon::parse($data)->format('d/m/Y') }}
                            </h4>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left border-collapse border border-black">
                                <thead class="bg-black text-white uppercase text-xs">
                                    <tr>
                                        <th class="px-4 py-3">Aluno / Visitante</th>
                                        <th class="px-4 py-3">Presença</th>
                                        <th class="px-4 py-3">Observação</th>
                                        <th class="py-3 px-4 text-center">Ações</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-100">

                                    @forelse($registros as $registro)

                                    <tr>

                                        <td class="px-4 py-3">
                                            @if($registro->matricula)

                                            <div class="flex items-center gap-2">
                                                <span>
                                                    {{ $registro->matricula->aluno->aluno_nome ?? '-' }}
                                                </span>

                                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                                                    Aluno
                                                </span>
                                            </div>

                                            @elseif($registro->visitanteGrade)

                                            <div class="flex items-center gap-2">
                                                <span>
                                                    {{ $registro->visitanteGrade->visitante->vis_nome ?? '-' }}
                                                </span>

                                                <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-700">
                                                    Visitante
                                                </span>
                                            </div>

                                            @else
                                            -
                                            @endif
                                        </td>

                                        <td class="px-4 py-3">
                                            @if($registro->freq_presenca == 'Presente')

                                            <span style="padding:2px 8px; font-size:0.75rem;
                                                font-weight:600; border-radius:9999px;
                                                color:#166534; background-color:#bbf7d0;">
                                                Presente
                                            </span>

                                            @else

                                            <span style="padding:2px 8px; font-size:0.75rem;
                                                font-weight:600; border-radius:9999px;
                                                color:#b91c1c; background-color:#fee2e2;">
                                                Falta
                                            </span>

                                            @endif
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $registro->freq_observacao ?? '-' }}
                                        </td>

                                        <td class="px-4 py-3 text-center">

                                            <a href="{{ route('frequencia.edit', Crypt::encrypt($registro->id_frequencia_aluno)) }}"
                                                style="background-color: #8E251F; color: white;"
                                                class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                                                Editar
                                            </a>

                                        </td>

                                    </tr>

                                    @empty

                                    <tr>
                                        <td colspan="4" class="text-center py-6 text-gray-500">
                                            Nenhum registro encontrado.
                                        </td>
                                    </tr>

                                    @endforelse

                                </tbody>
                            </table>
                        </div>

                    </div>

                </td>
            </tr>

            @empty

            <tr>
                <td colspan="3" class="text-center py-6 text-gray-500">
                    Nenhum dia registrado.
                </td>
            </tr>

            @endforelse

        </tbody>
    </table>


    <x-pagination-compact :paginator="$datasPaginadas" />

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

    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', function() {

            const id = this.getAttribute('data-edit');
            const linha = document.getElementById('editar-dia-' + id);

            linha.classList.toggle('hidden');

        });
    });

    function bloquearSubmit(event, form) {
        if (!form.checkValidity()) {
            return; // deixa validação normal do HTML
        }
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Salvando...';
        }
    }
    document.addEventListener("DOMContentLoaded", function() {

        // FILTRO POR DATA
        const filtroDia = document.getElementById('filtroDia');
        const limpar = document.getElementById('limparFiltroDia');
        const linhas = document.querySelectorAll('.linha-dia');

        function aplicarFiltroDia() {
            const dia = filtroDia.value;

            linhas.forEach(linha => {
                const dataLinha = linha.dataset.dia || '';
                let mostrar = true;

                if (dia && dataLinha !== dia) {
                    mostrar = false;
                }

                linha.style.display = mostrar ? '' : 'none';
            });
        }

        if (filtroDia) {
            filtroDia.addEventListener('change', aplicarFiltroDia);
        }

        if (limpar) {
            limpar.addEventListener('click', function() {
                filtroDia.value = '';
                aplicarFiltroDia();
            });
        }

        // TOGGLE DETALHES
        let abertoId = null;

        document.querySelectorAll(".btn-ver").forEach(botao => {
            botao.addEventListener("click", function() {

                const id = this.dataset.id;
                const detalhe = document.getElementById("detalhe-" + id);

                if (abertoId && abertoId !== id) {
                    const abertoElemento = document.getElementById("detalhe-" + abertoId);
                    if (abertoElemento) abertoElemento.classList.add("hidden");
                }

                detalhe.classList.toggle("hidden");
                abertoId = detalhe.classList.contains("hidden") ? null : id;
            });
        });

    });
</script>

@endsection