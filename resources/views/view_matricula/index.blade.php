@extends('layouts.dashboard')

@section('title', 'Matrículas do Aluno')

@section('content')

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('alunos', $aluno->responsavel_id_responsavel) }}"
                class="hover:text-[#8E251F] transition">
                Alunos
            </a>
        </li>
        <li>/</li>
        <li class="text-gray-400">{{ $aluno->aluno_nome }}</li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Matrícula</li>
    </ol>
</nav>

@if ($errors->any())
<div class="bg-red-100 text-red-700 p-3 rounded mb-3">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">

        <a href="{{ route('alunos', $aluno->responsavel_id_responsavel) }}"
            class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>

        <h2 class="text-3xl font-extrabold text-gray-800">
            Matrículas do Aluno
        </h2>
    </div>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] transition">
        + Nova Matrícula
    </button>
</div>


<!-- CARD DO ALUNO -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#8E251F] rounded-2xl shadow-lg p-6">
        <p class="text-xs uppercase tracking-widest text-gray-500">
            Aluno selecionado
        </p>

        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
            {{ $aluno->aluno_nome }}
        </h3>

        <p class="mt-2 text-sm text-gray-600">
            Data de nascimento:
            <strong class="text-gray-800">
                {{ \Carbon\Carbon::parse($aluno->aluno_nascimento)->format('d/m/Y') }}
            </strong>
        </p>
    </div>
</div>

<!-- FORMULÁRIO -->
<div id="cadastroForm" class="hidden mb-10">
    <form action="{{ route('matricula.store', $aluno->id_aluno) }}" method="POST">
        @csrf

        <div class="bg-white rounded-2xl shadow-xl p-8">

            <h3 class="text-xl font-bold text-gray-700 mb-6">
                Nova Matrícula
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div>
                    <label class="text-sm font-medium text-gray-600">Plano</label>
                    <select name="matri_plano" required
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                        <option value="">Selecione</option>
                        <option value="Mensal">Mensal</option>
                        <option value="Trimestral">Trimestral</option>
                        <option value="Semestral">Semestral</option>
                        <option value="Anual">Anual</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Data</label>
                    <input type="date" name="matri_data" required
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                </div>


                <!-- MODALIDADE -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Modalidade</label>
                    <select id="modalidadeSelect"
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                        <option value="">Selecione a modalidade</option>
                        @foreach ($grades->pluck('grade_modalidade')->unique() as $modalidade)
                        <option value="{{ $modalidade }}">
                            {{ $modalidade }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- TURMA -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Turma</label>
                    <select id="turmaSelect"
                        class="w-full border rounded-lg px-4 py-2 mt-1" disabled>
                        <option value="">Selecione a turma</option>
                    </select>
                </div>

                <!-- GRADE FINAL (ESSA ENVIA PRO STORE) -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Horário</label>
                    <select name="grade_id_grade" id="gradeSelect" required
                        class="w-full border rounded-lg px-4 py-2 mt-1" disabled>
                        <option value="">Selecione o horário</option>
                    </select>
                </div>


                <div class="md:col-span-3">
                    <label class="text-sm font-medium text-gray-600">Observações</label>
                    <textarea name="matri_desc" rows="3" placeholder="Ex.: Matrícula anual realizada sem bolsa"
                        class="w-full border rounded-lg px-4 py-2 mt-1"></textarea>
                </div>

            </div>

            <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                <button type="button" onclick="fecharCadastro()"
                    class="px-5 py-2 border rounded-lg hover:bg-gray-100">
                    Cancelar
                </button>

                <button type="submit"
                    class="px-6 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">
                    Cadastrar
                </button>
            </div>

        </div>
    </form>
</div>

<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">
        Histórico de Matrículas
    </h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Plano</th>
                <th class="py-3 px-4">Professor</th>
                <th class="py-3 px-4">Turma</th>
                <th class="py-3 px-4">Modalidade</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($matriculas as $matricula)
            <tr class="border-b hover:bg-gray-50 transition">

                <td class="py-3 px-4">
                    {{ $matricula->matri_plano }}
                </td>

                <td class="py-3 px-4">
                    {{ $matricula->grade->professor->prof_nome ?? '-' }}
                </td>

                <td class="py-3 px-4">
                    @if($matricula->grade)
                    {{ ucfirst($matricula->grade->grade_turma) }}
                    <span class="text-xs text-gray-500 block">
                        {{ \Carbon\Carbon::parse($matricula->grade->grade_inicio)->format('H:i') }}
                        às
                        {{ \Carbon\Carbon::parse($matricula->grade->grade_fim)->format('H:i') }}
                    </span>
                    @else
                    -
                    @endif
                </td>

                <td class="py-3 px-4">
                    {{ $matricula->grade->grade_modalidade ?? '-' }}
                </td>

                <td class="py-3 px-4">
                    @if ($matricula->matri_status === 'Matriculado')
                    <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                        Matriculado
                    </span>
                    @else
                    <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700">
                        Encerrada
                    </span>
                    @endif
                </td>

                <td class="py-3 px-4 flex gap-2">

                    <a href="{{ route('matricula.show', $matricula->id_matricula) }}"
                        style="background-color: #174ab9; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                        Detalhes
                    </a>

                    @if(strtolower($aluno->aluno_bolsista) !== 'sim')
                    <a href="{{ route('mensalidade', [
                            'id' => $aluno->id_aluno,
                            'matricula' => $matricula->id_matricula
                        ]) }}"
                        style="background-color: #15803d; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#166534] transition duration-200 text-center"> Financeiro
                    </a>
                    @endif

                    @if ($matricula->matri_status === 'Matriculado')
                    <form action="{{ route('matricula.destroy', $matricula->id_matricula) }}"
                        method="POST"
                        onsubmit="return confirm('Deseja encerrar esta matrícula?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 rounded-lg shadow text-white bg-red-600 hover:bg-red-700 transition">
                            Encerrar
                        </button>
                    </form>
                    @endif

                </td>

            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-6 text-gray-500">
                    Nenhuma matrícula encontrada
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="gradesData"
    data-grades='@json($grades)'>
