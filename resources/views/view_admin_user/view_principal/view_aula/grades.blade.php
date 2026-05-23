@extends('layouts.dashboard')

@section('title', 'Grades')

@section('content')

<div class="bg-white rounded-2xl shadow-md p-6">

    <h2 class="text-3xl font-extrabold text-gray-800 mb-8">
        Grades Disponíveis
    </h2>

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

            <tr class="border-b hover:bg-gray-50 transition">

                <td class="py-3 px-4">
                    {{ $grade->professor->prof_nome ?? '-' }}
                </td>

                <td class="py-3 px-4">
                    {{ $grade->grade_modalidade }}
                </td>

                <td class="py-3 px-4">
                    {{ $grade->grade_dia_semana }}
                </td>

                <td class="py-3 px-4">
                    {{ $grade->grade_inicio }} às {{ $grade->grade_fim }}
                </td>

                <td class="py-3 px-4">
                    {{ $grade->grade_turma }}
                </td>

                <td class="py-3 px-4">

                    <a href="{{ route('aulas', Crypt::encrypt($grade->id_grade)) }}"
                        style="background-color:#174ab9; color:white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
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

@endsection