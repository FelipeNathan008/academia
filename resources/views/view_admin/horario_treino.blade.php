@extends('layouts.dashboard')

@section('title', 'Horários de Treino')

@section('content')

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">Horários de Treino</h2>
    </div>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">
        + Cadastrar Horário
    </button>
</div>

<!-- FORMULÁRIO DE CADASTRO -->
<div id="cadastroForm" class="hidden mb-10">
    <form id="formCadastro" action="{{ route('horario_treino.store') }}" method="POST">
        @csrf

        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">Cadastrar Horário de Treino</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Dia da Semana -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Dia da Semana</label>
                    <select name="hora_semana" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                        <option value="">Selecione</option>
                        <option value="Segunda-feira">Segunda-feira</option>
                        <option value="Terça-feira">Terça-feira</option>
                        <option value="Quarta-feira">Quarta-feira</option>
                        <option value="Quinta-feira">Quinta-feira</option>
                        <option value="Sexta-feira">Sexta-feira</option>
                        <option value="Sábado">Sábado</option>
                        <option value="Domingo">Domingo</option>
                    </select>
                </div>

                <!-- Modalidade -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Modalidade</label>
                    <select name="hora_modalidade" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                        <option value="">Selecione</option>
                        @foreach ($modalidades as $modalidade)
                        <option value="{{ $modalidade->mod_nome }}">
                            {{ $modalidade->mod_nome }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Hora Início -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Hora Início</label>
                    <input type="time"
                        name="hora_inicio"
                        required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>

                <!-- Hora Fim -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Hora Fim</label>
                    <input type="time"
                        name="hora_fim"
                        required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
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
                    Salvar Horário
                </button>
            </div>
        </div>
    </form>
</div>

<!-- FILTRO -->
<div class="flex flex-wrap gap-6 items-end justify-center max-w-6xl mx-auto mb-10">

    <!-- Modalidade -->
    <div class="flex flex-col w-[220px]">
        <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
            Modalidade
        </label>
        <select id="filtroModalidade"
            class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                   focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
            <option value="">Todas</option>
            @foreach ($modalidades as $modalidade)
            <option value="{{ $modalidade->mod_nome }}">
                {{ $modalidade->mod_nome }}
            </option>
            @endforeach
        </select>
    </div>

    <!-- Dia da semana -->
    <div class="flex flex-col w-[220px]">
        <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
            Dia
        </label>
        <select id="filtroDia"
            class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                   focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
            <option value="">Todos</option>
            <option>Segunda-feira</option>
            <option>Terça-feira</option>
            <option>Quarta-feira</option>
            <option>Quinta-feira</option>
            <option>Sexta-feira</option>
            <option>Sábado</option>
            <option>Domingo</option>
        </select>
    </div>

    <!-- Horário -->
    <div class="flex flex-col w-[180px]">
        <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
            Horário
        </label>
        <input type="time" id="filtroHora"
            class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                   focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
    </div>

    <!-- Status -->
    <div class="flex flex-col w-[180px]">
        <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
            Status
        </label>
        <select id="filtroStatus"
            class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                   focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
            <option value="">Todos</option>
            <option value="Vago">Vago</option>
            <option value="Ocupado">Ocupado</option>
        </select>
    </div>

    <!-- Limpar -->
    <button id="limparFiltros"
        class="h-[48px] px-8 rounded-xl bg-gradient-to-r from-gray-300 to-gray-400
               text-gray-800 font-semibold hover:from-gray-400 hover:to-gray-500
               transition shadow-md">
        Limpar filtros
    </button>

</div>



<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">Lista de Horários de Treino</h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gray-300 text-gray-600 text-sm">
                <th class="py-3 px-4">Dia</th>
                <th class="py-3 px-4">Início</th>
                <th class="py-3 px-4">Fim</th>
                <th class="py-3 px-4">Modalidade</th>
                <th class="py-3 px-4">Horário vago</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($horarios as $horario)
            <tr class="border-b hover:bg-gray-50 transition"
                data-modalidade="{{ $horario->hora_modalidade }}"
                data-dia="{{ $horario->hora_semana }}"
                data-hora="{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}"
                data-status="{{ $horario->gradeHorario->isEmpty() ? 'Vago' : 'Ocupado' }}">

                <td class="py-3 px-4">{{ $horario->hora_semana }}</td>

                <td class="py-3 px-4">
                    {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}
                </td>

                <td class="py-3 px-4">
                    {{ \Carbon\Carbon::parse($horario->hora_fim)->format('H:i') }}
                </td>

                <td class="py-3 px-4">{{ $horario->hora_modalidade }}</td>

                <td class="py-3 px-4">
                    @if ($horario->gradeHorario->isEmpty())
                    <span style="display: inline-flex;align-items: center;padding: 4px 12px;font-size: 0.875rem;
                    font-weight: 600; border-radius: 9999px; color: #15803d; background-color: #dcfce7;">
                        Vago
                    </span>
                    @else
                    <span style="display: inline-flex; align-items: center; padding: 4px 12px; font-size: 0.875rem;
                    font-weight: 600; border-radius: 9999px; color: #b91c1c; background-color: #fee2e2;">
                        Ocupado
                    </span>

                    @endif
                </td>


                <td class="py-3 px-4 flex gap-2">
                    <a href="{{ route('horario_treino.edit', $horario->id_hora) }}"
                        style="background-color: #8E251F; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                        Editar
                    </a>

                    <form action="{{ route('horario_treino.destroy', $horario->id_hora) }}"
                        method="POST"
                        onsubmit="return confirm('Deseja excluir este horário?');">
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
                <td colspan="5" class="text-center text-gray-500 py-6">
                    Nenhum horário cadastrado
                </td>
            </tr>
            @endforelse
        </tbody>

    </table>
</div>

<script>
    const filtros = {
        modalidade: document.getElementById('filtroModalidade'),
        dia: document.getElementById('filtroDia'),
        hora: document.getElementById('filtroHora'),
        status: document.getElementById('filtroStatus'),
    };

    const linhas = document.querySelectorAll('tbody tr');

    function aplicarFiltros() {
        linhas.forEach(linha => {
            const modalidade = linha.dataset.modalidade;
            const dia = linha.dataset.dia;
            const hora = linha.dataset.hora;
            const status = linha.dataset.status;

            const matchModalidade = !filtros.modalidade.value || filtros.modalidade.value === modalidade;
            const matchDia = !filtros.dia.value || filtros.dia.value === dia;
            const matchHora = !filtros.hora.value || filtros.hora.value === hora;
            const matchStatus = !filtros.status.value || filtros.status.value === status;

            linha.style.display = (matchModalidade && matchDia && matchHora && matchStatus) ?
                '' :
                'none';
        });
    }

    Object.values(filtros).forEach(filtro => {
        filtro.addEventListener('change', aplicarFiltros);
    });

    document.getElementById('limparFiltros').addEventListener('click', () => {
        Object.values(filtros).forEach(filtro => filtro.value = '');
        aplicarFiltros();
    });



    function toggleCadastro() {
        const form = document.getElementById('cadastroForm');
        form.classList.toggle('hidden');
        form.scrollIntoView({
            behavior: 'smooth'
        });
        document.getElementById('formCadastro').reset();
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }
</script>

@endsection