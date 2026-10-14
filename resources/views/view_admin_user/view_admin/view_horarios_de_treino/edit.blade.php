@extends('layouts.dashboard')

@section('title', 'Editar Horário de Treino')

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
$diasOld = old('hora_semana');
$diasSelecionados = is_array($diasOld) ? $diasOld : explode(',', $horario->hora_semana);
@endphp

<div class="w-full px-4 pt-2 pb-6 sm:px-6 lg:px-8">
    <x-page-header
        title="Editar Horário de Treino"
        :breadcrumbs="[
            ['label' => 'Administração', 'url' => route('admin.administracao')],
            ['label' => 'Horários de Treino', 'url' => route('horario_treino')],
            ['label' => 'Editar Horário']
        ]" />

    <form action="{{ route('horario_treino.update', Crypt::encrypt($horario->id_hora)) }}" method="POST" onsubmit="bloquearSubmit(event, this)">
        @csrf
        @method('PUT')

        <x-form-card title="Dados do horário" description="Atualize os dias, a modalidade e o período do treino.">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Dias da semana</label>
                    <div class="grid grid-cols-2 gap-3 rounded-lg border border-gray-300 p-4">
                        @foreach ($diasSemana as $num => $nome)
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="hora_semana[]" value="{{ $num }}" {{ in_array((string) $num, array_map('strval', $diasSelecionados), true) ? 'checked' : '' }} class="rounded border-gray-300 text-[#8E251F] focus:ring-[#8E251F]">
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
                        <option value="{{ $modalidade->mod_nome }}" {{ old('hora_modalidade', $horario->hora_modalidade) === $modalidade->mod_nome ? 'selected' : '' }}>{{ $modalidade->mod_nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="hora_inicio" class="mb-2 block text-sm font-semibold text-gray-700">Hora de início</label>
                    <input type="time" id="hora_inicio" name="hora_inicio" required value="{{ old('hora_inicio', \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i')) }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                </div>

                <div>
                    <label for="hora_fim" class="mb-2 block text-sm font-semibold text-gray-700">Hora de fim</label>
                    <input type="time" id="hora_fim" name="hora_fim" required value="{{ old('hora_fim', \Carbon\Carbon::parse($horario->hora_fim)->format('H:i')) }}" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                </div>
            </div>

            <x-slot:actions>
                <a href="{{ route('horario_treino') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-200">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
                    </svg>
                    Voltar
                </a>
                <x-primary-button type="submit" icon="check">Salvar alterações</x-primary-button>
            </x-slot:actions>
        </x-form-card>
    </form>
</div>

<script>
    function bloquearSubmit(event, form) {
        if (!form.checkValidity()) return;

        const botao = form.querySelector('button[type="submit"]');
        if (botao) {
            botao.disabled = true;
            botao.innerText = 'Salvando...';
        }
    }
</script>
@endsection