@extends('layouts.dashboard')

@section('title', 'Editar Responsável')

@section('content')

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-8">

    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Editar Responsável – {{ $responsavel->resp_nome }}
    </h2>

    <p class="text-sm text-gray-500 mb-6">
        Aluno: <strong>{{ $aluno->aluno_nome }}</strong>
    </p>

    <form id="editResponsavelForm" action="{{ route('responsaveis.update', $responsavel->id_responsavel) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Nome | Parentesco -->
        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Nome do Responsável</label>
                <input type="text" name="resp_nome" required maxlength="120"
                       value="{{ old('resp_nome', $responsavel->resp_nome) }}"
                       oninput="validarNome(this)"
                       class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>

            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Parentesco</label>
                <select name="resp_parentesco" required class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                    <option value="">Selecione</option>
                    <option {{ $responsavel->resp_parentesco == 'Pai' ? 'selected' : '' }}>Pai</option>
                    <option {{ $responsavel->resp_parentesco == 'Mãe' ? 'selected' : '' }}>Mãe</option>
                    <option {{ $responsavel->resp_parentesco == 'Responsável Legal' ? 'selected' : '' }}>Responsável Legal</option>
                    <option {{ $responsavel->resp_parentesco == 'Avô(ó)' ? 'selected' : '' }}>Avô(ó)</option>
                    <option {{ $responsavel->resp_parentesco == 'Tio(a)' ? 'selected' : '' }}>Tio(a)</option>
                    <option {{ $responsavel->resp_parentesco == 'Outro' ? 'selected' : '' }}>Outro</option>
                </select>
            </div>
        </div>

        <!-- CPF | CEP -->
        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">CPF</label>
                <input type="text" name="resp_cpf" maxlength="14" required
                       value="{{ old('resp_cpf', $responsavel->resp_cpf) }}"
                       oninput="mascaraCPF(this)"
                       class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">CEP</label>
                <input type="text" name="resp_cep" maxlength="9" required
                       value="{{ old('resp_cep', $responsavel->resp_cep) }}"
                       oninput="mascaraCEP(this)"
                       class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>
        </div>

        <!-- Cidade | Bairro -->
        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Cidade</label>
                <input type="text" name="resp_cidade" required
                       value="{{ old('resp_cidade', $responsavel->resp_cidade) }}"
                       class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Bairro</label>
                <input type="text" name="resp_bairro" required
                       value="{{ old('resp_bairro', $responsavel->resp_bairro) }}"
                       class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>
        </div>

        <!-- Logradouro | Número | Complemento -->
        <div class="flex gap-4 mb-4">
            <div class="flex-2">
                <label class="text-sm font-medium text-gray-600">Logradouro</label>
                <input type="text" name="resp_logradouro" id="resp_logradouro" required
                       value="{{ old('resp_logradouro', $responsavel->resp_logradouro) }}"
                       placeholder="Rua, Avenida, etc."
                       class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Número</label>
                <input type="text" name="resp_numero" id="resp_numero"
                       value="{{ old('resp_numero', $responsavel->resp_numero) }}"
                       placeholder="Ex: 63" required
                       class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Complemento</label>
                <input type="text" name="resp_complemento" id="resp_complemento"
                       value="{{ old('resp_complemento', $responsavel->resp_complemento) }}"
                       placeholder="Ex: Bloco B, Apto 302"
                       class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>
        </div>

        <div class="flex justify-end gap-4 mt-8">
            <a href="{{ route('responsaveis.index', $aluno->id_aluno) }}"
               class="px-5 py-2 border rounded-lg hover:bg-gray-100">
                Voltar
            </a>

            <button type="submit"
                class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

<script>
function validarNome(input) {
    input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
}

function mascaraCPF(input) {
    let value = input.value.replace(/\D/g, '').slice(0, 11);
    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    input.value = value;
}

function mascaraCEP(input) {
    let value = input.value.replace(/\D/g, '').slice(0, 8);
    if (value.length > 5) value = value.replace(/^(\d{5})(\d)/, '$1-$2');
    input.value = value;
}
</script>

@endsection
