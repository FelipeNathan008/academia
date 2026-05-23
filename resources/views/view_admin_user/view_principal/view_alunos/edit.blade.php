@extends('layouts.dashboard')

@section('title', 'Editar Aluno')

@section('content')

<x-alert-error />

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('responsaveis') }}" class="hover:text-[#8E251F] transition">
                Responsáveis
            </a>
        </li>
        <li>/</li>
        <li class="text-gray-400">{{ $responsavel->resp_nome }}</li>
        <li>/</li>
        <li>
            <a href="{{ route('alunos', Crypt::encrypt($responsavel->id_responsavel)) }}"
                class="hover:text-[#8E251F] transition">
                Alunos
            </a>
        </li>
        <li>/</li>

        <li>
            <span class="text-gray-400">
                {{ $aluno->aluno_nome }}
            </span>
        </li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Editar Aluno</li>
    </ol>
</nav>

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Editar Aluno ({{ $aluno->aluno_nome }})
    </h2>

    <form action="{{ route('alunos.update', Crypt::encrypt($aluno->id_aluno)) }}"
        method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- NOME -->
            <div>
                <label class="text-sm font-medium text-gray-600">Nome Completo</label>
                <input type="text"
                    name="aluno_nome"
                    maxlength="120"
                    required
                    value="{{ old('aluno_nome', $aluno->aluno_nome) }}"
                    oninput="validarNome(this)"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Parentesco</label>
                <select name="aluno_parentesco" required
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">

                    <option value="Filho" {{ old('aluno_parentesco', $aluno->aluno_parentesco) == 'filho' ? 'selected' : '' }}>
                        Filho
                    </option>

                    <option value="Sobrinho" {{ old('aluno_parentesco', $aluno->aluno_parentesco) == 'sobrinho' ? 'selected' : '' }}>
                        Sobrinho
                    </option>

                    <option value="Neto" {{ old('aluno_parentesco', $aluno->aluno_parentesco) == 'neto' ? 'selected' : '' }}>
                        Neto
                    </option>

                    <option value="Responsável" {{ old('aluno_parentesco', $aluno->aluno_parentesco) == 'responsavel' ? 'selected' : '' }}>
                        Responsável
                    </option>

                    <option value="Outro" {{ old('aluno_parentesco', $aluno->aluno_parentesco) == 'outro' ? 'selected' : '' }}>
                        Outro
                    </option>

                </select>
            </div>

            <!-- NASCIMENTO -->
            <div>
                <label class="text-sm font-medium text-gray-600">Data de Nascimento</label>
                <input type="date"
                    name="aluno_nascimento" required max="{{ \Carbon\Carbon::now()->subYears(6)->format('Y-m-d') }}"
                    min="{{ \Carbon\Carbon::now()->subYears(100)->format('Y-m-d') }}"
                    oninput="this.setCustomValidity('')"
                    oninvalid="validarData(this)"
                    value="{{ old('aluno_nascimento', $aluno->aluno_nascimento) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
            </div>

            <!-- aluno BOLSISTA DESABILITADO POR ENQUANTO
             
            <div>
                <label class="text-sm font-medium text-gray-600">Aluno Bolsista?</label>
                <select name="aluno_bolsista" required
                    class="w-full border rounded-lg px-4 py-2 mt-1">
                    <option value="sim" {{ $aluno->aluno_bolsista === 'sim' ? 'selected' : '' }}>
                        Sim
                    </option>
                    <option value="nao" {{ $aluno->aluno_bolsista === 'nao' ? 'selected' : '' }}>
                        Não
                    </option>
                </select>
            </div> -->

            <!-- OBSERVAÇÕES -->
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-600">Observações</label>
                <textarea name="aluno_desc"
                    rows="3"
                    required
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">{{ old('aluno_desc', $aluno->aluno_desc) }}</textarea>
            </div>

            <!-- FOTO -->
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-600">Foto Atual</label>
                <div class="mb-2">
                    @if($aluno->aluno_foto)
                    <img src="{{ asset('images/alunos/' . $aluno->aluno_foto) }}"
                        alt="Foto do Aluno" class="w-20 h-20 object-cover border rounded-md">
                    @else
                    <span class="text-gray-500">Nenhuma foto cadastrada</span>
                    @endif
                </div>

                <label class="text-sm font-medium text-gray-600">Alterar Foto</label>
                <input type="file"
                    name="aluno_foto"
                    accept="image/*"
                    class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-50">
            </div>

        </div>

        <div class="flex justify-end gap-4 mt-6">
            <a href="{{ route('alunos', Crypt::encrypt($aluno->responsavel_id_responsavel)) }}"
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

<!-- JS Validação -->
<script>
    function validarNome(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
    }

    function validarData(campo) {
        if (campo.validity.valueMissing) {
            campo.setCustomValidity('A data de nascimento é obrigatória');
        } else if (campo.validity.rangeOverflow) {
            campo.setCustomValidity('Você precisa ter pelo menos 6 anos');
        } else if (campo.validity.rangeUnderflow) {
            campo.setCustomValidity('Idade não compatível com o sistema');
        } else {
            campo.setCustomValidity('');
        }
    }
</script>

@endsection