</div>

<script>
    function toggleCadastro() {
        document.getElementById('cadastroForm').classList.toggle('hidden');
        document.getElementById('cadastroForm').scrollIntoView({
            behavior: 'smooth'
        });
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }

    const gradesElement = document.getElementById('gradesData');
    const grades = JSON.parse(gradesElement.dataset.grades);

    const modalidadeSelect = document.getElementById('modalidadeSelect');
    const turmaSelect = document.getElementById('turmaSelect');
    const gradeSelect = document.getElementById('gradeSelect');

    // FILTRO MODALIDADE
    modalidadeSelect.addEventListener('change', function() {

        turmaSelect.innerHTML = '<option value="">Selecione a turma</option>';
        gradeSelect.innerHTML = '<option value="">Selecione o horário</option>';
        gradeSelect.disabled = true;

        if (!this.value) {
            turmaSelect.disabled = true;
            return;
        }

        const turmasFiltradas = [...new Set(
            grades
            .filter(g => g.grade_modalidade === this.value)
            .map(g => g.grade_turma)
        )];

        turmasFiltradas.forEach(turma => {

            let textoExibicao = turma;

            if (turma.toLowerCase() === 'criancas') {
                textoExibicao = 'Crianças';
            }
            if (turma.toLowerCase() === 'adultos') {
                textoExibicao = 'Adultos';
            }
            if (turma.toLowerCase() === 'mulheres') {
                textoExibicao = 'Mulheres';
            }

            turmaSelect.innerHTML += `
                <option value="${turma}">
                    ${textoExibicao}
                </option>
            `;
        });

        turmaSelect.disabled = false;
    });

    // FILTRO TURMA
    turmaSelect.addEventListener('change', function() {

        gradeSelect.innerHTML = '<option value="">Selecione o horário</option>';

        if (!this.value) {
            gradeSelect.disabled = true;
            return;
        }

        const resultado = grades.filter(g =>
            g.grade_modalidade === modalidadeSelect.value &&
            g.grade_turma === this.value
        );

        resultado.forEach(grade => {

            gradeSelect.innerHTML += `
                <option value="${grade.id_grade}">
                    Prof. ${grade.professor ? grade.professor.prof_nome : '-'}
                    (${grade.grade_inicio.substring(0,5)} às ${grade.grade_fim.substring(0,5)})
                </option>
            `;
        });

        gradeSelect.disabled = false;
    });
</script>
@endsection