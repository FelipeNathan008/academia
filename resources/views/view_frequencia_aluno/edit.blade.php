@extends('layouts.dashboard')

@section('title', 'Editar Frequência')

@section('content')

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Editar Frequência ({{ \Carbon\Carbon::parse($frequencia->freq_data_aula)->format('d/m/Y') }})
    </h2>

    <form action="{{ route('frequencia.update', $frequencia->id_frequencia_aluno) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6">

            <div>
                <label class="text-sm font-medium text-gray-600">Aluno</label>
                <input type="text"
                    class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-100"
                    value="{{ $frequencia->matricula->aluno->aluno_nome ?? '-' }}"
                    disabled>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Presença</label>

                <select name="freq_presenca"
                    class="w-full border rounded-lg px-4 py-2 mt-1
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none">

                    <option value="Presente"
                        {{ $frequencia->freq_presenca == 'Presente' ? 'selected' : '' }}>
                        Presente
                    </option>

                    <option value="Falta"
                        {{ $frequencia->freq_presenca == 'Falta' ? 'selected' : '' }}>
                        Falta
                    </option>

                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Observação</label>

                <input type="text"
                    name="freq_observacao"
                    value="{{ old('freq_observacao', $frequencia->freq_observacao) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                    placeholder="Observação (opcional)">
            </div>

        </div>

        <div class="flex justify-end gap-4 mt-6">
            <a href="{{ route('frequencia.dias', $frequencia->grade_horario_id_grade) }}"
                class="px-5 py-2 border rounded-lg hover:bg-gray-100 transition">
                Voltar
            </a>

            <button type="submit"
                class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920] transition">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

@endsection