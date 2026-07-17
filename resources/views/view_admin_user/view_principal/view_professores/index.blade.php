@extends('layouts.dashboard')

@section('title', 'Professores')
@section('content')


<x-alert-error />

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ url()->previous() }}"
            class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>
        <h2 class="text-3xl font-extrabold text-gray-800">
            Professores
        </h2>
    </div>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">
        + Cadastrar Professor
    </button>

</div>


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


<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">LISTA DE PROFESSORES</h3>

    <!-- FILTROS -->
    <div class="flex justify-center mb-8">
        <div class="flex flex-wrap gap-4 items-end justify-center">

            <!-- Nome -->
            <div class="flex flex-col w-[260px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Nome
                </label>

                <input type="text"
                    id="filtroNomeProfessor"
                    placeholder="Buscar por nome..."
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">
            </div>

            <!-- Graduado -->
            <div class="flex flex-col w-[200px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Graduado
                </label>

                <select id="filtroGraduado"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">

                    <option value="">Todos</option>
                    <option value="sim">Sim</option>
                    <option value="nao">Não</option>

                </select>
            </div>

            <!-- Limpar -->
            <button id="limparFiltroProfessor"
                class="h-[48px] px-6 rounded-xl bg-gray-300
                       text-gray-800 font-semibold hover:bg-gray-400
                       transition shadow-md">
                Limpar filtro
            </button>

        </div>
    </div>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gray-300 text-gray-600 text-sm">
                <th class="py-3 px-4">Nome</th>
                <th class="py-3 px-4">Foto</th>
                <th class="py-3 px-4">Qtd. Alunos</th>
                <th class="py-3 px-4">Graduado</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($professores as $professor)

            @php
            $professorGraduado = $professor->detalhes->where('professor_id_professor', $professor->id_professor)->count() > 0;
            @endphp

            <tr class="border-b hover:bg-gray-50 transition linha-professor"
                data-nome="{{ strtolower($professor->prof_nome ?? '') }}"
                data-graduado="{{ $professorGraduado ? 'sim' : 'nao' }}">

                <td class="py-3 px-4">{{ $professor->prof_nome }}</td>

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

                <!-- Graduado -->
                <td class="py-3 px-4">
                    @if($professorGraduado)
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

                <td class="py-3 px-4 flex gap-2">

                    <a href="{{ route('grades.aulas') }}?professor={{ Crypt::encrypt($professor->id_professor) }}"
                        style="background-color: #275cce; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                        Aulas
                    </a>

                    <!-- Botão Graduações -->
                    <a href="{{ route('detalhes-professor.index', Crypt::encrypt($professor->id_professor)) }}"
                        style="background-color: #174ab9; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                        Graduações
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

                </td>
            </tr>

            @empty
            <tr>
                <td colspan="5" class="text-center text-gray-500 py-6">Nenhum professor cadastrado</td>
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

    // FILTROS DE PROFESSORES
    document.addEventListener("DOMContentLoaded", function() {

        const filtroNome = document.getElementById('filtroNomeProfessor');
        const filtroGraduado = document.getElementById('filtroGraduado');
        const limpar = document.getElementById('limparFiltroProfessor');
        const linhas = document.querySelectorAll('.linha-professor');

        function aplicarFiltroProfessor() {
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

        if (filtroNome) filtroNome.addEventListener('input', aplicarFiltroProfessor);
        if (filtroGraduado) filtroGraduado.addEventListener('change', aplicarFiltroProfessor);

        if (limpar) {
            limpar.addEventListener('click', function() {
                filtroNome.value = '';
                filtroGraduado.value = '';
                aplicarFiltroProfessor();
            });
        }

    });
</script>

@endsection