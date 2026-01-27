@extends('layouts.dashboard')

@section('title', 'Editar Professor')

@section('content')

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Editar Professor ({{ $professor->prof_nome }})</h2>

    <form action="{{ route('professores.update', $professor->id_professor) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nome -->
            <div>
                <label class="text-sm font-medium text-gray-600">Nome Completo</label>
                <input type="text" name="prof_nome" id="edit_nome" maxlength="120" required
                    value="{{ old('prof_nome', $professor->prof_nome) }}"
                    oninput="validarNome(this)"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
            </div>

            <!-- Nascimento -->
            <div>
                <label class="text-sm font-medium text-gray-600">Data de Nascimento</label>
                <input type="date" name="prof_nascimento" value="{{ old('prof_nascimento', $professor->prof_nascimento) }}" required
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
            </div>

            <!-- Telefone -->
            <div>
                <label class="text-sm font-medium text-gray-600">Telefone</label>
                <input type="text"
                    name="prof_telefone"
                    id="prof_telefone"
                    required
                    maxlength="15"
                    placeholder="(99) 99999-9999"
                    value="{{ old('prof_telefone', $professor->prof_telefone) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
            </div>

            <!-- Observações -->
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-600">Observações</label>
                <textarea name="prof_desc" id="edit_desc" rows="3" maxlength="120" required
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">{{ old('prof_desc', $professor->prof_desc) }}</textarea>
            </div>

            <!-- Foto -->
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-600">Foto Atual</label>
                <div class="mb-2">
                    @if($professor->prof_foto)
                    <img src="{{ asset('images/professores/' . $professor->prof_foto) }}"
                        alt="Foto do Professor" class="w-20 h-20 object-cover border rounded-md">
                    @else
                    <span class="text-gray-500">Nenhuma foto cadastrada</span>
                    @endif
                </div>

                <label class="text-sm font-medium text-gray-600">Alterar Foto</label>
                <input type="file" name="prof_foto" accept="image/*"
                    class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-50">
            </div>
        </div>

        <div class="flex justify-end gap-4 mt-6">
            <a href="{{ route('professores') }}"
                class="px-5 py-2 border rounded-lg hover:bg-gray-100 transition">Voltar</a>

            <button type="submit"
                class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920] transition">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

<!-- JS Validação -->
<script>
    function validarNome(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
    }

    function aplicarMascaraTelefone(input) {
        let v = input.value.replace(/\D/g, '');

        if (v.length > 11) v = v.slice(0, 11);

        let f = '';
        if (v.length > 0) f = '(' + v.slice(0, 2);
        if (v.length >= 3) f += ') ' + v.slice(2, 7);
        if (v.length >= 8) f += '-' + v.slice(7, 11);

        input.value = f;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const tel = document.getElementById('prof_telefone');

        if (tel) {
            aplicarMascaraTelefone(tel);

            tel.addEventListener('input', () => {
                aplicarMascaraTelefone(tel);
            });
        }
    });
</script>

@endsection