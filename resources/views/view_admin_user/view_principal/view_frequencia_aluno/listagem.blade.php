@extends('layouts.dashboard')

@section('title', 'Turmas para Frequência')

@section('content')

<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.principal') }}"
            class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>
        <h2 class="text-3xl font-extrabold text-gray-800">Turmas para frequência e aulas</h2>
    </div>
</div>

<!-- FILTRO -->
<div class="bg-white rounded-2xl shadow-md p-6 mb-8">
    <div class="flex flex-wrap gap-6 items-end justify-center max-w-6xl mx-auto">

        <div class="flex flex-col w-[600px]">
            <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                Filtrar turmas
            </label>
            <form method="GET" class="flex flex-wrap gap-2 items-end">

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

                <a href="{{ route('frequencia.listagem') }}"
                    class="h-[48px] px-6 rounded-xl bg-gray-300 text-gray-800
                           flex items-center justify-center
                           hover:bg-gray-400 transition shadow-md">
                    Limpar
                </a>

            </form>
        </div>

    </div>
</div>

<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">

    <h3 class="text-xl font-bold mb-6 text-gray-700 flex items-center gap-4 flex-wrap">
        <span>TURMAS CADASTRADAS</span>

        <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm">
            Total: {{ $totalGrades }}
        </span>

        <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm">
            Filtrados: {{ $grades->total() }}
        </span>
    </h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Professor</th>
                <th class="py-3 px-4">Turma</th>
                <th class="py-3 px-4">Modalidade</th>
                <th class="py-3 px-4">Horário</th>
                <th class="py-3 px-4">Matrículas</th>
                <th class="py-3 px-4 text-center">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse($grades as $grade)
            <tr class="border-b hover:bg-gray-50 transition">

                <td class="py-3 px-4 font-semibold">
                    {{ $grade->professor->prof_nome ?? '-' }}
                </td>

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

                        <a href="{{ route('aulas', Crypt::encrypt($grade->id_grade)) }}"
                            style="background-color:#174ab9; color:white;"
                            class="px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition duration-200 inline-block text-center">
                            Aulas
                        </a>

                        <a href="{{ route('frequencia.dias', Crypt::encrypt($grade->id_grade)) }}"
                            class="px-4 py-2 rounded-lg shadow text-white"
                            style="background-color: #174ab9;">
                            Frequência
                        </a>

                        <a href="{{ route('frequencia.visualizar', Crypt::encrypt($grade->id_grade)) }}"
                            class="px-4 py-2 rounded-lg shadow text-white"
                            style="background-color: #8E251F;">
                            Relatório
                        </a>

                    </div>
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
        {{ $grades->links() }}
    </div>

</div>

@endsection