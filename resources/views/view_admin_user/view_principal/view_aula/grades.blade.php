@extends('layouts.dashboard')

@section('title', 'Grades')

@section('content')
<x-alert-error />

<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.principal') }}"
            class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>
        <h2 class="text-3xl font-extrabold text-gray-800">Turmas para aula</h2>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-md p-6"
    id="containerGrades"
    data-professor-inicial="{{ $professorFiltroNome ? strtolower($professorFiltroNome) : '' }}">

    <h2 class="text-3xl font-extrabold text-gray-800 mb-8">
        Grades Disponíveis
    </h2>

    <!-- FILTROS -->
    <div class="flex justify-center mb-8">
        <div class="flex flex-wrap gap-4 items-end justify-center">

            <!-- Professor -->
            <div class="flex flex-col w-[220px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Professor
                </label>

                <select id="filtroProfessor"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">

                    <option value="">Todos</option>
                    @foreach($grades->pluck('professor.prof_nome')->filter()->unique() as $prof)
                    <option value="{{ strtolower($prof) }}">
                        {{ $prof }}
                    </option>
                    @endforeach

                </select>
            </div>

            <!-- Modalidade -->
            <div class="flex flex-col w-[200px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Modalidade
                </label>

                <select id="filtroModalidade"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">

                    <option value="">Todas</option>
                    @foreach($grades->pluck('grade_modalidade')->filter()->unique() as $mod)
                    <option value="{{ strtolower($mod) }}">
                        {{ $mod }}
                    </option>
                    @endforeach

                </select>
            </div>

            <!-- Turma -->
            <div class="flex flex-col w-[220px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Turma
                </label>

                <select id="filtroTurma"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">

                    <option value="">Todas</option>
                    @foreach($grades->pluck('grade_turma')->filter()->unique() as $turma)
                    <option value="{{ strtolower($turma) }}">
                        {{ $turma == 'criancas' ? 'Crianças' : ucfirst($turma) }}
                    </option>
                    @endforeach

                </select>
            </div>

            <!-- Limpar -->
            <button id="limparFiltro"
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
                <th class="py-3 px-4">Professor</th>
                <th class="py-3 px-4">Modalidade</th>
                <th class="py-3 px-4">Dia</th>
                <th class="py-3 px-4">Horário</th>
                <th class="py-3 px-4">Turma</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>

            @forelse($grades as $grade)

            <tr class="border-b hover:bg-gray-50 transition linha-grade"
                data-professor="{{ strtolower($grade->professor->prof_nome ?? '') }}"
                data-modalidade="{{ strtolower($grade->grade_modalidade ?? '') }}"
                data-turma="{{ strtolower($grade->grade_turma ?? '') }}">

                <td class="py-3 px-4 font-semibold">
                    {{ $grade->professor->prof_nome ?? '-' }}
                </td>

                <td class="py-3 px-4">
                    {{ $grade->grade_modalidade ?? '-' }}
                </td>

                <td class="py-3 px-4">
                    @php
                    $dias = [
                    1 => 'Domingo',
                    2 => 'Segunda',
                    3 => 'Terça',
                    4 => 'Quarta',
                    5 => 'Quinta',
                    6 => 'Sexta',
                    7 => 'Sábado',
                    ];

                    $diasGrade = $grade->grade_dia_semana
                    ? collect(explode(',', $grade->grade_dia_semana))
                    ->map(fn($dia) => $dias[(int) $dia] ?? $dia)
                    ->implode(' e ')
                    : '-';
                    @endphp

                    {{ $diasGrade }}
                </td>

                <td class="py-3 px-4">
                    @if($grade->grade_inicio && $grade->grade_fim)
                    {{ \Carbon\Carbon::parse($grade->grade_inicio)->format('H:i') }}
                    às
                    {{ \Carbon\Carbon::parse($grade->grade_fim)->format('H:i') }}
                    @else
                    -
                    @endif
                </td>

                <td class="py-3 px-4">
                    {{ $grade->grade_turma == 'criancas' ? 'Crianças' : ($grade->grade_turma ?? '-') }}
                </td>

                <td class="py-3 px-4">

                    <a href="{{ route('aulas', Crypt::encrypt($grade->id_grade)) }}"
                        style="background-color:#174ab9; color:white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition duration-200 inline-block text-center">
                        Aulas
                    </a>

                </td>

            </tr>

            @empty

            <tr>
                <td colspan="6"
                    class="text-center py-6 text-gray-500">
                    Nenhuma grade cadastrada
                </td>
            </tr>

            @endforelse

        </tbody>

    </table>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const filtroProfessor = document.getElementById('filtroProfessor');
        const filtroModalidade = document.getElementById('filtroModalidade');
        const filtroTurma = document.getElementById('filtroTurma');
        const limpar = document.getElementById('limparFiltro');
        const linhas = document.querySelectorAll('.linha-grade');

        function aplicarFiltro() {
            const professor = filtroProfessor.value;
            const modalidade = filtroModalidade.value;
            const turma = filtroTurma.value;

            linhas.forEach(linha => {
                const p = linha.dataset.professor || '';
                const m = linha.dataset.modalidade || '';
                const t = linha.dataset.turma || '';
                let mostrar = true;

                if (professor && p !== professor) {
                    mostrar = false;
                }

                if (modalidade && m !== modalidade) {
                    mostrar = false;
                }

                if (turma && t !== turma) {
                    mostrar = false;
                }

                linha.style.display = mostrar ? '' : 'none';
            });
        }

        if (filtroProfessor) filtroProfessor.addEventListener('change', aplicarFiltro);
        if (filtroModalidade) filtroModalidade.addEventListener('change', aplicarFiltro);
        if (filtroTurma) filtroTurma.addEventListener('change', aplicarFiltro);

        if (limpar) {
            limpar.addEventListener('click', function() {
                filtroProfessor.value = '';
                filtroModalidade.value = '';
                filtroTurma.value = '';
                aplicarFiltro();
            });
        }

        // Aplica filtro de professor vindo da URL
        // (ex: clique em "Aulas" na tela de Professores)
        const containerGrades = document.getElementById('containerGrades');
        const professorInicial = containerGrades.dataset.professorInicial;

        if (professorInicial && filtroProfessor) {

            const opcaoExiste = Array.from(filtroProfessor.options)
                .some(opt => opt.value === professorInicial);

            if (opcaoExiste) {
                filtroProfessor.value = professorInicial;
                aplicarFiltro();
            }
        }

    });
</script>

@endsection 