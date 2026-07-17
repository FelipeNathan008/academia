@extends('layouts.dashboard')

@section('title', 'Turmas para Frequência')

@section('content')

<!-- TOPO -->
<div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-extrabold text-gray-800">
        Minhas Turmas para Frequência
    </h2>

</div>

<div class="bg-white rounded-2xl shadow-md p-6">

    <!-- FILTROS -->
    <div class="flex justify-center">
        <div class="flex flex-wrap gap-4 items-end justify-center">

            <!-- TURMA -->
            <div class="flex flex-col w-[220px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Turma
                </label>

                <select id="filtroTurma"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">

                    <option value="">Todas</option>
                    @foreach($grades->pluck('grade_turma')->unique() as $turma)
                    <option value="{{ strtolower($turma) }}">
                        {{ $turma == 'criancas' ? 'Crianças' : ucfirst($turma) }}
                    </option>
                    @endforeach

                </select>
            </div>

            <!-- MODALIDADE -->
            <div class="flex flex-col w-[200px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Modalidade
                </label>

                <select id="filtroModalidade"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">

                    <option value="">Todas</option>
                    @foreach($grades->pluck('grade_modalidade')->unique() as $mod)
                    <option value="{{ strtolower($mod) }}">
                        {{ $mod }}
                    </option>
                    @endforeach

                </select>
            </div>

            <!-- LIMPAR -->
            <button id="limparFiltro"
                class="h-[48px] px-6 rounded-xl bg-gray-300
                       text-gray-800 font-semibold hover:bg-gray-400
                       transition shadow-md">
                Limpar filtro
            </button>

        </div>
    </div>

    <!-- TABELA -->
    <table class="w-full text-left border-collapse mt-6">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Turma</th>
                <th class="py-3 px-4">Modalidade</th>
                <th class="py-3 px-4">Horário</th>
                <th class="py-3 px-4">Matrículas</th>
                <th class="py-3 px-4 text-center">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse($grades as $grade)
            <tr class="border-b hover:bg-gray-50 transition linha-grade"
                data-turma="{{ strtolower($grade->grade_turma) }}"
                data-modalidade="{{ strtolower($grade->grade_modalidade ?? '') }}">

                <td class="py-3 px-4">
                    {{ $grade->grade_turma == 'criancas' ? 'Crianças' : ucfirst($grade->grade_turma) }}
                </td>

                <td class="py-3 px-4">
                    {{ $grade->grade_modalidade ?? '-' }}
                </td>

                <td class="py-3 px-4">
                    {{ \Carbon\Carbon::parse($grade->grade_inicio)->format('H:i') }}
                    às
                    {{ \Carbon\Carbon::parse($grade->grade_fim)->format('H:i') }}
                </td>

                <td class="py-3 px-4">
                    {{ $grade->matriculas->count() }}
                </td>

                <td class="py-3 px-4 text-center">
                    <div class="flex justify-center gap-2">

                        <a href="{{ route('professor-aulas', Crypt::encrypt($grade->id_grade)) }}"
                            style="background-color:#174ab9; color:white;"
                            class="px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition duration-200 inline-block text-center">
                            Aulas
                        </a>


                        <a href="{{ route('professor-frequencia.dias', Crypt::encrypt($grade->id_grade)) }}"
                            class="px-4 py-2 rounded-lg shadow text-white"
                            style="background-color: #15367c;">
                            Frequência
                        </a>

                        <a href="{{ route('professor-frequencia.relatorio', Crypt::encrypt($grade->id_grade)) }}"
                            class="px-4 py-2 rounded-lg shadow text-white"
                            style="background-color: #8E251F;">
                            Relatório
                        </a>

                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-6 text-gray-500">
                    Nenhuma turma encontrada.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>

<!-- SCRIPT FILTRO -->
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const filtroTurma = document.getElementById('filtroTurma');
        const filtroModalidade = document.getElementById('filtroModalidade');
        const limpar = document.getElementById('limparFiltro');
        const linhas = document.querySelectorAll('.linha-grade');

        function aplicarFiltro() {
            const turma = filtroTurma.value;
            const modalidade = filtroModalidade.value;

            linhas.forEach(linha => {
                const t = linha.dataset.turma || '';
                const m = linha.dataset.modalidade || '';
                let mostrar = true;

                if (turma && t !== turma) mostrar = false;
                if (modalidade && m !== modalidade) mostrar = false;

                linha.style.display = mostrar ? '' : 'none';
            });
        }

        if (filtroTurma) filtroTurma.addEventListener('change', aplicarFiltro);
        if (filtroModalidade) filtroModalidade.addEventListener('change', aplicarFiltro);

        if (limpar) {
            limpar.addEventListener('click', function() {
                filtroTurma.value = '';
                filtroModalidade.value = '';
                aplicarFiltro();
            });
        }

    });
</script>

@endsection