@extends('layouts.dashboard')

@section('title', 'Grade de Horário do Visitante')

@section('content')

<x-alert-error />

<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('visitantes') }}" class="hover:text-[#8E251F] transition">
                Visitantes
            </a>
        </li>
        <li>/</li>
        <li class="text-gray-400">{{ $visitante->vis_nome }}</li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Grade de Horário</li>
    </ol>
</nav>

<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('visitantes') }}"
            class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>

        <h2 class="text-3xl font-extrabold text-gray-800">
            Grade de Horário
        </h2>
    </div>

    <button type="button" onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition">
        + Nova Grade
    </button>
</div>

<div class="mb-8">
    <div class="bg-white border-l-8 border-[#8E251F] rounded-2xl shadow-lg p-6">
        <p class="text-xs uppercase tracking-widest text-gray-500">
            Visitante selecionado
        </p>

        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
            {{ $visitante->vis_nome }}
        </h3>

        <p class="mt-2 text-sm text-gray-600">
            Data de nascimento:
            <strong class="text-gray-800">
                {{ $visitante->vis_data_nasc ? $visitante->vis_data_nasc->format('d/m/Y') : '-' }}
            </strong>

            <br>

            Idade:
            <strong class="text-gray-800">
                {{ $visitante->vis_data_nasc ? $visitante->vis_data_nasc->age : '-' }}
            </strong>
        </p>
    </div>
</div>

