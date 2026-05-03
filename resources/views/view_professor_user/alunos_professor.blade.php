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
                <td class="py-3 px-4 font-medium">
                    {{ $professor->prof_nome }}
                </td>

                <td class="py-3 px-4 font-bold">
                    {{ $professor->empresas->emp_nome ?? '-' }}
                </td>

                <td class="py-3 px-4">
                    @if($professor->prof_foto)
                    <div class="w-12 h-12 overflow-hidden">
                        <img src="{{ asset('images/professores/' . $professor->prof_foto) }}"
                            alt="Foto" style="width:48px; height:48px; object-fit:cover;">
                    </div>
                    @else
                    -
                    @endif
                </td>


                <td class="py-3 px-4 font-bold">
                    {{ $professor->qtd_aluno ?? '0'}}
                </td>

                <td class="py-3 px-4 flex gap-2">

                    <!-- VER ALUNOS -->
                    <button onclick="toggleAlunos()"
                        class="btn-ver-prof px-4 py-2 rounded-lg shadow text-white"
                        style="background-color: #275cce; color: white;">
                        Ver Alunos
                    </button>

                    <!-- VER PROFESSOR -->
                    <a href="{{ route('professor.show', Crypt::encrypt($professor->id_professor)) }}"
                        style="background-color: #8E251F; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                        Ver Professor
                    </a>

                </td>
            </tr>
        </tbody>
    </table>

    <!-- LISTA DE ALUNOS -->
    <div id="listaAlunos" class="hidden mt-8">

        <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">

            <!-- HEADER -->
            <div class="px-4 py-3 bg-gray-100 border-b flex justify-between items-center">
                <h4 class="text-sm font-semibold text-gray-700">
                    Alunos do Professor
                </h4>
            </div>

            <!-- TABELA -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Foto</th>
                            <th class="px-4 py-3">Aluno</th>
                            <th class="px-4 py-3">Idade</th>
                            <th class="px-4 py-3">Modalidade</th>
                            <th class="px-4 py-3">Mensalidade</th>
                            <th class="px-4 py-3">Bolsista</th>
                            <th class="px-4 py-3">Ações</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse($alunos as $aluno)

                        @php
                        $nascimento = $aluno->aluno_nascimento
                        ? \Carbon\Carbon::parse($aluno->aluno_nascimento)
                        : null;

                        $matriculaAtiva = $aluno->matriculas
                        ->where('matri_status', 'Matriculado')
                        ->first();

                        $grade = $matriculaAtiva?->grade;
                        @endphp

                        <tr class="hover:bg-gray-50 transition">

                            <!-- FOTO -->
                            <td class="py-3 px-4">
                                @if($aluno->aluno_foto)
                                <div class="w-12 h-12 overflow-hidden rounded-lg">
                                    <img src="{{ asset('images/alunos/' . $aluno->aluno_foto) }}"
                                        style="width:48px; height:48px; object-fit:cover;">
                                </div>
                                @else
                                -
                                @endif
                            </td>

                            <!-- NOME -->
                            <td class="px-4 py-3">
                                {{ $aluno->aluno_nome }}
                            </td>

                            <!-- IDADE -->
                            <td class="px-4 py-3">
                                {{ $nascimento ? $nascimento->age : '-' }}
                            </td>

                            <!-- MODALIDADE -->
                            <td class="px-4 py-3">
                                {{ $grade->grade_modalidade ?? '-' }}
                            </td>

                            <!-- MENSALIDADE -->
                            <td class="px-4 py-3">
                                @if(isset($aluno->atrasado) && $aluno->atrasado)
                                <span style="padding:2px 8px; font-size:0.75rem;
                                    font-weight:600; border-radius:9999px;
                                    color:#991b1b; background-color:#fecaca;">
                                    Atrasado
                                </span>
                                @else
                                <span style="padding:2px 8px; font-size:0.75rem;
                                    font-weight:600; border-radius:9999px;
                                    color:#166534; background-color:#bbf7d0;">
                                    Em dia
                                </span>
                                @endif
                            </td>

                            <!-- BOLSISTA -->
                            <td class="px-4 py-3">
                                @if(strtolower($aluno->aluno_bolsista) === 'sim')
                                <span style="padding:2px 8px; font-size:0.75rem;
                                    font-weight:600; border-radius:9999px;
                                    color:#166534; background-color:#bbf7d0;">
                                    Sim
                                </span>
                                @else
                                <span style="padding:2px 8px; font-size:0.75rem;
                                    font-weight:600; border-radius:9999px;
                                    color:#444; background-color:#f3f4f6;">
                                    Não
                                </span>
                                @endif
                            </td>

                            <!-- AÇÕES -->
                            <td class="px-4 py-3 flex gap-2">

                                {{-- DETALHES --}}
                                <a href="{{ route('professor-aluno.show', Crypt::encrypt($aluno->id_aluno)) }}"
                                    style="background-color: #174ab9; color: white;"
                                    class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                                    Detalhes
                                </a>

                                {{-- FINANCEIRO --}}
                                @if(strtolower($aluno->aluno_bolsista) !== 'sim' && $matriculaAtiva)
                                <a href="{{ route('professor.financeiro', Crypt::encrypt($aluno->id_aluno)) }}"
                                    style="background-color: #15803d; color: white;"
                                    class="px-4 py-2 rounded-lg shadow hover:bg-[#166534] transition duration-200 text-center">
                                    Financeiro
                                </a>
                                @endif

                                {{-- MATRÍCULA --}}
                                <a href="{{ route('matricula', Crypt::encrypt($aluno->id_aluno)) }}"
                                    style="background-color: #8E251F; color: white;"
                                    class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                                    Ver Matrícula
                                </a>

                            </td>

                        </tr>

                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-400 py-4">
                                Nenhum aluno encontrado
                            </td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </div>

    </div>

</div>

<!-- JS -->
<script>
    function toggleAlunos() {
        const div = document.getElementById('listaAlunos');
        div.classList.toggle('hidden');
    }
</script>

@endsection