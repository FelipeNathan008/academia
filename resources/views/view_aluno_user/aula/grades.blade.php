@extends('layouts.dashboard')

@section('title', 'Minhas Turmas')

@section('content')

<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('aluno.index') }}" class="hover:text-[#8E251F]">
                Meus Alunos
            </a>
        </li>
        <li>/</li>
        <li class="font-semibold text-gray-700">
            Turmas
        </li>
    </ol>
</nav>

<div class="flex justify-between items-center mb-10">

    <h2 class="text-3xl font-extrabold text-gray-800">
        MINHAS TURMAS
    </h2>

    <a href="{{ route('aluno.index') }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">
        ← Voltar
    </a>

</div>

<!-- FILTRO -->
<div class="bg-white rounded-2xl shadow-md p-6 mb-8">
    <div class="flex flex-wrap gap-6 items-end justify-center max-w-6xl mx-auto">

        <div class="flex flex-col w-[600px]">
            <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                Filtrar turmas
            </label>
            <form method="GET" class="flex flex-wrap gap-2 items-end">

                <select name="aluno"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
           focus:ring-2 focus:ring-[#8E251F] focus:outline-none">

                    <option value="">Todos os alunos</option>

                    @foreach($alunos as $aluno)
                    <option value="{{ $aluno }}"
                        {{ request('aluno') == $aluno ? 'selected' : '' }}>
                        {{ $aluno }}
                    </option>
                    @endforeach
                </select>

                <select name="turma"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none">

                    <option value="">Todas as Turmas</option>
                    @foreach($turmas as $turma)
                    <option value="{{ $turma }}" {{ request('turma') == $turma ? 'selected' : '' }}>
                        {{ $turma == 'criancas' ? 'Crianças' : ucfirst($turma) }}
                    </option>
                    @endforeach
                </select>

                <select name="modalidade"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none">

                    <option value="">Todas as Modalidades</option>
                    @foreach($modalidades as $mod)
                    <option value="{{ $mod }}" {{ request('modalidade') == $mod ? 'selected' : '' }}>
                        {{ $mod }}
                    </option>
                    @endforeach
                </select>

                <select name="professor"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none">

                    <option value="">Todos os Professores</option>
                    @foreach($professores as $prof)
                    <option value="{{ $prof }}" {{ request('professor') == $prof ? 'selected' : '' }}>
                        {{ $prof ?? 'Sem professor' }}
                    </option>
                    @endforeach
                </select>

                <button type="submit"
                    class="h-[48px] px-6 rounded-xl bg-[#8E251F] text-white
                           hover:bg-[#732920] transition shadow-md">
                    Buscar
                </button>

                <a href="{{ route('aluno-grades.index') }}"
                    class="h-[48px] px-6 rounded-xl bg-gray-300 text-gray-800
                           flex items-center justify-center
                           hover:bg-gray-400 transition shadow-md">
                    Limpar
                </a>

            </form>
        </div>

    </div>
</div>

<div class="bg-white rounded-2xl shadow-md p-6">

    <h3 class="text-xl font-bold mb-6 text-gray-700 flex items-center gap-4">

        <span>MINHAS TURMAS</span>

        <span class="bg-gray-200 px-3 py-1 rounded-full text-sm">
            Total: {{ $totalMatriculas }}
        </span>

        <span class="bg-gray-200 px-3 py-1 rounded-full text-sm">
            Filtradas: {{ $matriculas->total() }}
        </span>

    </h3>

    <table class="w-full">

        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Aluno</th>
                <th class="py-3 px-4">Professor</th>
                <th class="py-3 px-4">Turma</th>
                <th class="py-3 px-4">Modalidade</th>
                <th class="py-3 px-4">Horário</th>
                <th class="py-3 px-4 text-center">Ações</th>
            </tr>
        </thead>

        <tbody>

            @forelse($matriculas as $matricula)
            <tr class="border-b hover:bg-gray-50">

                <td class="py-3 px-4">
                    {{ $matricula->aluno->aluno_nome }}
                </td>

                <td class="py-3 px-4">
                    {{ $matricula->grade->professor->prof_nome }}
                </td>

                <td class="py-3 px-4">
                    {{ $matricula->grade->grade_turma == 'criancas' ? 'Crianças' : ucfirst($matricula->grade->grade_turma) }}
                </td>

                <td class="py-3 px-4">
                    {{ $matricula->grade->grade_modalidade }}
                </td>

                <td class="py-3 px-4">
                    {{ \Carbon\Carbon::parse($matricula->grade->grade_inicio)->format('H:i') }}
                    às
                    {{ \Carbon\Carbon::parse($matricula->grade->grade_fim)->format('H:i') }}
                </td>

                <td class="py-3 px-4 text-center">

                    <a href="{{ route('aluno-aulas.index', Crypt::encrypt($matricula->grade->id_grade)) }}"
                        class="px-4 py-2 rounded-lg text-white shadow"
                        style="background:#174ab9;">
                        Aulas
                    </a>

                    <a href="{{ route('aluno-frequencia.visualizar', Crypt::encrypt($matricula->id_matricula)) }}"
                        class="px-4 py-2 rounded-lg shadow text-white"
                        style="background:#8E251F;">
                        Frequência
                    </a>

                </td>

            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-6 text-gray-500">
                    Nenhuma turma encontrada.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-6">
        {{ $matriculas->links() }}
    </div>
</div>


@endsection