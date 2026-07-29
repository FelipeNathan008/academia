@extends('layouts.dashboard')

@section('title', 'Alunos')

@section('content')

<x-alert-error />

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('responsaveis') }}" class="hover:text-[#8E251F] transition">
                Responsáveis
            </a>
        </li>
        <li>/</li>
        <li class="text-gray-400">{{ $responsavel->resp_nome }}</li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Alunos</li>
        <li>/</li>
        <li class="text-gray-400">Graduações</li>
        <li>/</li>
        <li class="text-gray-400">Matrículas</li>
        <li>/</li>
        <li class="text-gray-400">Financeiro</li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('responsaveis') }}"
            class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>

        <h2 class="text-3xl font-extrabold text-gray-800">
            Matrículas / Alunos do Responsável
        </h2>
    </div>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] transition">
        + Cadastrar Aluno
    </button>
</div>

<!-- CARD DO RESPONSÁVEL -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#174ab9] rounded-2xl shadow-lg p-6">
        <p class="text-xs uppercase tracking-widest text-gray-500">Responsável selecionado</p>
        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
            {{ $responsavel->resp_nome }}
        </h3>
        <p class="mt-2 text-sm text-gray-600">
            Telefone:
            <strong class="text-gray-800">
                {{ $responsavel->resp_telefone
                    ? preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $responsavel->resp_telefone)
                    : '-' }}
            </strong>
        </p>
    </div>
</div>

<!-- FORMULÁRIO -->
<div id="cadastroForm" class="hidden mb-10">
    <form action="{{ route('alunos.store', Crypt::encrypt($responsavel->id_responsavel)) }}"
        method="POST" onsubmit="bloquearSubmit(event, this)"
        enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">
                Cadastrar Aluno
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-600">Nome Completo</label>
                    <input type="text" name="aluno_nome" required placeholder="Ex: Davi Lucas"
                        oninput="validarNome(this)"
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Parentesco</label>
                    <select name="aluno_parentesco" required
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                        <option value="">Selecione</option>
                        <option value="Filho">Filho</option>
                        <option value="Sobrinho">Sobrinho</option>
                        <option value="Neto">Neto</option>
                        <option value="Responsável">Responsável</option>
                        <option value="Outro">Outro</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Data de Nascimento</label>
                    <input type="date" name="aluno_nascimento"
                        max="{{ \Carbon\Carbon::now()->subYears(6)->format('Y-m-d') }}"
                        min="{{ \Carbon\Carbon::now()->subYears(100)->format('Y-m-d') }}"

                        oninvalid="validarData(this)"
                        oninput="this.setCustomValidity('')"

                        required class="w-full border rounded-lg px-4 py-2 mt-1">
                </div>


                <div>
                    <label class="text-sm font-medium text-gray-600">Aluno Bolsista?</label>
                    <select name="aluno_bolsista" required
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                        <option value="">Selecione</option>
                        <option value="sim">Sim</option>
                        <option value="nao">Não</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-600">Observações</label>
                    <textarea name="aluno_desc" rows="4" required placeholder="Ex: Aluno iniciante com foco na saúde"
                        class="w-full border rounded-lg px-4 py-2 mt-1"></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-600">Foto do Aluno</label>

                    <input type="file"
                        name="aluno_foto" required
                        accept="image/*"
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                </div>

            </div>

            <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                <button type="button" onclick="fecharCadastro()"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-100">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-[#8E251F] text-white rounded-lg">
                    Salvar
                </button>
            </div>
        </div>
    </form>
</div>

