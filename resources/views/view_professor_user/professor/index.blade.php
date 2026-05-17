@extends('layouts.dashboard')

@section('title', 'Meus Alunos')

@section('content')

<!-- TOPO -->
<div class="flex justify-between items-center mb-10">
    <h2 class="text-3xl font-extrabold text-gray-800">
        Professor / Meus Alunos
    </h2>
</div>

<!-- CARD -->
<div class="bg-white rounded-2xl shadow-md p-6">

    <h3 class="text-xl font-bold mb-6 text-gray-700">
        DADOS DO PROFESSOR
    </h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Nome</th>
                <th class="py-3 px-4">Empresa</th>
                <th class="py-3 px-4">Foto</th>
                <th class="py-3 px-4">Qtd. Alunos</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            <tr class="border-b">

                <!-- NOME -->
                <td class="py-3 px-4 font-medium">
                    {{ $professor->prof_nome }}
                </td>

                <!-- EMPRESA -->
                <td class="py-3 px-4 font-bold">
                    {{ $professor->empresas->emp_nome ?? '-' }}
                </td>

                <!-- FOTO -->
                <td class="py-3 px-4">
                    @if($professor->prof_foto)
                    <div class="w-12 h-12 overflow-hidden rounded-lg">
                        <img src="{{ asset('images/professores/' . $professor->prof_foto) }}"
                            alt="Foto"
                            style="width:48px; height:48px; object-fit:cover;">
                    </div>
                    @else
                    -
                    @endif
                </td>

                <!-- QUANTIDADE -->
                <td class="py-3 px-4 font-bold">
                    {{ $professor->qtd_aluno ?? '0' }}
                </td>

                <!-- AÇÕES -->
                <td class="py-3 px-4 flex gap-2">

                    <!-- VER ALUNOS -->
                    <a href="{{ route('professor-aluno.index', Crypt::encrypt($professor->id_professor)) }}"
                        class="px-4 py-2 rounded-lg shadow text-white hover:opacity-90 transition duration-200"
                        style="background-color: #275cce;">
                        Ver Alunos
                    </a>

                    <!-- VER PROFESSOR -->
                    <a href="{{ route('professor.show', Crypt::encrypt($professor->id_professor)) }}"
                        class="px-4 py-2 rounded-lg shadow text-white hover:opacity-90 transition duration-200"
                        style="background-color: #8E251F;">
                        Ver Professor
                    </a>

                </td>

            </tr>
        </tbody>
    </table>

</div>

@endsection