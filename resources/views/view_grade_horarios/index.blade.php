@extends('layouts.dashboard')

@section('title', 'Grade de Horários')

@section('content')

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <h2 class="text-3xl font-extrabold text-gray-800">Grade de Horários</h2>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow hover:bg-[#732920] transition">
        + Cadastrar Horário
    </button>
</div>

@if ($errors->any())
<div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
    <ul class="list-disc list-inside">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- FORMULÁRIO -->
<div id="cadastroForm" class="hidden mb-10">
    <form id="formCadastro" action="{{ route('grade_horarios.store') }}" method="POST">
        @csrf

        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">Cadastrar Horário</h3>

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

                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-600">Modalidade</label>
                    <select id="modalidadeSelect" name="grade_modalidade" disabled required
                        class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100">
                        <option value="">Selecione a modalidade</option>
                        @foreach ($horariosTreino->unique('hora_modalidade') as $h)
                        <option value="{{ $h->hora_modalidade }}">{{ $h->hora_modalidade }}</option>
                        @endforeach
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
                    <option value="adultos">Adultos</option>
                    <option value="criancas">Crianças</option>
                    <option value="mulheres">Mulheres</option>
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

@include('view_grade_horarios.agenda_semanal')

<!-- JS -->
<script>
    const professor = document.getElementById('professorSelect');
    const modalidade = document.getElementById('modalidadeSelect');
    const horario = document.getElementById('horarioTreinoSelect');

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

    function toggleCadastro() {
        cadastroForm.classList.toggle('hidden');
        formCadastro.reset();
        resetCampos();
    }

    function fecharCadastro() {
        cadastroForm.classList.add('hidden');
    }

    function resetCampos() {
        diaTexto.value = '';
        diaNumero.value = '';
        inicio.value = '';
        fim.value = '';
    }

    professor.addEventListener('change', () => {
        modalidade.disabled = !professor.value;
        horario.disabled = true;
        resetCampos();
    });

    modalidade.addEventListener('change', () => {
        horario.disabled = !modalidade.value;
        [...horario.options].forEach(o => {
            if (!o.dataset.modalidade) return;
            o.hidden = o.dataset.modalidade !== modalidade.value;
        });
        resetCampos();
    });

    horario.addEventListener('change', () => {
        const opt = horario.options[horario.selectedIndex];
        if (!opt.value) {
            resetCampos();
            return;
        }

        const diasArray = opt.dataset.dia.split(',').map(d => d.trim());

        diaNumero.value = diasArray.join(',');

        diaTexto.value = diasArray
            .map(d => mapaDias[d])
            .filter(Boolean)
            .join(', ');

        inicio.value = opt.dataset.inicio;
        fim.value = opt.dataset.fim;
    });
</script>
@endsection