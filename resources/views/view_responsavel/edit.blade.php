@extends('layouts.dashboard')

@section('title', 'Editar Responsável')

@section('content')

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2 flex-wrap">
        <li>
            <a href="{{ route('responsaveis') }}"
                class="hover:text-[#8E251F] transition">
                Responsáveis
            </a>
        </li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Editar Responsável</li>
    </ol>
</nav>

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-8">

    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Editar Responsável – {{ $responsavel->resp_nome }}
    </h2>

    <form action="{{ route('responsaveis.update', $responsavel->id_responsavel) }}"
        method="POST">
        @csrf
        @method('PUT')

        <!-- Nome | Parentesco -->
        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Nome do Responsável</label>
                <input type="text"
                    name="resp_nome"
                    required
                    maxlength="120"
                    placeholder="Ex: Maria Lúcia"
                    value="{{ old('resp_nome', $responsavel->resp_nome) }}"
                    oninput="validarNome(this)"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>

            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Parentesco</label>
                <select name="resp_parentesco"
                    required
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                    <option value="">Selecione</option>
                    @foreach (['Pai','Mãe','Responsável Legal','Avô(ó)','Tio(a)','Outro'] as $p)
                    <option {{ $responsavel->resp_parentesco == $p ? 'selected' : '' }}>
                        {{ $p }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Email | Telefone -->
        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Email</label>
                <input type="email"
                    name="resp_email"
                    required
                    maxlength="150"
                    placeholder="Ex: marialucia@email.com"
                    value="{{ old('resp_email', $responsavel->resp_email) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>

            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Telefone</label>
                <input type="text"
                    name="resp_telefone"
                    id="resp_telefone"
                    required
                    placeholder="Ex: (99) 99999-9999"
                    value="{{ old('resp_telefone', $responsavel->resp_telefone) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>
        </div>

        <!-- CPF | CEP -->
        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">CPF</label>
                <input type="text"
                    name="resp_cpf"
                    maxlength="14"
                    required
                    placeholder="000.000.000-00"
                    value="{{ old('resp_cpf', $responsavel->resp_cpf) }}"
                    oninput="mascaraCPF(this)"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>

            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">CEP</label>
                <input type="text"
                    name="resp_cep"
                    id="resp_cep"
                    maxlength="9"
                    required
                    placeholder="00000-000"
                    value="{{ old('resp_cep', $responsavel->resp_cep) }}"
                    oninput="mascaraCEP(this)"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>
        </div>

        <!-- Cidade | Bairro -->
        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Cidade</label>
                <input type="text"
                    name="resp_cidade"
                    required
                    placeholder="Ex: São Paulo"
                    value="{{ old('resp_cidade', $responsavel->resp_cidade) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>

            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Bairro</label>
                <input type="text"
                    name="resp_bairro"
                    required
                    placeholder="Ex: Centro"
                    value="{{ old('resp_bairro', $responsavel->resp_bairro) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>
        </div>

        <!-- Logradouro | Número | Complemento -->
        <div class="flex gap-4 mb-6">
            <div class="flex-2">
                <label class="text-sm font-medium text-gray-600">Logradouro</label>
                <input type="text"
                    name="resp_logradouro"
                    required
                    placeholder="Rua, Avenida, etc."
                    value="{{ old('resp_logradouro', $responsavel->resp_logradouro) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>

            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Número</label>
                <input type="text"
                    name="resp_numero"
                    required
                    placeholder="Ex: 63"
                    value="{{ old('resp_numero', $responsavel->resp_numero) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>

            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Complemento</label>
                <input type="text"
                    name="resp_complemento"
                    placeholder="Ex: Bloco B, Apto 302"
                    value="{{ old('resp_complemento', $responsavel->resp_complemento) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>
        </div>

        <!-- AÇÕES -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('responsaveis') }}"
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
        let v = input.value.replace(/\D/g, '').slice(0, 11);
        v = v.replace(/(\d{3})(\d)/, '$1.$2')
            .replace(/(\d{3})(\d)/, '$1.$2')
            .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        input.value = v;
    }

    function mascaraCEP(input) {
        let v = input.value.replace(/\D/g, '').slice(0, 8);
        if (v.length > 5) v = v.replace(/^(\d{5})(\d)/, '$1-$2');
        input.value = v;
    }
</script>

@endsection