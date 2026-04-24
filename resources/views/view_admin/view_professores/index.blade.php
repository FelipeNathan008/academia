@extends('layouts.dashboard')

@section('title', 'Professores')
@section('content')


<x-alert-error />

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">
            @if($modo === 'alunos')
            Professores / Alunos
            @else
            Professores
            @endif
        </h2>
    </div>

    @if($modo !== 'alunos')
    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">
        + Cadastrar Professor
    </button>
    @endif

</div>

@if($modo !== 'alunos')
<!-- FORMULÁRIO DE CADASTRO -->
<div id="cadastroForm" class="hidden mb-10">
    <form id="formCadastro" action="{{ route('professores.store') }}" method="POST" enctype="multipart/form-data" onsubmit=" bloquearSubmit(event, this)">
        @csrf
        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 id="tituloCadastro" class="text-xl font-bold mb-6 text-gray-700">Cadastrar Professor</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- NOME -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Nome Completo</label>
                    <input type="text" name="prof_nome" id="cad_nome" maxlength="120" required
                        placeholder="Ex: João da Silva"
                        oninput="validarNome(this)"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>

                <!-- NASCIMENTO -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Data de Nascimento</label>
                    <input type="date" name="prof_nascimento" id="cad_nascimento" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>

                <!-- TELEFONE -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Telefone</label>
                    <input type="text"
                        name="prof_telefone"
                        id="prof_telefone"
                        required
                        placeholder="(99) 99999-9999"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>

                <!-- DESCRIÇÃO -->
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-600">Observações</label>
                    <textarea name="prof_desc" id="cad_desc" required rows="3"
                        placeholder="Ex: Professor de Judô, horários flexíveis"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"></textarea>
                </div>

                <!-- FOTO -->
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-600">Foto do Professor</label>
                    <input type="file" name="prof_foto" id="cad_foto" required accept="image/*"
                        class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-50">
                </div>
            </div>

            <!-- AÇÕES -->
            <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                <button type="button" onclick="fecharCadastro()"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-100 transition">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920] transition">
                    Salvar Professor
                </button>
            </div>
        </div>
    </form>
</div>
@endif