<div id="cadastroForm" class="{{ $errors->any() ? '' : 'hidden' }} mb-10">
    <form action="{{ route('visitantes.grade.store', Crypt::encrypt($visitante->id_visitante)) }}"
        method="POST" onsubmit="bloquearSubmit(event, this)">
        @csrf

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h3 class="text-xl font-bold text-gray-700 mb-6">
                Vincular Grade de Horário
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Data
                    </label>

                    <input type="date" name="visgra_data" required
                        min="{{ $visitante->vis_data_nasc?->format('Y-m-d') }}"
                        max="{{ now()->format('Y-m-d') }}"
                        value="{{ old('visgra_data') }}"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Modalidade
                    </label>

                    <select id="modalidadeSelect" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                        <option value="">Selecione a modalidade</option>

                        @foreach($grades->pluck('grade_modalidade')->unique() as $modalidade)
                        <option value="{{ $modalidade }}">
                            {{ $modalidade }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Turma
                    </label>

                    <select id="turmaSelect" required disabled
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                        <option value="">
                            Selecione a modalidade primeiro
                        </option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Horário
                    </label>

                    <select name="grade_id_grade" id="gradeSelect" required disabled
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                        <option value="">
                            Selecione a turma primeiro
                        </option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-600">
                        Observações
                    </label>

                    <textarea name="visgra_desc" rows="3" maxlength="150"
                        placeholder="Observação opcional..."
                        class="w-full border rounded-lg px-4 py-2 mt-1">{{ old('visgra_desc') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                <button type="button" onclick="fecharCadastro()"
                    class="px-5 py-2 border rounded-lg hover:bg-gray-100">
                    Cancelar
                </button>

                <button type="submit"
                    class="px-6 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">
                    Vincular Grade
                </button>
            </div>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">
        GRADES DE HORÁRIO
    </h3>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b text-gray-600 text-sm">
                    <th class="py-3 px-4 text-left">Data</th>
                    <th class="py-3 px-4 text-left">Modalidade</th>
                    <th class="py-3 px-4 text-left">Turma</th>
                    <th class="py-3 px-4 text-left">Professor</th>
                    <th class="py-3 px-4 text-left">Horário</th>
                    <th class="py-3 px-4 text-left">Observação</th>
                    <th class="py-3 px-4 text-left">Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse($gradesVisitante as $gradeVisitante)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="py-3 px-4">
                        {{ $gradeVisitante->visgra_data?->format('d/m/Y') ?? '-' }}
                    </td>

                    <td class="py-3 px-4">
                        {{ $gradeVisitante->grade->grade_modalidade ?? '-' }}
                    </td>

                    <td class="py-3 px-4">
                        {{ ucfirst($gradeVisitante->grade->grade_turma ?? '-') }}
                    </td>

                    <td class="py-3 px-4">
                        {{ $gradeVisitante->grade->professor->prof_nome ?? '-' }}
                    </td>

                    <td class="py-3 px-4">
                        @if($gradeVisitante->grade)
                        {{ \Carbon\Carbon::parse($gradeVisitante->grade->grade_inicio)->format('H:i') }}
                        às
                        {{ \Carbon\Carbon::parse($gradeVisitante->grade->grade_fim)->format('H:i') }}
                        @else
                        -
                        @endif
                    </td>

                    <td class="py-3 px-4">
                        {{ $gradeVisitante->visgra_desc ?? '-' }}
                    </td>

                    <td class="py-3 px-4">
                        <div class="flex justify-center gap-2">
                            @if($gradeVisitante->grade)
                            <a href="{{ route('frequencia.dias', Crypt::encrypt($gradeVisitante->grade->id_grade)) }}"
                                class="px-4 py-2 rounded-lg shadow text-white"
                                style="background-color: #174ab9;">
                                Frequência
                            </a>
                            @endif

                            <form
                                action="{{ route('visitantes.grade.destroy', Crypt::encrypt($gradeVisitante->id_visitante_grade)) }}"
                                method="POST"
                                onsubmit="return confirm('Deseja remover esta grade do visitante?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="px-4 py-2 rounded-lg shadow text-white bg-red-600 hover:bg-red-700 transition">
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500">
                        Nenhuma grade de horário vinculada
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="gradesData" data-grades='@json($grades)'></div>

<script>
    function toggleCadastro() {
        const form = document.getElementById('cadastroForm');

        if (!form.classList.contains('hidden')) {
            fecharCadastro();
            return;
        }

        form.classList.remove('hidden');

        form.scrollIntoView({
            behavior: 'smooth'
        });
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }

    function bloquearSubmit(event, form) {
        if (!form.checkValidity()) {
            return;
        }

        const btn = form.querySelector('button[type="submit"]');

        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Salvando...';
        }
    }

    const grades = JSON.parse(
        document.getElementById('gradesData').dataset.grades
    );

    const modalidadeSelect = document.getElementById('modalidadeSelect');
    const turmaSelect = document.getElementById('turmaSelect');
    const gradeSelect = document.getElementById('gradeSelect');

    modalidadeSelect.addEventListener('change', function() {
        turmaSelect.innerHTML = '<option value="">Selecione a turma</option>';
        turmaSelect.disabled = true;

        gradeSelect.innerHTML = '<option value="">Selecione a turma primeiro</option>';
        gradeSelect.disabled = true;

        if (!this.value) {
            return;
        }

        const turmasFiltradas = [
            ...new Set(
                grades
                .filter(grade => grade.grade_modalidade === this.value)
                .map(grade => grade.grade_turma)
                .filter(turma => turma)
            )
        ];

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

        if (turmasFiltradas.length) {
            turmaSelect.disabled = false;
        }
    });

    turmaSelect.addEventListener('change', function() {
        gradeSelect.innerHTML = '<option value="">Selecione o horário</option>';
        gradeSelect.disabled = true;

        if (!this.value) {
            return;
        }

        const resultado = grades.filter(grade =>
            grade.grade_modalidade === modalidadeSelect.value &&
            grade.grade_turma === this.value
        );

        resultado.forEach(grade => {
            const professor = grade.professor ?
                grade.professor.prof_nome :
                '-';

            const inicio = grade.grade_inicio ?
                grade.grade_inicio.substring(0, 5) :
                '--:--';

            const fim = grade.grade_fim ?
                grade.grade_fim.substring(0, 5) :
                '--:--';

            gradeSelect.innerHTML += `
                <option value="${grade.id_grade}">
                    Prof. ${professor} (${inicio} às ${fim})
                </option>
            `;
        });

        if (resultado.length) {
            gradeSelect.disabled = false;
        }
    });
</script>

@endsection