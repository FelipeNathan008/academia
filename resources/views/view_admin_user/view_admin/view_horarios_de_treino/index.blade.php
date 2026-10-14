@extends('layouts.dashboard')

@section('title', 'Horários de Treino')

@section('content')
<x-alert-error />

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

<div class="w-full px-4 pt-2 pb-6 sm:px-6 lg:px-8">
    <x-page-header title="Horários de Treino" :breadcrumbs="[
        ['label' => 'Administração', 'url' => route('admin.administracao')],
        ['label' => 'Horários de Treino']
    ]">
        <x-slot:action>
            <div class="flex flex-wrap items-center gap-3">
                <x-back-button :url="route('admin.administracao')" />
                <x-primary-button icon="plus" onclick="toggleCadastro()">Cadastrar horário</x-primary-button>
            </div>
        </x-slot:action>
    </x-page-header>

    <div id="cadastroForm" class="mb-8 hidden">
        <form id="formCadastro" action="{{ route('horario_treino.store') }}" method="POST" onsubmit="bloquearSubmit(event, this)">
            @csrf
            <x-form-card title="Cadastrar horário de treino" description="Preencha os dias, a modalidade e o período do treino.">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">Dias da semana</label>
                        <div class="grid grid-cols-2 gap-3 rounded-lg border border-gray-300 p-4">
                            @foreach ($diasSemana as $num => $nome)
                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="hora_semana[]" value="{{ $num }}" class="rounded border-gray-300 text-[#8E251F] focus:ring-[#8E251F]">
                                {{ $nome }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label for="hora_modalidade" class="mb-2 block text-sm font-semibold text-gray-700">Modalidade</label>
                        <select id="hora_modalidade" name="hora_modalidade" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                            <option value="">Selecione uma modalidade</option>
                            @foreach ($modalidades as $modalidade)
                            <option value="{{ $modalidade->mod_nome }}" {{ old('hora_modalidade') === $modalidade->mod_nome ? 'selected' : '' }}>{{ $modalidade->mod_nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="hora_inicio" class="mb-2 block text-sm font-semibold text-gray-700">Hora de início</label>
                        <input type="time" id="hora_inicio" name="hora_inicio" value="{{ old('hora_inicio') }}" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                    </div>
                    <div>
                        <label for="hora_fim" class="mb-2 block text-sm font-semibold text-gray-700">Hora de fim</label>
                        <input type="time" id="hora_fim" name="hora_fim" value="{{ old('hora_fim') }}" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                    </div>
                </div>
                <x-slot:actions>
                    <x-secondary-button onclick="fecharCadastro()">Cancelar</x-secondary-button>
                    <x-primary-button type="submit" icon="check">Salvar horário</x-primary-button>
                </x-slot:actions>
            </x-form-card>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-md">
        <div class="border-b border-gray-200 px-6 py-5">
            <form action="{{ route('horario_treino') }}" method="GET">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div>
                        <label for="filtroModalidade" class="mb-2 block text-sm font-semibold text-gray-700">Modalidade</label>
                        <select id="filtroModalidade" name="modalidade" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                            <option value="">Todas as modalidades</option>
                            @foreach ($modalidades as $modalidade)
                            <option value="{{ $modalidade->mod_nome }}" {{ request('modalidade') === $modalidade->mod_nome ? 'selected' : '' }}>{{ $modalidade->mod_nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filtroDia" class="mb-2 block text-sm font-semibold text-gray-700">Dia da semana</label>
                        <select id="filtroDia" name="dia" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                            <option value="">Todos os dias</option>
                            @foreach ($diasSemana as $num => $nome)
                            <option value="{{ $num }}" {{ (string) request('dia') === (string) $num ? 'selected' : '' }}>{{ $nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filtroHora" class="mb-2 block text-sm font-semibold text-gray-700">Horário de início</label>
                        <input type="time" id="filtroHora" name="hora" value="{{ request('hora') }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                    </div>
                    <div>
                        <label for="filtroStatus" class="mb-2 block text-sm font-semibold text-gray-700">Status</label>
                        <select id="filtroStatus" name="status" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                            <option value="">Todos os status</option>
                            <option value="Vago" {{ request('status') === 'Vago' ? 'selected' : '' }}>Vago</option>
                            <option value="Ocupado" {{ request('status') === 'Ocupado' ? 'selected' : '' }}>Ocupado</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap gap-3">
                    <x-primary-button type="submit" icon="search">Buscar</x-primary-button>
                    <a href="{{ route('horario_treino') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-200">Limpar</a>
                </div>
            </form>

            <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                <p class="text-sm text-gray-500">Consulte e gerencie os horários de treino cadastrados.</p>
                <x-record-count :count="$totalHorarios" singular="horário" plural="horários" />
            </div>
        </div>


        <div class="p-4 sm:p-6">
            @if ($horarios->isEmpty())
            @if (request()->hasAny(['modalidade', 'dia', 'hora', 'status']) && collect(request()->only(['modalidade', 'dia', 'hora', 'status']))->contains(fn ($valor) => $valor !== null && $valor !== ''))
            <x-empty-state title="Nenhum horário encontrado" message="Tente alterar os filtros utilizados." :route="route('horario_treino')" button-text="Limpar filtros" />
            @else
            <x-empty-state title="Nenhum horário cadastrado" message="Cadastre um horário de treino para começar." button-type="button" button-action="toggleCadastro()" button-text="Cadastrar horário" />
            @endif
            @else
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="w-full border-collapse text-left">
                    <thead class="bg-gray-700 text-white">
                        <tr>
                            <th class="border-r border-gray-500 px-5 py-4 text-xs font-bold uppercase tracking-wider">Dia</th>
                            <th class="border-r border-gray-500 px-5 py-4 text-xs font-bold uppercase tracking-wider">Início</th>
                            <th class="border-r border-gray-500 px-5 py-4 text-xs font-bold uppercase tracking-wider">Fim</th>
                            <th class="border-r border-gray-500 px-5 py-4 text-xs font-bold uppercase tracking-wider">Modalidade</th>
                            <th class="border-r border-gray-500 px-5 py-4 text-xs font-bold uppercase tracking-wider">Status</th>
                            <th class="px-5 py-4 text-center text-xs font-bold uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white" id="tabelaHorarios">
                        @foreach ($horarios as $horario)
                        @php
                        $status = $horario->gradeHorario->isEmpty() ? 'Vago' : 'Ocupado';
                        $dias = array_map('trim', explode(',', (string) $horario->hora_semana));
                        @endphp
                        <tr class="linhaHorario transition-colors hover:bg-gray-50"
                            data-modalidade="{{ $horario->hora_modalidade }}"
                            data-dia="{{ implode(',', $dias) }}"
                            data-hora="{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}"
                            data-status="{{ $status }}">
                            <td class="border-r border-gray-200 px-5 py-4 text-sm text-gray-700">{{ $horario->diasSemanaTexto() }}</td>
                            <td class="border-r border-gray-200 px-5 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}</td>
                            <td class="border-r border-gray-200 px-5 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($horario->hora_fim)->format('H:i') }}</td>
                            <td class="border-r border-gray-200 px-5 py-4 text-sm font-semibold text-gray-800">{{ $horario->hora_modalidade }}</td>
                            <td class="border-r border-gray-200 px-5 py-4">
                                <x-status-badge :status="$status" />
                            </td>
                            <td class="px-5 py-4">
                                <x-table-actions
                                    :edit-route="route('horario_treino.edit', Crypt::encrypt($horario->id_hora))"
                                    :delete-action="route('horario_treino.destroy', Crypt::encrypt($horario->id_hora))"
                                    :delete-id="'formExcluirHorario'.$horario->id_hora"
                                    delete-modal="modalExclusao" />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <p id="nenhumResultadoFiltro" class="hidden px-5 py-8 text-center text-sm text-gray-500">Nenhum horário corresponde aos filtros selecionados.</p>
            </div>
            <div class="mt-5">
                <x-pagination-compact :paginator="$horarios" />
            </div>
            @endif
        </div>
    </div>
</div>

<x-modal-confirmacao id="modalExclusao" titulo="Confirmar exclusão" mensagem="Deseja realmente excluir este horário de treino?" texto-confirmar="Excluir" />

<script>
    function bloquearSubmit(event, form) {
        if (!form.checkValidity()) return;
        const botao = form.querySelector('button[type="submit"]');
        if (botao) {
            botao.disabled = true;
            botao.innerText = 'Salvando...';
        }
    }

    function toggleCadastro() {
        const cadastro = document.getElementById('cadastroForm');
        const abrir = cadastro.classList.contains('hidden');
        cadastro.classList.toggle('hidden', !abrir);

        if (abrir) {
            cadastro.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
            document.getElementById('hora_modalidade').focus();
        }
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }
</script>
@endsection