<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">LISTA DE PROFESSORES</h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gray-300 text-gray-600 text-sm">
                <th class="py-3 px-4">Nome</th>
               <!-- <th class="py-3 px-4">Idade</th>-->
                <th class="py-3 px-4">Empresa</th>
                <th class="py-3 px-4">Foto</th>
                <th class="py-3 px-4">Qtd. Alunos</th>
                @if($modo !== 'alunos')
                <th class="py-3 px-4">Graduado</th>
                @endif
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($professores as $professor)

            @php
            $nascimento = $professor->prof_nascimento
            ? \Carbon\Carbon::parse($professor->prof_nascimento)
            : null;
            $hoje = \Carbon\Carbon::today();
            @endphp

            <tr class="border-b transition professor-row"
                data-prof="{{ $professor->id_professor }}">
                <td class="py-3 px-4">{{ $professor->prof_nome }}</td>



               <!-- <td class="py-3 px-4">
                    @if($nascimento)
                    {{ $nascimento->age }}

                    @if ($nascimento->isBirthday())
                    <span style="margin-left:6px; padding:2px 8px; font-size:0.75rem; font-weight:600;
                    border-radius:9999px; color:#166534; background-color:#bbf7d0;">
                        🧁 Hoje
                    </span>
                    @elseif ($nascimento->month === $hoje->month)
                    <span style="margin-left:6px; padding:2px 8px; font-size:0.75rem; font-weight:600;
                    border-radius:9999px; color:#854d0e; background-color:#fef3c7;">
                        🎉 Este mês
                    </span>
                    @endif
                    @else
                    -
                    @endif
                </td> -->


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

                @if($modo !== 'alunos')

                <!-- Graduado -->
                <td class="py-3 px-4">
                    @if($professor->detalhes->where('professor_id_professor',$professor->id_professor)->count() > 0)
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
                @endif

                <td class="py-3 px-4 flex gap-2">

                    <button type="button"
                        data-id="{{ $professor->id_professor }}"
                        class="btn-ver-prof px-4 py-2 rounded-lg shadow text-white"
                        style="background-color: #275cce; color: white;">
                        Ver Alunos
                    </button>

                    @if($modo !== 'alunos')

                    <!-- Botão Graduações -->
                    <a href="{{ route('detalhes-professor.index', Crypt::encrypt($professor->id_professor)) }}"
                        style="background-color: #174ab9; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                        Graduações
                    </a>

                    <a href="{{ route('professores.show', Crypt::encrypt($professor->id_professor)) }}"
                        style="background-color: #ca8a04; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                        Ver
                    </a>

                    <a href="{{ route('professores.edit', Crypt::encrypt($professor->id_professor)) }}"
                        style="background-color: #8E251F; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                        Editar
                    </a>

                    <form action="{{ route('professores.destroy', Crypt::encrypt($professor->id_professor)) }}" method="POST"
                        onsubmit="return confirm('Deseja excluir este professor?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            style="background-color: #c02600; color: white;"
                            class="px-4 py-2 rounded-lg shadow hover:bg-[#D65A3E] transition duration-200">
                            Excluir
                        </button>
                    </form>
                    @endif

                </td>
            </tr>

            <tr id="detalhe-prof-{{ $professor->id_professor }}" class="hidden bg-gray-50">
                <td colspan="8" class="px-6 py-6">

                    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden p-4">

                        <div class="flex flex-col w-[300px]">
                            <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                                Turma / Horário
                            </label>

                            <select class="filtro-unico border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                                focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                                data-prof="{{ $professor->id_professor }}">

                                <option value="">Todas</option>
                                @php
                                $diasMap = [
                                1 => 'Dom',
                                2 => 'Seg',
                                3 => 'Ter',
                                4 => 'Qua',
                                5 => 'Qui',
                                6 => 'Sex',
                                7 => 'Sáb'
                                ];
                                @endphp
                                @foreach($professor->alunos->unique('id_grade') as $g)

                                @php
                                $dias = collect(explode(',', $g->grade_dia_semana ?? ''))
                                ->map(fn($d) => $diasMap[$d] ?? $d)
                                ->implode(', ');
                                @endphp

                                <option value="{{ $g->id_grade }}">
                                    {{ $g->grade_modalidade }}
                                    - {{ $g->grade_turma }}
                                    - ({{ \Carbon\Carbon::parse($g->grade_inicio)->format('H:i') }}
                                    até {{ \Carbon\Carbon::parse($g->grade_fim)->format('H:i') }})
                                    - {{ $dias }}
                                </option>

                                @endforeach

                            </select>
                        </div>

                        <!-- HEADER -->
                        <div class="px-4 py-3 bg-gray-100 border-b">
                            <h4 class="text-sm font-semibold text-gray-700">
                                Alunos do Professor
                            </h4>
                        </div>

                        <!-- TABELA -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                                    <tr>
                                        <th class="px-4 py-3">Aluno</th>
                                        <th class="px-4 py-3">Idade</th>
                                        <th class="px-4 py-3">Modalidade</th>
                                        <th class="px-4 py-3">Mensalidade</th>
                                        <th class="px-4 py-3">Bolsista</th>
                                        <th class="px-4 py-3">Ações</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-100">

                                    @forelse($professor->alunos as $aluno)
                                    @php
                                    $nascimento = $aluno->aluno_nascimento
                                    ? \Carbon\Carbon::parse($aluno->aluno_nascimento)
                                    : null;
                                    @endphp

                                    <tr class="linha-aluno hover:bg-gray-50 transition"
                                        data-grade="{{ $aluno->id_grade }}"
                                        data-horario="{{ $aluno->grade_inicio }}">

                                        <td class="px-4 py-3">
                                            {{ $aluno->aluno_nome }}
                                        </td>

                                        <!-- IDADE -->
                                        <td class="py-3 px-4">
                                            {{ $nascimento ? $nascimento->age : '-' }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $aluno->grade_modalidade ?? '-' }}
                                        </td>

                                        <!-- MENSALIDADES  -->
                                        <td class="py-3 px-4">
                                            @if($aluno->atrasado)
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


                                        <td class="py-3 px-4 flex gap-2">

                                            {{-- VER ALUNO --}}
                                            @if($aluno->id_responsavel)
                                            <a href="{{ route('alunos.show', Crypt::encrypt($aluno->id_aluno)) }}"
                                                style="background-color: #174ab9; color: white;"
                                                class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                                                Detalhes
                                            </a>
                                            @endif

                                            {{-- FINANCEIRO --}}
                                            @if(strtolower($aluno->aluno_bolsista) !== 'sim' && $aluno->matriculado > 0)
                                            <a href="{{ route('mensalidade', Crypt::encrypt($aluno->id_aluno)) }}"
                                                style="background-color: #15803d; color: white;"
                                                class="px-4 py-2 rounded-lg shadow hover:bg-[#166534] transition duration-200 text-center">
                                                Financeiro
                                            </a>
                                            @endif

                                            {{-- MATRÍCULA --}}
                                            @if($aluno->matriculado == 0)
                                            <a href="{{ route('matricula', Crypt::encrypt($aluno->id_aluno)) }}"
                                                class="px-4 py-2 rounded-lg shadow text-white"
                                                style="background-color: #ca8a04;">
                                                Matricular
                                            </a>
                                            @else
                                            <a href="{{ route('matricula', Crypt::encrypt($aluno->id_aluno)) }}"
                                                style="background-color: #8E251F; color: white;"
                                                class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                                                Ver Matrícula
                                            </a>
                                            @endif

                                        </td>

                                    </tr>

                                    @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-4 text-center text-gray-400">
                                            Nenhum aluno vinculado
                                        </td>
                                    </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>

                    </div>

                </td>
            </tr>

            @empty
            <tr>
                <td colspan="8" class="text-center text-gray-500 py-6">Nenhum professor cadastrado</td>
            </tr>
            @endforelse
        </tbody>

    </table>
