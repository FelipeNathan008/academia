@extends('layouts.dashboard')

@section('title', 'Editar Horário da Grade')

@section('content')
@if ($errors->any())
<div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
    <ul class="list-disc list-inside">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Editar Horário ({{ $grade->grade_modalidade }} - {{ $grade->grade_dia_semana }})
    </h2>

    <form action="{{ route('grade_horarios.update', $grade->id_grade) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- PROFESSOR + MODALIDADE -->
        <div class="flex gap-6 mb-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Professor</label>
                <select id="professorSelect" name="professor_id_professor" required
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
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
                <select id="modalidadeSelect"
                    class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100">
                    <option value="">Selecione a modalidade</option>
                    @foreach ($horariosTreino->unique('hora_modalidade') as $h)
                    <option value="{{ $h->hora_modalidade }}"
                        {{ $grade->grade_modalidade == $h->hora_modalidade ? 'selected' : '' }}>
                        {{ $h->hora_modalidade }}
                    </option>
                    @endforeach
                </select>

            </div>
        </div>

        <!-- HORÁRIO -->
        <div class="mb-4">
            <label class="text-sm font-medium text-gray-600">Horário Treino</label>
            <select id="horarioTreinoSelect" name="horario_treino_id_hora" required
                class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                <option value="">Selecione o horário</option>
                @foreach ($horariosTreino as $hora)
                <option value="{{ $hora->id_hora }}"
                    data-modalidade="{{ $hora->hora_modalidade }}"
                    data-dia="{{ $hora->hora_semana }}"
                    data-inicio="{{ $hora->hora_inicio }}"
                    data-fim="{{ $hora->hora_fim }}"
                    {{ $grade->horario_treino_id_hora == $hora->id_hora ? 'selected' : '' }}>
                    {{ $hora->hora_semana }} | {{ $hora->hora_inicio }} - {{ $hora->hora_fim }}
                </option>
                @endforeach
            </select>

        </div>

        <!-- DIA / INICIO / FIM -->
        <div class="flex gap-6 mb-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Dia</label>
                <input id="gradeDia" name="grade_dia_semana" readonly
                    class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100"
                    value="{{ $grade->grade_dia_semana }}">
            </div>

            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Início</label>
                <input id="gradeInicio" name="grade_inicio" readonly
                    class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100"
                    value="{{ $grade->grade_inicio }}">
            </div>

            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Fim</label>
                <input id="gradeFim" name="grade_fim" readonly
                    class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100"
                    value="{{ $grade->grade_fim }}">
            </div>
        </div>

        <!-- TURMA -->
        <div class="mb-4">
            <label class="text-sm font-medium text-gray-600">Turma</label>
            <select name="grade_turma" required
                class="w-full border rounded-lg px-4 py-2 mt-1">
                <option value="adultos" {{ $grade->grade_turma=='adultos'?'selected':'' }}>Adultos</option>
                <option value="criancas" {{ $grade->grade_turma=='criancas'?'selected':'' }}>Crianças</option>
                <option value="mulheres" {{ $grade->grade_turma=='mulheres'?'selected':'' }}>Mulheres</option>
            </select>
        </div>

        <!-- DESCRIÇÃO -->
        <div class="mb-6">
            <label class="text-sm font-medium text-gray-600">Descrição</label>
            <textarea name="grade_desc" rows="3" required
                class="w-full border rounded-lg px-4 py-2 mt-1">{{ $grade->grade_desc }}</textarea>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('grade_horarios') }}"
                class="px-4 py-2 border rounded-lg hover:bg-gray-100">
                Voltar
            </a>

            <button type="submit"
                class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

<!-- JS -->
<script>
    const modalidade = document.getElementById('modalidadeSelect');
    const horario = document.getElementById('horarioTreinoSelect');
    const dia = document.getElementById('gradeDia');
    const inicio = document.getElementById('gradeInicio');
    const fim = document.getElementById('gradeFim');

    window.addEventListener('load', () => {
        filtrarHorarios();

        const opt = horario.options[horario.selectedIndex];
        if (!opt || !opt.value) return;

        dia.value = opt.dataset.dia;
        inicio.value = opt.dataset.inicio;
        fim.value = opt.dataset.fim;
    });


    function filtrarHorarios() {
        [...horario.options].forEach(o => {
            if (!o.dataset.modalidade) return;
            o.hidden = o.dataset.modalidade !== modalidade.value;
        });
    }

    modalidade.addEventListener('change', () => {
        horario.value = '';
        dia.value = '';
        inicio.value = '';
        fim.value = '';
        filtrarHorarios();
    });

    horario.addEventListener('change', () => {
        const opt = horario.options[horario.selectedIndex];
        if (!opt || !opt.value) return;

        dia.value = opt.dataset.dia;
        inicio.value = opt.dataset.inicio;
        fim.value = opt.dataset.fim;
    });
</script>


@endsection