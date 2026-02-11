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
        <li class="font-semibold text-gray-700">Matrículas</li>
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

<!-- FORMULÁRIO (ESCONDIDO) -->
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

                <div>
                    <label class="text-sm font-medium text-gray-600">Professor</label>
                    <select name="professor_id" id="professor"
                        class="w-full border rounded-lg px-4 py-2 mt-1" required>

                        <option value="">Selecione o professor</option>

                        @foreach ($professores as $prof)
                        <option value="{{ $prof->id_professor }}">
                            {{ $prof->prof_nome }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Turma</label>
                    <select name="matri_turma" id="turma"
                        class="w-full border rounded-lg px-4 py-2 mt-1" required>
                        <option value="">Selecione um professor primeiro</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Dias da Turma</label>
                    <input type="text" id="dias_turma"
                        class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100"
                        readonly>
                </div>


                <div class="md:col-span-3">
                    <label class="text-sm font-medium text-gray-600">Observações</label>
                    <textarea name="matri_desc" rows="3"
                        placeholder="Ex: Matrícula anual, bolsa parcial, condições especiais..."
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

                <!-- Plano -->
                <td class="py-3 px-4">
                    {{ $matricula->matri_plano }}
                </td>

                <!-- Professor -->
                <td class="py-3 px-4">
                    {{ $matricula->professor->prof_nome ?? '-' }}
                </td>

                <!-- Turma -->
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

                <!-- MODALIDADE (NOVA) -->
                <td class="py-3 px-4">
                    @if($matricula->grade && $matricula->grade->grade_modalidade)
                    {{ ucfirst($matricula->grade->grade_modalidade) }}
                    @else
                    -
                    @endif
                </td>

                <!-- Status -->
                <td class="py-3 px-4">
                    @if ($matricula->matri_status === 'Ativa')
                    <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                        Ativa
                    </span>
                    @else
                    <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700">
                        Encerrada
                    </span>
                    @endif
                </td>

                <!-- Ações -->
                <td class="py-3 px-4 flex gap-2">

                    <a href="{{ route('matricula.show', $matricula->id_matricula) }}"
                        style="background-color: #275cce; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1d4ed8] transition duration-200 text-center">
                        Detalhes
                    </a>

                    <a href="{{ route('financeiro.index', $matricula->id_matricula) }}"
                        style="background-color: #15803d; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#166534] transition duration-200 text-center">
                        Financeiro
                    </a>

                    @if ($matricula->matri_status === 'Ativa')
                    <form action="{{ route('matricula.destroy', $matricula->id_matricula) }}"
                        method="POST"
                        onsubmit="return confirm('Deseja encerrar esta matrícula?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            style="background-color: #c02600; color: white;"
                            class="px-4 py-2 rounded-lg shadow hover:bg-[#991b1b] transition duration-200">
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

    const professorSelect = document.getElementById('professor');
    const turmaSelect = document.getElementById('turma');
    const diasInput = document.getElementById('dias_turma');

    // Quando troca o professor
    professorSelect.addEventListener('change', function() {

        const professorId = this.value;

        turmaSelect.innerHTML = '<option>Carregando...</option>';
        diasInput.value = '';

        if (!professorId) {
            turmaSelect.innerHTML =
                '<option value="">Selecione um professor primeiro</option>';
            return;
        }

        fetch(`/professor/${professorId}/turmas`)
            .then(res => res.json())
            .then(data => {

                turmaSelect.innerHTML = '';

                if (data.length === 0) {
                    turmaSelect.innerHTML =
                        '<option value="">Nenhuma turma disponível</option>';
                    return;
                }

                turmaSelect.innerHTML =
                    '<option value="">Selecione a turma</option>';

                data.forEach(grade => {

                    const option = document.createElement('option');
                    option.value = grade.id_grade;

                    const nomesTurmas = {
                        criancas: 'Crianças',
                        adultos: 'Adultos',
                        mulheres: 'Mulheres'
                    };

                    const nomeFormatado =
                        nomesTurmas[grade.grade_turma] ??
                        grade.grade_turma.charAt(0).toUpperCase() +
                        grade.grade_turma.slice(1);

                    const diasSemana = {
                        1: 'Domingo',
                        2: 'Segunda-feira',
                        3: 'Terça-feira',
                        4: 'Quarta-feira',
                        5: 'Quinta-feira',
                        6: 'Sexta-feira',
                        7: 'Sábado'
                    };

                    let diasFormatados = '';

                    if (grade.grade_dia_semana) {
                        const diasArray =
                            grade.grade_dia_semana.toString().split(',');

                        const diasTraduzidos = diasArray.map(dia => {
                            return diasSemana[dia.trim()] ?? dia;
                        });

                        diasFormatados =
                            diasTraduzidos.join(', ');
                    }

                    const horaInicio = grade.grade_inicio.slice(0, 5);
                    const horaFim = grade.grade_fim.slice(0, 5);

                    const modalidade = grade.grade_modalidade ?
                        ` • ${grade.grade_modalidade}` :
                        '';

                    option.textContent =
                        `${nomeFormatado}${modalidade} (${horaInicio} às ${horaFim})`;
                    option.setAttribute('data-dias', diasFormatados);

                    turmaSelect.appendChild(option);
                });
            })
            .catch(() => {
                turmaSelect.innerHTML =
                    '<option value="">Erro ao carregar turmas</option>';
            });
    });

    // Quando troca a turma
    turmaSelect.addEventListener('change', function() {

        const selectedOption = this.options[this.selectedIndex];
        const dias = selectedOption.getAttribute('data-dias');

        diasInput.value = dias ?? '';
    });
</script>
@endsection