</div>

<!-- JS -->
<script>
    // Toggle Cadastro
    function toggleCadastro() {
        const form = document.getElementById('cadastroForm');
        form.classList.toggle('hidden');
        form.scrollIntoView({
            behavior: 'smooth'
        });
        document.getElementById('formCadastro').reset();
    }


    document.addEventListener("DOMContentLoaded", function() {

        const botoes = document.querySelectorAll(".btn-ver-prof");

        botoes.forEach(botao => {
            botao.addEventListener("click", function() {

                const id = this.dataset.id;
                const detalhe = document.getElementById("detalhe-prof-" + id);

                detalhe.classList.toggle("hidden");

            });
        });

    });

    document.querySelectorAll(".filtro-unico").forEach(select => {
        select.addEventListener("change", function() {

            const grade = this.value;
            const profId = this.dataset.prof;

            const container = document.querySelector("#detalhe-prof-" + profId);
            const linhas = container.querySelectorAll(".linha-aluno");

            linhas.forEach(linha => {

                if (!grade) {
                    linha.style.display = "";
                    return;
                }

                if (linha.dataset.grade === grade) {
                    linha.style.display = "";
                } else {
                    linha.style.display = "none";
                }

            });

        });
    });

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

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }


    // Validação de nome
    function validarNome(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
    }


    const tel = document.getElementById('prof_telefone');

    if (tel) {
        tel.addEventListener('input', () => {
            let v = tel.value.replace(/\D/g, '');

            if (v.length > 11) v = v.slice(0, 11);

            let f = '';
            if (v.length > 0) f = '(' + v.slice(0, 2);
            if (v.length >= 3) f += ') ' + v.slice(2, 7);
            if (v.length >= 8) f += '-' + v.slice(7, 11);

            tel.value = f;
        });
    }
</script>

@endsection