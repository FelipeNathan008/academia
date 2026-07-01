@extends('layouts.dashboard')

@section('title', 'Matrículas do Aluno')

@section('content')

<x-alert-error />

<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('responsaveis') }}"
                class="hover:text-[#8E251F] transition">
                Responsáveis
            </a>
        </li>
        <li>/</li>
        <li class="text-gray-400">{{ $aluno->responsavel->resp_nome }}</li>

        <li>/</li>
        <li>
            <a href="{{ route('alunos', Crypt::encrypt($aluno->responsavel_id_responsavel)) }}"
                class="hover:text-[#8E251F] transition">
                Alunos
            </a>
        </li>
        <li>/</li>
        <li class="text-gray-400">{{ $aluno->aluno_nome }}</li>
        <li>/</li>
        <li>
            <a href="{{ route('detalhes-aluno.index', Crypt::encrypt($aluno->id_aluno)) }}" class="hover:text-[#8E251F] transition">
                Graduações
            </a>
        </li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Matrícula</li>
        <li>/</li>
        <li class="text-gray-400">Financeiro</li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">

        <a href="{{ route('alunos', Crypt::encrypt($aluno->responsavel_id_responsavel)) }}"
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

        <div class="flex items-center gap-3 mt-1">
            <h3 class="text-2xl font-extrabold text-gray-800">
                {{ $aluno->aluno_nome }}
            </h3>

            {{-- BADGE BOLSISTA --}}
            @if(strtolower($aluno->aluno_bolsista) === 'sim')
            <span class="px-3 py-1 rounded-full text-xs font-semibold"
                style="background-color: #fef9c3; color: #854d0e;">
                🎓 Bolsista
            </span>
            @else
            <span class="px-3 py-1 rounded-full text-xs font-semibold"
                style="background-color: #f3f4f6; color: #6b7280;">
                Não bolsista
            </span>
            @endif
        </div>

        <p class="mt-2 text-sm text-gray-600">
            Data de nascimento:
            <strong class="text-gray-800">
                {{ \Carbon\Carbon::parse($aluno->aluno_nascimento)->format('d/m/Y') }}
            </strong> <br>
            Idade:
            <strong class="text-gray-800">
                {{ $aluno->aluno_nascimento ? \Carbon\Carbon::parse($aluno->aluno_nascimento)->age : '-' }}
            </strong>
        </p>
    </div>
</div>

