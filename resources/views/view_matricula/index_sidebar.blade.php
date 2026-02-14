@extends('layouts.dashboard')

@section('title', 'Matrículas e Financeiro')

@section('content')

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">

    <h2 class="text-3xl font-extrabold text-gray-800">
        Selecionar Aluno para Matrícula / Financeiro
    </h2>

</div>

<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6 mb-6">

    <h3 class="text-xl font-bold mb-6 text-gray-700">
        Alunos Cadastrados
    </h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Aluno</th>
                <th class="py-3 px-4">Nascimento</th>
                <th class="py-3 px-4">Idade</th>
                <th class="py-3 px-4">Responsável</th>
                <th class="py-3 px-4">CPF</th>
                <th class="py-3 px-4">Bolsista</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($alunos as $aluno)

            @php
            $nascimento = $aluno->aluno_nascimento
            ? \Carbon\Carbon::parse($aluno->aluno_nascimento)
            : null;
            @endphp

            <tr class="border-b hover:bg-gray-50 transition">

                <!-- NOME -->
                <td class="py-3 px-4 font-medium text-gray-800">
                    {{ $aluno->aluno_nome }}
                </td>

                <!-- NASCIMENTO -->
                <td class="py-3 px-4">
                    {{ $nascimento ? $nascimento->format('d/m/Y') : '-' }}
                </td>

                <!-- IDADE -->
                <td class="py-3 px-4">
                    {{ $nascimento ? $nascimento->age . ' anos' : '-' }}
                </td>

                <!-- RESPONSÁVEL -->
                <td class="py-3 px-4">
                    {{ $aluno->responsavel->resp_nome ?? '-' }}
                </td>

                <!-- cpf-->
                <td class="py-3 px-4">
                    @if($aluno->responsavel && $aluno->responsavel->resp_cpf)
                    {{ preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $aluno->responsavel->resp_cpf) }}
                    @else
                    -
                    @endif
                </td>


                <!-- BOLSISTA -->
                <td class="py-3 px-4">
                    @if($aluno->aluno_bolsista === 'sim')
                    <span style="padding:2px 8px; font-size:0.75rem;
                            font-weight:600; border-radius:9999px;
                            color:#166534; background-color:#bbf7d0;">
                        🎓 Sim
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
                <td class="py-3 px-4 flex gap-2">

                    <!-- Ver Aluno (vai para lista de alunos do responsável) -->
                    @if($aluno->responsavel)
                    <a href="{{ route('alunos', $aluno->responsavel->id_responsavel) }}"
                        style="background-color: #174ab9; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                        Ver Aluno
                    </a>
                    @endif
                    <a href="{{ route('mensalidade', $aluno->id_aluno) }}"
                        style="background-color: #15803d; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#166534] transition duration-200 text-center">
                        Financeiro
                    </a>
                    <!-- Ver Matrícula -->
                    <a href="{{ route('matricula', $aluno->id_aluno) }}"
                        style="background-color: #275cce; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                        Ver Matrícula
                    </a>

                </td>


            </tr>

            @empty
            <tr>
                <td colspan="6" class="text-center py-6 text-gray-500">
                    Nenhum aluno cadastrado
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>

@endsection