<!-- LISTAGEM EM CARDS -->
<div class="bg-white rounded-2xl shadow-md p-6 mb-6"
    id="containerAlunos"
    data-aluno-inicial="{{ request('aluno') ? strtolower(request('aluno')) : '' }}">

    <h3 class="text-xl font-bold mb-6 text-gray-700">
        ALUNOS CADASTRADOS
    </h3>

    <!-- FILTROS -->
    <div class="flex justify-center mb-8">
        <div class="flex flex-wrap gap-4 items-end justify-center">

            <!-- Nome -->
            <div class="flex flex-col w-[260px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Nome
                </label>

                <input type="text"
                    id="filtroNomeAluno"
                    placeholder="Buscar por nome..."
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">
            </div>

            <!-- Graduado -->
            <div class="flex flex-col w-[200px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Graduado
                </label>

                <select id="filtroGraduadoAluno"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">

                    <option value="">Todos</option>
                    <option value="sim">Sim</option>
                    <option value="nao">Não</option>

                </select>
            </div>

            <!-- Limpar -->
            <button id="limparFiltroAluno"
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
                <th class="py-3 px-4">Aluno</th>
                <th class="py-3 px-4">Parentesco</th>
                <th class="py-3 px-4">Idade</th>
                <th class="py-3 px-4">Foto</th>
                <th class="py-3 px-4">Graduado</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($alunos as $aluno)
            @php
            $nascimento = $aluno->aluno_nascimento
            ? \Carbon\Carbon::parse($aluno->aluno_nascimento)
            : null;

            $hoje = \Carbon\Carbon::today();
            $alunoGraduado = $aluno->detalhes->where('aluno_id_aluno', $aluno->id_aluno)->count() > 0;
            @endphp

            <tr class="border-b hover:bg-gray-50 transition linha-aluno"
                data-nome="{{ strtolower($aluno->aluno_nome ?? '') }}"
                data-graduado="{{ $alunoGraduado ? 'sim' : 'nao' }}">

                <td class="py-3 px-4">
                    {{ $aluno->aluno_nome }}

                    @if(strtolower($aluno->aluno_bolsista) === 'sim')
                    <span style="
                        display:inline-block;
                        margin-left:6px;
                        padding:2px 8px;
                        font-size:0.7rem;
                        font-weight:600;
                        border-radius:9999px;
                        color:#854d0e;
                        background-color:#fef9c3;">
                        🎓 Bolsista
                    </span>
                    @endif
                </td>

                <!-- PARENTESCO -->
                <td
                    class="py-3 px-4">{{ $aluno->aluno_parentesco }}
                </td>

                <!-- IDADE + ANIVERSÁRIO -->
                <td class="py-3 px-4">
                    @if($nascimento)
                    {{ $nascimento->age }}

                    @if ($nascimento->isBirthday())
                    <span style="margin-left:6px; padding:2px 8px; font-size:0.75rem;
                            font-weight:600; border-radius:9999px;
                            color:#166534; background-color:#bbf7d0;">
                        🧁 Hoje
                    </span>
                    @elseif ($nascimento->month === $hoje->month)
                    <span style="margin-left:6px; padding:2px 8px; font-size:0.75rem;
                            font-weight:600; border-radius:9999px;
                            color:#854d0e; background-color:#fef3c7;">
                        🎉 Este mês
                    </span>
                    @endif
                    @else
                    -
                    @endif
                </td>

                <!-- FOTO -->
                <td class="py-3 px-4">
                    @if($aluno->aluno_foto)
                    <div class="w-12 h-12 overflow-hidden">
                        <img src="{{ asset('images/alunos/' . $aluno->aluno_foto) }}"
                            alt="Foto" style="width:48px; height:48px; object-fit:cover;">
                    </div>
                    @else
                    -
                    @endif
                </td>

                <!--
                <td class="py-3 px-4">
                    @if(strtolower($aluno->aluno_bolsista) === 'sim')
                    <span style="
                        padding:4px 10px;
                        font-size:0.75rem;
                        font-weight:600;
                        border-radius:9999px;
                        color:#166534;
                        background-color:#bbf7d0;">
                        Sim
                    </span>
                    @else
                    <span style="
                        padding:4px 10px;
                        font-size:0.75rem;
                        font-weight:600;
                        border-radius:9999px;
                        color:#7f1d1d;
                        background-color:#fecaca;">
                        Não
                    </span>
                    @endif
                </td>

                <td class="py-3 px-4">
                    @if($aluno->matriculas->where('matri_status', 'Matriculado')->count() > 0)
                    <span style="padding:2px 8px; font-size:0.75rem;
                    font-weight:600; border-radius:9999px;
                    color:#166534; background-color:#bbf7d0;"> 🎓 Sim
                    </span>
                    @else
                    <span style="
                        padding:4px 10px;
                        font-size:0.75rem;
                        font-weight:600;
                        border-radius:9999px;
                        color:#7f1d1d;
                        background-color:#fecaca;">
                        Não
                    </span>
                    @endif
                </td>

-->

                <!-- Graduado -->
                <td class="py-3 px-4">
                    @if($alunoGraduado)
                    <span style="padding:2px 8px; font-size:0.75rem;
