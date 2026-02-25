@extends('layouts.dashboard')

@section('title', 'Frequência por Dia')

@section('content')

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('frequencia.listagem') }}" class="hover:text-[#8E251F] transition">
                Frequência
            </a>
        </li>
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

        <form action="{{ route('frequencia.store') }}" method="POST">
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

            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">Aluno</th>
                        <th class="px-4 py-3">Presença</th>
                        <th class="px-4 py-3">Observação</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($grade->matriculas as $matricula)
                    <tr class="border-b hover:bg-red-50 transition">

                        <td class="py-3 px-4 font-semibold">
                            {{ $matricula->aluno->aluno_nome ?? '-' }}
                        </td>

                        <td class="py-3 px-4 text-center">

                            <input type="hidden" name="presenca[{{ $matricula->id_matricula }}]" value="Falta">

                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox"
                                    name="presenca[{{ $matricula->id_matricula }}]"
                                    value="Presente"
                                    class="w-5 h-5 text-green-600 border-gray-300 rounded">

                                <span class="text-sm text-gray-700">Presente</span>
                            </label>

                        </td>

                        <td class="py-3 px-4">
                            <input type="text"
                                name="observacao[{{ $matricula->id_matricula }}]"
                                class="border rounded-lg p-2 w-full"
                                placeholder="Observação (opcional)">
                        </td>


                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-6 text-gray-500">
                            Nenhum aluno matriculado.
                        </td>
                    </tr>
                    @endforelse
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
@if (empty($dias))
<div class="bg-white rounded-2xl shadow-md p-6 text-center text-gray-500">
    Nenhuma frequência registrada ainda.
</div>
@endif

<div class="bg-white rounded-2xl shadow-md p-6">

    <div class="flex justify-center">
        <div class="flex flex-wrap gap-4 items-end justify-center">

            <div>
                <label class="block text-sm font-semibold text-gray-700">
                    Filtrar por Data
                </label>

                <input type="date"
                    id="filtroDia"
                    class="w-full border rounded-lg p-2">
            </div>

            <button id="limparFiltroDia"
                class="h-[40px] px-6 rounded-lg bg-gray-300
                       text-gray-800 font-semibold hover:bg-gray-400
                       transition shadow-md">
                Limpar
            </button>

        </div>
    </div>


    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Data</th>
                <th class="py-3 px-4">Registros</th>
                <th class="py-3 px-4 text-center">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse($dias as $data => $registros)

            <tr class="border-b hover:bg-gray-50 transition linha-dia"
                data-dia="{{ $data }}">
                <td class="py-3 px-4 font-semibold">
                    {{ \Carbon\Carbon::parse($data)->format('d/m/Y') }}
                </td>

                <td class="py-3 px-4">
                    {{ $registros->count() }} registros
                </td>

                <td class="py-3 px-4 text-center">
                    <button type="button"
                        data-id="{{ $data }}"
                        class="btn-ver px-4 py-2 rounded-lg shadow text-white"
                        style="background-color: #174ab9;">
                        Abrir
                    </button>
                </td>
            </tr>

            <!-- ABA OCULTA COM DETALHES -->
            <tr id="detalhe-{{ $data }}" class="hidden bg-gray-50">
                <td colspan="3" class="px-6 py-6">

                    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">

                        <div class="px-4 py-3 bg-gray-100 border-b">
                            <h4 class="text-sm font-semibold text-gray-700">
                                Presenças do dia {{ \Carbon\Carbon::parse($data)->format('d/m/Y') }}
                            </h4>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                                    <tr>
                                        <th class="px-4 py-3">Aluno</th>
                                        <th class="px-4 py-3">Presença</th>
                                        <th class="px-4 py-3">Observação</th>
                                        <th class="px-4 py-3 text-center">Ações</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-100">

                                    @foreach($registros as $registro)

                                    <tr>

                                        <td class="px-4 py-3">
                                            {{ $registro->matricula->aluno->aluno_nome ?? '-' }}
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
                                            <a href="{{ route('frequencia.edit', ['id' => $registro->id_frequencia_aluno]) }}"
                                                style="background-color: #8E251F; color: white;"
                                                class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                                                Editar
                                            </a>
                                        </td>

                                    </tr>

                                    @endforeach

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