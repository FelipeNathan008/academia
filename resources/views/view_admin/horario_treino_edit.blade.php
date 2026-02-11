@extends('layouts.dashboard')

@section('title', 'Editar Horário de Treino')

@section('content')

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Editar Horário de Treino
    </h2>

    <form action="{{ route('horario_treino.update', $horario->id_hora) }}" method="POST">
        @csrf
        @method('PUT')

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

            // transforma "1,2,3" em array [1,2,3]
            $diasSelecionados = explode(',', old('hora_semana', $horario->hora_semana));
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Dias da Semana -->
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
                                {{ in_array((string)$num, $diasSelecionados) ? 'checked' : '' }}
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
                    class="w-full border rounded-lg px-4 py-2 mt-1
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none">

                    @foreach ($modalidades as $modalidade)
                        <option value="{{ $modalidade->mod_nome }}"
                            {{ old('hora_modalidade', $horario->hora_modalidade) == $modalidade->mod_nome ? 'selected' : '' }}>
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
                    value="{{ old('hora_inicio', $horario->hora_inicio) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
            </div>

            <!-- Hora Fim -->
            <div>
                <label class="text-sm font-medium text-gray-600">Hora Fim</label>
                <input type="time"
                    name="hora_fim"
                    required
                    value="{{ old('hora_fim', $horario->hora_fim) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
            </div>

        </div>

        <!-- AÇÕES -->
        <div class="flex justify-end gap-4 mt-8 border-t pt-6">
            <a href="{{ route('horario_treino') }}"
               class="px-5 py-2 border rounded-lg hover:bg-gray-100 transition">
                Voltar
            </a>

            <button type="submit"
                class="px-6 py-2 bg-[#8E251F] text-white rounded-lg
                       hover:bg-[#732920] shadow-md transition">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

@endsection