font-weight:600; border-radius:9999px;
color:#166534; background-color:#bbf7d0;"> 🥋 Sim
                    </span>
                    @else
                    <span style="
        padding:4px 10px;
        font-size:0.75rem;
        font-weight:600;
        border-radius:9999px;
        color:#7f1d1d;
        background-color:#fecaca;">
                        Não
                    </span>
                    @endif
                </td>
                <!-- AÇÕES -->
                <td class="py-3 px-4 flex gap-2">
                    <a href="{{ route('detalhes-aluno.index', Crypt::encrypt($aluno->id_aluno)) }}"
                        style="background-color: #174ab9; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                        Graduações
                    </a>

                    <a href="{{ route('matricula', Crypt::encrypt($aluno->id_aluno)) }}"
                        style="background-color: #275cce; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                        Matrícula
                    </a>

                    <!--@if(strtolower($aluno->aluno_bolsista) !== 'sim')
                    <a href="{{ route('mensalidade', Crypt::encrypt($aluno->id_aluno)) }}"
                        style="background-color: #15803d; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#166534] transition duration-200 text-center">
                        Financeiro
                    </a>
                    @endif-->


                    <a href="{{ route('alunos.edit', Crypt::encrypt($aluno->id_aluno)) }}"
                        style="background-color: #8E251F; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                        Editar
                    </a>

                    <!--<form action="{{ route('alunos.destroy', Crypt::encrypt($aluno->id_aluno)) }}" method="POST"
                        onsubmit="return confirm('Deseja excluir este aluno?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            style="background-color: #c02600; color: white;"
                            class="px-4 py-2 rounded-lg shadow hover:bg-[#D65A3E] transition duration-200">
                            Excluir
                        </button>
                    </form>-->
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="9" class="text-center py-6 text-gray-500">
                    Nenhum aluno cadastrado para este responsável
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {

        const filtroNome = document.getElementById('filtroNomeAluno');
        const filtroGraduado = document.getElementById('filtroGraduadoAluno');
        const limpar = document.getElementById('limparFiltroAluno');
        const linhas = document.querySelectorAll('.linha-aluno');

        function aplicarFiltroAluno() {
            const nome = filtroNome.value.toLowerCase().trim();
            const graduado = filtroGraduado.value;

            linhas.forEach(linha => {
                const n = linha.dataset.nome || '';
                const g = linha.dataset.graduado || '';
                let mostrar = true;

                if (nome && !n.includes(nome)) {
                    mostrar = false;
                }

                if (graduado && g !== graduado) {
                    mostrar = false;
                }

                linha.style.display = mostrar ? '' : 'none';
            });
        }

        if (filtroNome) filtroNome.addEventListener('input', aplicarFiltroAluno);
        if (filtroGraduado) filtroGraduado.addEventListener('change', aplicarFiltroAluno);

        if (limpar) {
            limpar.addEventListener('click', function() {
                filtroNome.value = '';
                filtroGraduado.value = '';
                aplicarFiltroAluno();
            });
        }

        // Aplica filtro de aluno vindo da URL
        // (ex: clique em "Ver Aluno" na tela de Matrícula/Modalidade)
        const containerAlunos = document.getElementById('containerAlunos');
        const alunoInicial = containerAlunos?.dataset.alunoInicial;

        if (alunoInicial && filtroNome) {
            filtroNome.value = alunoInicial;
            aplicarFiltroAluno();
        }

    });

    function validarData(campo) {
        if (campo.validity.valueMissing) {
            campo.setCustomValidity('A data de nascimento é obrigatória');
        } else if (campo.validity.rangeOverflow) {
            campo.setCustomValidity('Você precisa ter pelo menos 6 anos');
        } else if (campo.validity.rangeUnderflow) {
            campo.setCustomValidity('Idade não compatível com o sistema');
        } else {
            campo.setCustomValidity('');
        }
    }

    function toggleCadastro() {
        document.getElementById('cadastroForm').classList.toggle('hidden');
        document.getElementById('cadastroForm').scrollIntoView({
            behavior: 'smooth'
        });
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }

    function bloquearSubmit(event, form) {

        if (!form.checkValidity()) {
            return; // deixa validação normal do HTML
        }

        const btn = form.querySelector('button[type="submit"]');

        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Salvando...';
        }
    }

    function validarNome(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
    }
</script>

@endsection