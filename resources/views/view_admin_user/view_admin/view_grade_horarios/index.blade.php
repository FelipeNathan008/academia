@extends('layouts.dashboard')

@section('title', 'Grade de Horários')

@section('content')

<x-alert-error />

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.administracao') }}"
            class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>
        <h2 class="text-3xl font-extrabold text-gray-800">Grade de Horários</h2>

    </div>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow hover:bg-[#732920] transition">
        + Cadastrar Horário
    </button>
</div>

<div id="cadastroForm" class="hidden mb-10">
    <form id="formCadastro" action="{{ route('grade_horarios.store') }}" method="POST" onsubmit="bloquearSubmit(event, this)">
        @csrf

        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">Cadastrar Horário</h3>

            <!-- FORMULÁRIO -->
            @if($horariosTreino->isEmpty())
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded mb-6">
                <p class="font-semibold">Atenção</p>
                <p>Não existe nenhum horário cadastrado. É necessário cadastrar uma horários antes de criar a grade.</p>

                <a href="{{ route('horario_treino') }}"
                    class="inline-block mt-2 text-sm text-blue-600 hover:underline">
                    Cadastrar horários agora →
                </a>
            </div>
            @endif

            <!-- PROFESSOR + MODALIDADE -->
            <div class="flex gap-6 mb-4">
                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-600">Professor</label>
                    <select id="professorSelect" name="professor_id_professor" required
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                        <option value="">Selecione o professor</option>
                        @foreach ($professores as $prof)
                        <option value="{{ $prof->id_professor }}">{{ $prof->prof_nome }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- MODALIDADE -->
                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-600">Modalidade</label>
                    <select id="modalidadeSelect" name="grade_modalidade" disabled required
                        class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100">
                        <option value="">Selecione a modalidade</option>
                    </select>
                </div>
            </div>

            <!-- HORÁRIO + DIA -->
            <div class="flex gap-6 mb-4">
                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-600">Horário Treino</label>
                    <select id="horarioTreinoSelect" name="horario_treino_id_hora" disabled required
                        class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100">
                        <option value="">Selecione o horário</option>
                        @foreach ($horariosTreino as $hora)
                        <option value="{{ $hora->id_hora }}"
                            data-modalidade="{{ $hora->hora_modalidade }}"
                            data-dia="{{ $hora->hora_semana }}"
                            data-inicio="{{ $hora->hora_inicio }}"
                            data-fim="{{ $hora->hora_fim }}">
                            {{ $hora->hora_modalidade }} |
                            {{ $hora->hora_semana }} |
                            {{ substr($hora->hora_inicio,0,5) }} - {{ substr($hora->hora_fim,0,5) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-600">Dia da Semana</label>
                    <input id="gradeDiaTexto" type="text" readonly
                        class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100">
                    <input type="hidden" id="gradeDiaNumero" name="grade_dia_semana">
                </div>
            </div>

            <!-- INÍCIO + FIM -->
            <div class="flex gap-6 mb-4">
                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-600">Início</label>
                    <input id="gradeInicio" type="time" name="grade_inicio" readonly required
                        class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100">
                </div>

                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-600">Fim</label>
                    <input id="gradeFim" type="time" name="grade_fim" readonly required
                        class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100">
                </div>
            </div>

            <!-- TURMA -->
            <div class="mb-4">
                <label class="text-sm font-medium text-gray-600">Turma</label>
                <select name="grade_turma" required
                    class="w-full border rounded-lg px-4 py-2 mt-1">

                    <option value="">Selecione a turma</option>

                    @foreach ($turmas as $turma)
                    <option value="{{ $turma->turma_nome }}">
                        {{ $turma->turma_nome }}
                    </option>
                    @endforeach

                </select>
            </div>

            <!-- DESCRIÇÃO -->
            <div class="mb-6">
                <label class="text-sm font-medium text-gray-600">Descrição</label>
                <textarea name="grade_desc" rows="3" required
                    class="w-full border rounded-lg px-4 py-2 mt-1"></textarea>
            </div>

            <div class="flex justify-end gap-4 border-t pt-4">
                <button type="button" onclick="fecharCadastro()" class="px-4 py-2 border rounded-lg">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2 bg-[#8E251F] text-white rounded-lg">
                    Salvar
                </button>
            </div>

        </div>
    </form>
</div>
<div id="profData"
    data-professores='@json($professores)'
    data-horarios='@json($horariosTreino)'>
</div>

@include('view_admin_user.view_admin.view_grade_horarios.agenda_semanal')
<script>
    const profData = JSON.parse(document.getElementById('profData').dataset.professores);
    const horariosData = JSON.parse(document.getElementById('profData').dataset.horarios);

    const professorSelect = document.getElementById('professorSelect');
    const modalidadeSelect = document.getElementById('modalidadeSelect');
    const horarioSelect = document.getElementById('horarioTreinoSelect');
    const diaTexto = document.getElementById('gradeDiaTexto');
    const diaNumero = document.getElementById('gradeDiaNumero');
    const inicio = document.getElementById('gradeInicio');
    const fim = document.getElementById('gradeFim');

    const mapaDias = {
        1: 'Domingo',
        2: 'Segunda-feira',
        3: 'Terça-feira',
        4: 'Quarta-feira',
        5: 'Quinta-feira',
        6: 'Sexta-feira',
        7: 'Sábado'
    };

    function resetCampos() {
        diaTexto.value = '';
        diaNumero.value = '';
        inicio.value = '';
        fim.value = '';
    }

    function toggleCadastro() {
        cadastroForm.classList.toggle('hidden');
        formCadastro.reset();
        resetCampos();
        modalidadeSelect.innerHTML = `<option value="">Selecione a modalidade</option>`;
        modalidadeSelect.disabled = true;
        horarioSelect.innerHTML = `<option value="">Selecione o horário</option>`;
        horarioSelect.disabled = true;
    }

    function fecharCadastro() {
        cadastroForm.classList.add('hidden');
    }

    function bloquearSubmit(event, form) {
        if (!form.checkValidity()) return;
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Salvando...';
        }
    }

    // 1) PROFESSOR → popula modalidades do professor
    professorSelect.addEventListener('change', () => {
        modalidadeSelect.innerHTML = `<option value="">Selecione a modalidade</option>`;
        modalidadeSelect.disabled = true;
        horarioSelect.innerHTML = `<option value="">Selecione o horário</option>`;
        horarioSelect.disabled = true;
        resetCampos();

        const prof = profData.find(p => p.id_professor == professorSelect.value);
        if (!prof) return;

        const modalidadesVistas = new Set();
        prof.detalhes.forEach(d => {
            const modNome = d.graduacao?.modalidade?.mod_nome;
            if (modNome && !modalidadesVistas.has(modNome)) {
                modalidadesVistas.add(modNome);
                const opt = document.createElement('option');
                opt.value = modNome;
                opt.textContent = modNome;
                modalidadeSelect.appendChild(opt);
            }
        });

        if (modalidadesVistas.size === 0) {
            alert(`O professor ${prof.prof_nome} não possui modalidade cadastrada, cadastre  prosseguir`);
            professorSelect.value = '';
            return;
        }

        modalidadeSelect.disabled = false;
    });

    // 2) MODALIDADE → reconstrói options de horário filtrando pelo mod_nome
    modalidadeSelect.addEventListener('change', () => {
        horarioSelect.innerHTML = `<option value="">Selecione o horário</option>`;
        horarioSelect.disabled = true;
        resetCampos();

        const modSelecionada = modalidadeSelect.value;
        if (!modSelecionada) return;

        // filtra os horários cujo hora_modalidade bate com o nome da modalidade
        const horariosFiltrados = horariosData.filter(h => h.hora_modalidade === modSelecionada);

        if (horariosFiltrados.length === 0) {
            alert(`Nenhum horário disponível para a modalidade "${modSelecionada}".`);
            return;
        }

        horariosFiltrados.forEach(h => {
            const opt = document.createElement('option');
            opt.value = h.id_hora;
            opt.dataset.modalidade = h.hora_modalidade;
            opt.dataset.dia = h.hora_semana;
            opt.dataset.inicio = h.hora_inicio;
            opt.dataset.fim = h.hora_fim;
            opt.textContent = `${h.hora_modalidade} | ${h.hora_semana} | ${h.hora_inicio.substring(0,5)} - ${h.hora_fim.substring(0,5)}`;
            horarioSelect.appendChild(opt);
        });

        horarioSelect.disabled = false;
    });

    // 3) HORÁRIO → preenche dia/início/fim
    horarioSelect.addEventListener('change', () => {
        const opt = horarioSelect.options[horarioSelect.selectedIndex];
        if (!opt.value) {
            resetCampos();
            return;
        }

        const diasArray = opt.dataset.dia.split(',').map(d => d.trim());
        diaNumero.value = diasArray.join(',');
        diaTexto.value = diasArray.map(d => mapaDias[d]).filter(Boolean).join(', ');
        inicio.value = opt.dataset.inicio.substring(0, 5);
        fim.value = opt.dataset.fim.substring(0, 5);
    });
</script>
@endsection