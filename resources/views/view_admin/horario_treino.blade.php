@extends('layouts.dashboard')

@section('title', 'Horários de Treino')

@section('content')

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <h2 class="text-3xl font-extrabold text-gray-800">Horários de Treino</h2>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">
        + Cadastrar Horário
    </button>
</div>

<!-- FORMULÁRIO -->
<div id="cadastroForm" class="hidden mb-10">
    <form action="{{ route('horario_treino.store') }}" method="POST">
        @csrf

        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">Cadastrar Horário de Treino</h3>

            @php
            $diasSemana = [
            1 => 'Domingo',
            2 => 'Segunda-feira',
            3 => 'Terça-feira',
            4 => 'Quarta-feira',
            5 => 'Quinta-feira',
            6 => 'Sexta-feira',
            7 => 'Sábado',
            ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Dias -->
                <div>
                    <label class="text-sm font-medium text-gray-600 mb-2 block">
                        Dias da Semana
                    </label>

                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($diasSemana as $num => $nome)
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox"
                                name="hora_semana[]"
                                value="{{ $num }}"
                                class="rounded border-gray-300 text-[#8E251F] focus:ring-[#8E251F]">
                            {{ $nome }}
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Modalidade -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Modalidade</label>
                    <select name="hora_modalidade" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
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
                    <input type="time" name="hora_inicio" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                </div>

                <!-- Hora Fim -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Hora Fim</label>
                    <input type="time" name="hora_fim" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
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

<!-- FILTROS -->
<div class="bg-white rounded-2xl shadow-md p-6 mb-8">

    <div class="flex justify-center">
        <div class="flex flex-wrap gap-6 items-end justify-center">

            <!-- Modalidade -->
            <div class="flex flex-col w-[250px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Modalidade
                </label>
                <select id="filtroModalidade"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">
                    <option value="">Todas</option>
                    @foreach ($modalidades as $modalidade)
                    <option value="{{ $modalidade->mod_nome }}">
                        {{ $modalidade->mod_nome }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Dia -->
            <div class="flex flex-col w-[200px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Dia
                </label>
                <select id="filtroDia"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">
                    <option value="">Todos</option>
                    @foreach ($diasSemana as $num => $nome)
                    <option value="{{ $num }}">{{ $nome }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Horário -->
            <div class="flex flex-col w-[180px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Horário
                </label>
                <input type="time" id="filtroHora"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">
            </div>

            <!-- Status -->
            <div class="flex flex-col w-[180px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Status
                </label>
                <select id="filtroStatus"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">
                    <option value="">Todos</option>
                    <option value="Vago">Vago</option>
                    <option value="Ocupado">Ocupado</option>
                </select>
            </div>

            <!-- Limpar -->
            <button id="limparFiltros"
                class="h-[48px] px-6 rounded-xl bg-gray-300
                       text-gray-800 font-semibold hover:bg-gray-400
                       transition shadow-md">
                Limpar filtros
            </button>

        </div>
    </div>

</div>


<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">Lista de Horários</h3>

    <table class="w-full text-left">
        <thead>
            <tr class="border-b text-sm text-gray-600">
                <th class="py-3 px-4">Dia</th>
                <th class="py-3 px-4">Início</th>
                <th class="py-3 px-4">Fim</th>
                <th class="py-3 px-4">Modalidade</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($horarios as $horario)
            <tr class="border-b hover:bg-gray-50"
                data-modalidade="{{ $horario->hora_modalidade }}"
                data-dia="{{ $horario->hora_semana }}"
                data-hora="{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}"
                data-status="{{ $horario->gradeHorario->isEmpty() ? 'Vago' : 'Ocupado' }}">

                <td class="py-3 px-4">{{ $horario->diasSemanaTexto() }}</td>
                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}</td>
                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($horario->hora_fim)->format('H:i') }}</td>
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
                <td colspan="6" class="text-center py-6 text-gray-500">
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
            const dias = linha.dataset.dia.split(',');

            const ok =
                (!filtros.modalidade.value || filtros.modalidade.value === linha.dataset.modalidade) &&
                (!filtros.dia.value || dias.includes(filtros.dia.value)) &&
                (!filtros.hora.value || filtros.hora.value === linha.dataset.hora) &&
                (!filtros.status.value || filtros.status.value === linha.dataset.status);

            linha.style.display = ok ? '' : 'none';
        });
    }

    Object.values(filtros).forEach(f => f.addEventListener('change', aplicarFiltros));
    document.getElementById('limparFiltros').onclick = () => {
        Object.values(filtros).forEach(f => f.value = '');
        aplicarFiltros();
    };

    function toggleCadastro() {
        document.getElementById('cadastroForm').classList.toggle('hidden');
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }
</script>

@endsection