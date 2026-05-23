{{-- resources/views/view_aulas/edit.blade.php --}}

@extends('layouts.dashboard')

@section('title', 'Editar Aula')

@section('content')

<x-alert-error />

<div class="max-w-4xl mx-auto">

    <div class="flex items-center justify-between mb-8">

        <h2 class="text-3xl font-extrabold text-gray-800">
            Editar Aula
        </h2>

        <a href="{{ route('aulas', Crypt::encrypt($aula->professor_id)) }}"
            class="px-4 py-2 border rounded-lg hover:bg-gray-100">
            ← Voltar
        </a>
    </div>

    <form action="{{ route('aulas.update', Crypt::encrypt($aula->id_aula)) }}"
        method="POST">

        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-md p-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Grade Horário
                    </label>

                    <select name="grade_horario_id"
                        required
                        class="w-full border rounded-lg px-4 py-2 mt-1">

                        @foreach($aula->professor->grades as $grade)

                        <option value="{{ $grade->id_grade }}"
                            {{ $aula->grade_horario_id == $grade->id_grade ? 'selected' : '' }}>

                            {{ $grade->grade_modalidade }}
                            -
                            {{ $grade->grade_dia_semana }}

                        </option>

                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Posição Ensino
                    </label>

                    <input type="text"
                        name="aula_posicao_ensino"
                        maxlength="150"
                        required
                        value="{{ $aula->aula_posicao_ensino }}"
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Período Inicial
                    </label>

                    <input type="date"
                        name="aula_periodo_inicial"
                        required
                        value="{{ $aula->aula_periodo_inicial }}"
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Período Final
                    </label>

                    <input type="date"
                        name="aula_periodo_final"
                        required
                        value="{{ $aula->aula_periodo_final }}"
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                </div>

            </div>

            <div class="flex justify-end gap-4 border-t pt-6 mt-8">

                <button type="submit"
                    class="px-5 py-2 bg-[#8E251F] text-white rounded-lg">
                    Atualizar
                </button>

            </div>
        </div>
    </form>
</div>

@endsection