<!-- FORMULÁRIO -->
<div id="cadastroForm" class="hidden mb-10">
    <form action="{{ route('matricula.store', Crypt::encrypt($aluno->id_aluno)) }}" method="POST" onsubmit="bloquearSubmit(event, this)">
        @csrf

        <div class="bg-white rounded-2xl shadow-xl p-8">

            <h3 class="text-xl font-bold text-gray-700 mb-6">
                Nova Matrícula
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- MODALIDADE -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Modalidade</label>
                    <select id="modalidadeSelect" required
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                        <option value="">Selecione a modalidade</option>
                        @foreach ($grades->pluck('grade_modalidade')->unique() as $modalidade)
                        <option value="{{ $modalidade }}">
                            {{ $modalidade }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Plano</label>

                    <select name="matri_plano"
                        id="planoSelect"
                        required
                        disabled
                        class="w-full border rounded-lg px-4 py-2 mt-1">

                        <option value="">Selecione a modalidade primeiro</option>

                    </select>
                </div>


                <div>
                    <label class="text-sm font-medium text-gray-600">Data</label>
                    <input
                        type="date"
                        name="matri_data"
                        required
                        min="{{ $aluno->aluno_nascimento }}"
                        max="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                        class="w-full border rounded-lg px-4 py-2 mt-1" />
                </div>


                <!-- TURMA -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Turma</label>
                    <select id="turmaSelect" required
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
                    <label class="text-sm font-medium text-gray-600">Observações </label>
                    <textarea name="matri_desc" required rows="3" placeholder="Ex.: Matrícula anual realizada sem bolsa"
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
        HISTÓRICO DE MATRÍCULAS
    </h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Turma</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($matriculas as $matricula)
            <tr class="border-b hover:bg-gray-50 transition">

                <td class="py-4 px-4">

                    <p class="font-semibold text-gray-800">
                        {{ ucfirst($matricula->grade->grade_turma ?? '-') }}
                    </p>

                    <p class="text-sm text-gray-600">
                        {{ $matricula->grade->grade_modalidade ?? '-' }}
                    </p>

                    <p class="text-sm text-gray-500">
                        Plano:
                        <strong>{{ $matricula->matri_plano }}</strong>
                    </p>

                    <p class="text-sm text-gray-500">
                        Prof.
                        {{ $matricula->grade->professor->prof_nome ?? '-' }}
                    </p>

                    @if($matricula->grade)
                    <p class="text-xs text-gray-500 mt-1">
                        {{ \Carbon\Carbon::parse($matricula->grade->grade_inicio)->format('H:i') }}
                        às
                        {{ \Carbon\Carbon::parse($matricula->grade->grade_fim)->format('H:i') }}
                    </p>
                    @endif

                </td>


                <!-- STATUS -->
                <td class="py-3 px-4">
                    @if ($matricula->matri_status === 'Matriculado')
                    <span class="px-2 py-1 rounded-full text-xs font-semibold"
                        style="background-color: #dcfce7; color: #166534;">
                        Matriculado
                    </span>
                    @elseif ($matricula->matri_status === 'Pausada')
                    <span class="px-2 py-1 rounded-full text-xs font-semibold"
                        style="background-color: #fef9c3; color: #854d0e;">
                        Pausada
                    </span>

                    @else
                    <span class="px-2 py-1 rounded-full text-xs font-semibold"
                        style="background-color: #fee2e2; color: #991b1b;">
                        Encerrada
                    </span>

                    @endif
                </td>

                <!-- AÇÕES -->
                <td class="py-3 px-4 flex gap-2 flex-wrap">

                    <a href="{{ route('matricula.show', Crypt::encrypt($matricula->id_matricula)) }}"
                        style="background-color: #174ab9; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition text-center">
                        Ver Matrícula
                    </a>

                    @if(strtolower($aluno->aluno_bolsista) !== 'sim')
                    <a href="{{ route('mensalidade', Crypt::encrypt($matricula->aluno_id_aluno)) }}"
                        style="background-color: #15803d; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#166534] transition text-center">
                        Financeiro
                    </a>
                    @endif

                    @if ($matricula->matri_status === 'Matriculado')

                    <!-- PAUSAR -->
                    <button type="button"
                        onclick="abrirModalMotivo('{{ Crypt::encrypt($matricula->id_matricula) }}', 'pausar', '{{ $matricula->matri_data }}')"
                        class="px-4 py-2 rounded-lg shadow text-white bg-red-600 hover:bg-red-700 transition">
                        Pausar
                    </button>

                    <!-- ENCERRAR -->
                    <button type="button"
                        onclick="abrirModalMotivo('{{ Crypt::encrypt($matricula->id_matricula) }}', 'encerrar', '{{ $matricula->matri_data_pausa ?? $matricula->matri_data }}')"
                        style="background-color: #7c3aed; color: white;"
                        class="px-4 py-3 rounded-lg shadow hover:opacity-90 transition text-center font-semibold">
                        Encerrar
                    </button>

                    @endif

                    {{-- PAUSADA: pode reativar ou encerrar --}}
                    @if ($matricula->matri_status === 'Pausada')

                    <form action="{{ route('matricula.reativar', Crypt::encrypt($matricula->id_matricula)) }}"
                        method="POST"
                        onsubmit="return confirm('Deseja reativar esta matrícula?')">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                            class="px-4 py-2 rounded-lg shadow text-white"
                            style="background:#8E251F;"> Reativar
                        </button>
                    </form>

                    <!-- ENCERRAR -->
                    <button type="button"
                        onclick="abrirModalMotivo('{{ Crypt::encrypt($matricula->id_matricula) }}', 'encerrar', '{{ $matricula->matri_data_pausa ?? $matricula->matri_data }}')"
                        style="background-color: #7c3aed; color: white;"
                        class="px-4 py-3 rounded-lg shadow hover:opacity-90 transition text-center font-semibold">
                        Encerrar
                    </button>

                    @endif

                    @if ($matricula->matri_status === 'Encerrada')
                    <form action="{{ route('matricula.destroy', Crypt::encrypt($matricula->id_matricula)) }}"
                        method="POST"
                        onsubmit="return confirm('Deseja remover esta matrícula?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 rounded-lg shadow text-white bg-red-600 hover:bg-red-700 transition">
                            Excluir
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

    <!-- MODAL MOTIVO (pausar/encerrar) -->
    <div id="modalMotivo" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md">
            <h3 id="modalMotivtitulo" class="text-xl font-bold mb-4 text-gray-800">Informe o motivo</h3>

            <form id="formMotivo" method="POST" onsubmit="bloquearSubmit(event, this)">
                @csrf
                @method('PUT')

                <textarea name="matri_motivo" rows="4" required
                    placeholder="Descreva o motivo..."
                    class="w-full border rounded-lg px-4 py-2 mb-4"></textarea>

                <label id="modalDataLabel" class="text-sm font-medium text-gray-600">
                    Data do evento
                </label>
                <input
                    type="date"
                    name="matri_data_evento"
                    id="modalDataEvento"
                    required
                    class="w-full border rounded-lg px-4 py-2 mt-1 mb-6" />

                <div class="flex justify-end gap-4">
                    <button type="button" onclick="fecharModalMotivo()"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-100">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">
                        Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalMotivo(idCriptografado, acao, dataMinima) {
            const modal = document.getElementById('modalMotivo');
            const form = document.getElementById('formMotivo');
            const titulo = document.getElementById('modalMotivtitulo');
            const labelData = document.getElementById('modalDataLabel');
            const inputData = document.getElementById('modalDataEvento');
            const baseUrl = "{{ url('matricula') }}";

            // limpa restrições anteriores
            inputData.removeAttribute('max');
            inputData.removeAttribute('min');

            if (acao === 'pausar') {
                form.action = `${baseUrl}/${idCriptografado}/pausar`;
                titulo.textContent = 'Motivo da Pausa';
                labelData.textContent = 'Data da Pausa';

                // pausa não pode ser futura
                const hoje = new Date().toISOString().split('T')[0];
                inputData.max = hoje;

                if (dataMinima) {
                    inputData.min = dataMinima;
                }
            } else {
                form.action = `${baseUrl}/${idCriptografado}/encerrar`;
                titulo.textContent = 'Motivo do Encerramento';
                labelData.textContent = 'Data do Encerramento';

                // encerramento PODE ser futuro — sem max
                if (dataMinima) {
                    inputData.min = dataMinima;
                }
            }

            modal.classList.remove('hidden');
        }

        function fecharModalMotivo() {
            const modal = document.getElementById('modalMotivo');
            const form = document.getElementById('formMotivo');

            modal.classList.add('hidden');
            form.reset();
            form.querySelector('#modalDataEvento').removeAttribute('max');
            form.querySelector('#modalDataEvento').removeAttribute('min');
        }
    </script>

</div>

<div id="gradesData"
    data-grades='@json($grades)'>
</div>

<div id="precosData"
    data-precos='@json($precos)'>
</div>

<script>
    function bloquearSubmit(event, form) {

        console.log("Entrou");

        console.log(form.checkValidity());

        const btn = form.querySelector('button[type="submit"]');

        if (btn) {
            btn.disabled = true;
            btn.innerText = "Salvando...";
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

    const gradesElement = document.getElementById('gradesData');
    const grades = JSON.parse(gradesElement.dataset.grades);

    const modalidadeSelect = document.getElementById('modalidadeSelect');
    const turmaSelect = document.getElementById('turmaSelect');
    const gradeSelect = document.getElementById('gradeSelect');

    const precosElement = document.getElementById('precosData');
    const precos = JSON.parse(precosElement.dataset.precos);

    const planoSelect = document.getElementById('planoSelect');

    // FILTRO MODALIDADE
    modalidadeSelect.addEventListener('change', function() {

        planoSelect.innerHTML =
            '<option value="">Selecione o plano</option>';
        planoSelect.disabled = true;

        turmaSelect.innerHTML = '<option value="">Selecione a turma</option>';
        gradeSelect.innerHTML = '<option value="">Selecione o horário</option>';
        gradeSelect.disabled = true;

        if (!this.value) {
            turmaSelect.disabled = true;
            return;
        }

        const modalidadeSelecionada = this.value;

        const planosDisponiveis = [
            ...new Set(
                precos
                .filter(p =>
                    p.modalidade &&
                    p.modalidade.mod_nome === modalidadeSelecionada
                )
                .map(p => p.preco_plano)
            )
        ];

        if (planosDisponiveis.length > 0) {

            planoSelect.disabled = false;

            planosDisponiveis.forEach(plano => {

                planoSelect.innerHTML += `
            <option value="${plano}">
                ${plano}
            </option>
        `;
            });

        } else {

            planoSelect.innerHTML = `
        <option value="">
            Nenhum plano cadastrado para esta modalidade
        </option>
        `;

            planoSelect.disabled = true;
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