@extends('layouts.dashboard')

@section('title', 'Frequência por Dia')

@section('content')

<x-alert-error />

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('professor-frequencia') }}" class="hover:text-[#8E251F] transition">
                Frequência
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
        <a href="{{ route('professor-frequencia') }}"
            class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>

        <h2 class="text-3xl font-extrabold text-gray-800">
            Frequência - Dias
        </h2>
    </div>

    <!-- PROFESSOR NÃO CADASTRA (segurança) -->
</div>

<!-- CARD DA GRADE -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#174ab9] rounded-2xl shadow-lg p-6">

        <p class="text-xs uppercase tracking-widest text-gray-500">
            Sua turma
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

                {{ ucfirst($grade->grade_turma) }}
                <span class="text-xs text-gray-500 block">
                    {{ \Carbon\Carbon::parse($grade->grade_inicio)->format('H:i') }}
                    às
                    {{ \Carbon\Carbon::parse($grade->grade_fim)->format('H:i') }}
                </span>
            </div>

        </div>
    </div>
</div>

<!-- LISTAGEM -->
@if ($dias->isEmpty())
<div class="bg-white rounded-2xl shadow-md p-6 text-center text-gray-500">
    Nenhuma frequência registrada ainda.
</div>
@endif

<div class="bg-white rounded-2xl shadow-md p-6">

    <!-- FILTRO -->
    <div class="flex justify-center mb-6">
        <div class="flex gap-4 items-end">
            <div>
                <label class="block text-sm font-semibold text-gray-700">
                    Filtrar por Data
                </label>

                <input type="date" id="filtroDia"
                    class="border rounded-lg p-2">
            </div>

            <button id="limparFiltroDia"
                class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
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

            <tr class="border-b linha-dia" data-dia="{{ $data }}">
                <td class="py-3 px-4 font-semibold">
                    {{ \Carbon\Carbon::parse($data)->format('d/m/Y') }}
                </td>

                <td class="py-3 px-4">
                    {{ $registros->count() }} registros
                </td>

                <td class="py-3 px-4 text-center">
                    <button type="button"
                        data-id="{{ $data }}"
                        class="btn-ver px-4 py-2 rounded-lg text-white bg-blue-600">
                        Ver
                    </button>
                </td>
            </tr>

            <!-- DETALHES -->
            <tr id="detalhe-{{ $data }}" class="hidden bg-gray-50">
                <td colspan="3" class="px-6 py-6">

                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 text-xs uppercase">
                            <tr>
                                <th class="px-4 py-2">Aluno</th>
                                <th class="px-4 py-2">Presença</th>
                                <th class="px-4 py-2">Observação</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($registros as $registro)
                            <tr class="border-b">

                                <td class="px-4 py-2">
                                    {{ $registro->matricula->aluno->aluno_nome ?? '-' }}
                                </td>

                                <td class="px-4 py-2">
                                    @if($registro->freq_presenca == 'Presente')
                                    <span class="text-green-600 font-semibold">Presente</span>
                                    @else
                                    <span class="text-red-600 font-semibold">Falta</span>
                                    @endif
                                </td>

                                <td class="px-4 py-2">
                                    {{ $registro->freq_observacao ?? '-' }}
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>

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
    document.addEventListener("DOMContentLoaded", function() {

        const filtroDia = document.getElementById('filtroDia');
        const limpar = document.getElementById('limparFiltroDia');
        const linhas = document.querySelectorAll('.linha-dia');

        function aplicarFiltro() {
            const dia = filtroDia.value;

            linhas.forEach(linha => {
                linha.style.display =
                    (!dia || linha.dataset.dia === dia) ? '' : 'none';
            });
        }

        filtroDia?.addEventListener('change', aplicarFiltro);

        limpar?.addEventListener('click', () => {
            filtroDia.value = '';
            aplicarFiltro();
        });

        let aberto = null;

        document.querySelectorAll(".btn-ver").forEach(btn => {
            btn.addEventListener("click", function() {

                const id = this.dataset.id;
                const detalhe = document.getElementById("detalhe-" + id);

                if (aberto && aberto !== id) {
                    document.getElementById("detalhe-" + aberto)?.classList.add("hidden");
                }

                detalhe.classList.toggle("hidden");
                aberto = detalhe.classList.contains("hidden") ? null : id;
            });
        });

    });
</script>

@endsection