@extends('layouts.dashboard')

@section('title', 'Editar Horário da Grade')

@section('content')

<x-alert-error />

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Editar Horário ({{ $grade->grade_modalidade }} - {{ $grade->grade_dia_semana }})
    </h2>

    <form action="{{ route('grade_horarios.update', Crypt::encrypt($grade->id_grade)) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- PROFESSOR + MODALIDADE -->
        <div class="flex gap-6 mb-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Professor</label>
                <select id="professorSelect" name="professor_id_professor" required
                    class="w-full border rounded-lg px-4 py-2 mt-1">
                    <option value="">Selecione o professor</option>
                    @foreach ($professores as $prof)
                    <option value="{{ $prof->id_professor }}"
                        {{ $grade->professor_id_professor == $prof->id_professor ? 'selected' : '' }}>
                        {{ $prof->prof_nome }}
                    </option>
                    @endforeach
                </select>
            </div>

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
            <select name="grade_turma" required class="w-full border rounded-lg px-4 py-2 mt-1">
                <option value="">Selecione a turma</option>
                @foreach($turmas as $turma)
                <option value="{{ $turma->turma_nome }}"
                    {{ $grade->grade_turma == $turma->turma_nome ? 'selected' : '' }}>
                    {{ $turma->turma_nome }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- DESCRIÇÃO -->
        <div class="mb-6">
            <label class="text-sm font-medium text-gray-600">Descrição</label>
            <textarea name="grade_desc" rows="3" required
                class="w-full border rounded-lg px-4 py-2 mt-1">{{ $grade->grade_desc }}</textarea>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('grade_horarios') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-100">
                Voltar
            </a>
            <button type="submit" class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

<!-- Dados para o JS (idêntico ao index) -->
<div id="profData"
    data-professores='@json($professores)'
    data-horarios='@json($horariosTreino)'>
</div>

<!-- Valores atuais da grade para pré-carregar -->
<div id="gradeAtual"
    data-professor="{{ $grade->professor_id_professor }}"
    data-modalidade="{{ $grade->grade_modalidade }}"
    data-horario="{{ $grade->horario_treino_id_hora }}"
    data-dia="{{ $grade->grade_dia_semana }}"
    data-inicio="{{ substr($grade->grade_inicio, 0, 5) }}"
    data-fim="{{ substr($grade->grade_fim, 0, 5) }}">
</div>

<script>
    const profData = JSON.parse(document.getElementById('profData').dataset.professores);
    const horariosData = JSON.parse(document.getElementById('profData').dataset.horarios);

    const gradeAtual = document.getElementById('gradeAtual').dataset;

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

    function popularModalidades(profId, modalidadeSelecionada = null, silencioso = false) {
        modalidadeSelect.innerHTML = `<option value="">Selecione a modalidade</option>`;
        modalidadeSelect.disabled = true;
        horarioSelect.innerHTML = `<option value="">Selecione o horário</option>`;
        horarioSelect.disabled = true;
        resetCampos();

        const prof = profData.find(p => p.id_professor == profId);
        if (!prof) return;

        const vistas = new Set();
        prof.detalhes.forEach(d => {
            const modNome = d.graduacao?.modalidade?.mod_nome;
            if (modNome && !vistas.has(modNome)) {
                vistas.add(modNome);
                const opt = document.createElement('option');
                opt.value = modNome;
                opt.textContent = modNome;
                if (modNome === modalidadeSelecionada) opt.selected = true;
                modalidadeSelect.appendChild(opt);
            }
        });

        if (vistas.size === 0) {
            if (!silencioso) {
                alert(`O professor ${prof.prof_nome} não possui modalidade cadastrada, cadastre para prosseguir`);
                professorSelect.value = '';
            }
            return;
        }

        modalidadeSelect.disabled = false;
    }

    function popularHorarios(modNome, horarioSelecionado = null, silencioso = false) {
        horarioSelect.innerHTML = `<option value="">Selecione o horário</option>`;
        horarioSelect.disabled = true;
        resetCampos();

        if (!modNome) return;

        const filtrados = horariosData.filter(h => h.hora_modalidade === modNome);

        if (filtrados.length === 0) {
            if (!silencioso) {
                alert(`Nenhum horário disponível para a modalidade "${modNome}".`);
            }
            return;
        }

        filtrados.forEach(h => {
            const opt = document.createElement('option');
            opt.value = h.id_hora;
            opt.dataset.dia = h.hora_semana;
            opt.dataset.inicio = h.hora_inicio;
            opt.dataset.fim = h.hora_fim;
            opt.textContent = `${h.hora_modalidade} | ${h.hora_semana} | ${h.hora_inicio.substring(0,5)} - ${h.hora_fim.substring(0,5)}`;
            if (h.id_hora == horarioSelecionado) opt.selected = true;
            horarioSelect.appendChild(opt);
        });

        horarioSelect.disabled = false;
    }

    function preencherCampos(opt) {
        if (!opt || !opt.value) {
            resetCampos();
            return;
        }
        const diasArray = opt.dataset.dia.split(',').map(d => d.trim());
        diaNumero.value = diasArray.join(',');
        diaTexto.value = diasArray.map(d => mapaDias[d]).filter(Boolean).join(', ');
        inicio.value = opt.dataset.inicio.substring(0, 5);
        fim.value = opt.dataset.fim.substring(0, 5);
    }

    window.addEventListener('load', () => {
        popularModalidades(gradeAtual.professor, gradeAtual.modalidade, true);
        popularHorarios(gradeAtual.modalidade, gradeAtual.horario, true);

        diaNumero.value = gradeAtual.dia;
        diaTexto.value = gradeAtual.dia.split(',').map(d => mapaDias[d.trim()]).filter(Boolean).join(', ');
        inicio.value = gradeAtual.inicio;
        fim.value = gradeAtual.fim;
    });

    professorSelect.addEventListener('change', () => {
        popularModalidades(professorSelect.value, null, false);
    });

    modalidadeSelect.addEventListener('change', () => {
        popularHorarios(modalidadeSelect.value, null, false);
    });

    horarioSelect.addEventListener('change', () => {
        preencherCampos(horarioSelect.options[horarioSelect.selectedIndex]);
    });
</script>

@endsection