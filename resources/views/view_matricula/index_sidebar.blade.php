@extends('layouts.dashboard')

@section('title', 'Matrículas e Financeiro')

@section('content')

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">

    <h2 class="text-3xl font-extrabold text-gray-800">
        Selecionar Aluno para Matrícula / Financeiro
    </h2>

</div>

<!-- FILTROS -->
<div class="bg-white rounded-2xl shadow-md p-6 overflow-x-auto mb-8">
    <div class="flex flex-wrap gap-6 items-end justify-center max-w-6xl mx-auto">

        <!-- Buscar por Nome -->
        <div class="flex flex-col w-[300px]">
            <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                Buscar Aluno
            </label>
            <input type="text" id="filtroNome"
                placeholder="Digite o nome..."
                class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                       focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
        </div>

        <div class="flex flex-col w-[300px]">
            <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                Buscar Responsável
            </label>
            <input type="text" id="filtroResponsavel"
                placeholder="Digite o responsavel..."
                class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                       focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
        </div>


        <!-- Bolsista -->
        <div class="flex flex-col w-[300px]">
            <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                Bolsista
            </label>
            <select id="filtroBolsista"
                class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                       focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                <option value="">Todos</option>
                <option value="sim">Sim</option>
                <option value="nao">Não</option>
            </select>
        </div>

        <div class="flex flex-col w-[300px]">
            <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                Matrícula
            </label>
            <select id="filtroMatricula"
                class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
               focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                <option value="">Todos</option>
                <option value="com">Matriculado</option>
                <option value="sem">Não Matriculado</option>
            </select>
        </div>

        <!-- Limpar -->
        <button id="limparFiltros"
            class="self-end h-[48px] px-8 rounded-xl bg-gradient-to-r from-gray-300 to-gray-400
                   text-gray-800 font-semibold hover:from-gray-400 hover:to-gray-500
                   transition shadow-md">
            Limpar filtros
        </button>

    </div>
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
                <th class="py-3 px-4">Matriculado</th>
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

            <tr class="border-b hover:bg-gray-50 transition linha-aluno"
                data-nome="{{ strtolower($aluno->aluno_nome) }}"
                data-bolsista="{{ strtolower($aluno->aluno_bolsista) }}"
                data-responsavel="{{ strtolower($aluno->responsavel->resp_nome ?? '') }}"
                data-matricula="{{ $aluno->matriculas->count() > 0 ? 'com' : 'sem' }}">


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

                <!-- CPF -->
                <td class="py-3 px-4">
                    @if($aluno->responsavel && $aluno->responsavel->resp_cpf)
                    {{ preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $aluno->responsavel->resp_cpf) }}
                    @else
                    -
                    @endif
                </td>

                <!-- BOLSISTA -->
                <td class="py-3 px-4">
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

                <!-- Matriculado -->
                <td class="py-3 px-4">
                    @if($aluno->matriculas->count() > 0)
                    <span style="padding:2px 8px; font-size:0.75rem;
                    font-weight:600; border-radius:9999px;
                    color:#166534; background-color:#bbf7d0;"> 🎓 Sim
                    </span>
                    @else
                    <span class="px-2 py-1 text-xs font-semibold rounded-full text-gray-700 bg-gray-200">
                        Não
                    </span>
                    @endif
                </td>

                <!-- AÇÕES -->
                <td class="py-3 px-4 flex gap-2">

                    @if($aluno->responsavel)
                    <a href="{{ route('alunos', $aluno->responsavel->id_responsavel) }}"
                        style="background-color: #174ab9; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                        Ver Aluno
                    </a>
                    @endif

                    @if(strtolower($aluno->aluno_bolsista) !== 'sim' && $aluno->matriculas->count() > 0)
                    <a href="{{ route('mensalidade', $aluno->id_aluno) }}"
                        style="background-color: #15803d; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#166534] transition duration-200 text-center">
                        Financeiro
                    </a>
                    @endif

                    @if($aluno->matriculas->count() == 0)
                    <a href="{{ route('matricula', $aluno->id_aluno) }}"
                        style="background-color: #275cce; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                        Matricular
                    </a>
                    @else
                    <a href="{{ route('matricula', $aluno->id_aluno) }}"
                        style="background-color: #275cce; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                        Ver Matrícula
                    </a>
                    @endif
                </td>

            </tr>

            @empty
            <tr>
                <td colspan="7" class="text-center py-6 text-gray-500">
                    Nenhum aluno cadastrado
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const filtroNome = document.getElementById('filtroNome');
        const filtroResponsavel = document.getElementById('filtroResponsavel');
        const filtroBolsista = document.getElementById('filtroBolsista');
        const filtroMatricula = document.getElementById('filtroMatricula');
        const limparBtn = document.getElementById('limparFiltros');
        const linhas = document.querySelectorAll('.linha-aluno');

        function aplicarFiltro() {
            const nome = filtroNome.value.toLowerCase().trim();
            const responsavel = filtroResponsavel.value.toLowerCase().trim();
            const bolsista = filtroBolsista.value.toLowerCase().trim();
            const matricula = filtroMatricula.value.toLowerCase().trim();

            linhas.forEach(linha => {

                const nomeAluno = (linha.dataset.nome || '').toLowerCase();
                const responsavelAluno = (linha.dataset.responsavel || '').toLowerCase();
                const bolsistaAluno = (linha.dataset.bolsista || '').toLowerCase();
                const matriculaAluno = (linha.dataset.matricula || '').toLowerCase();

                let mostrar = true;

                if (nome && !nomeAluno.includes(nome)) {
                    mostrar = false;
                }

                if (responsavel && !responsavelAluno.includes(responsavel)) {
                    mostrar = false;
                }

                if (bolsista && bolsistaAluno !== bolsista) {
                    mostrar = false;
                }

                if (matricula && matriculaAluno !== matricula) {
                    mostrar = false;
                }

                linha.style.display = mostrar ? '' : 'none';
            });
        }

        filtroNome.addEventListener('input', aplicarFiltro);
        filtroResponsavel.addEventListener('input', aplicarFiltro);
        filtroBolsista.addEventListener('change', aplicarFiltro);
        filtroMatricula.addEventListener('change', aplicarFiltro);

        limparBtn.addEventListener('click', function() {
            filtroNome.value = '';
            filtroResponsavel.value = '';
            filtroBolsista.value = '';
            filtroMatricula.value = '';
            aplicarFiltro();
        });

    });
</script>

@endsection