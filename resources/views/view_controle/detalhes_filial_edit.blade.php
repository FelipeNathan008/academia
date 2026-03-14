@extends('layouts.dashboard')

@section('title', 'Editar Detalhes da Filial')

@section('content')

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('filiais') }}" class="hover:text-[#8E251F] transition">
                Filiais
            </a>
        </li>
        <li>/</li>
        <li class="text-gray-400">{{ $detalhe->filial->filial_nome ?? '' }}</li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Editar Detalhes</li>
    </ol>
</nav>

<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-md p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Editar Detalhes da Filial ({{ $detalhe->filial->filial_nome ?? '' }})
    </h2>

    <form action="{{ route('detalhes-filial.update', $detalhe->id_det_filial) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- CEP -->
            <div>
                <label class="text-sm font-medium text-gray-600">CEP</label>
                <input type="text" name="det_filial_cep" required
                    value="{{ old('det_filial_cep', $detalhe->det_filial_cep) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                    placeholder="Ex: 12345-678">
            </div>

            <!-- Logradouro -->
            <div>
                <label class="text-sm font-medium text-gray-600">Logradouro</label>
                <input type="text" name="det_filial_logradouro" required
                    value="{{ old('det_filial_logradouro', $detalhe->det_filial_logradouro) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                    placeholder="Ex: Rua das Flores">
            </div>

            <!-- Número -->
            <div>
                <label class="text-sm font-medium text-gray-600">Número</label>
                <input type="text" name="det_filial_numero" required
                    value="{{ old('det_filial_numero', $detalhe->det_filial_numero) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                    placeholder="Ex: 123">
            </div>

            <!-- Complemento -->
            <div>
                <label class="text-sm font-medium text-gray-600">Complemento</label>
                <input type="text" name="det_filial_complemento"
                    value="{{ old('det_filial_complemento', $detalhe->det_filial_complemento) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                    placeholder="Opcional">
            </div>

            <!-- Bairro -->
            <div>
                <label class="text-sm font-medium text-gray-600">Bairro</label>
                <input type="text" name="det_filial_bairro" required
                    value="{{ old('det_filial_bairro', $detalhe->det_filial_bairro) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                    placeholder="Ex: Centro">
            </div>

            <!-- Cidade -->
            <div>
                <label class="text-sm font-medium text-gray-600">Cidade</label>
                <input type="text" name="det_filial_cidade" required
                    value="{{ old('det_filial_cidade', $detalhe->det_filial_cidade) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                    placeholder="Ex: São Paulo">
            </div>

            <!-- UF -->
            <div>
                <label class="text-sm font-medium text-gray-600">UF</label>
                <input type="text" name="det_filial_uf" required maxlength="2"
                    value="{{ old('det_filial_uf', $detalhe->det_filial_uf) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                    placeholder="Ex: SP">
            </div>

            <!-- Região -->
            <div>
                <label class="text-sm font-medium text-gray-600">Região</label>
                <input type="text" name="det_filial_regiao" required
                    value="{{ old('det_filial_regiao', $detalhe->det_filial_regiao) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                    placeholder="Ex: Sudeste">
            </div>

            <!-- País -->
            <div>
                <label class="text-sm font-medium text-gray-600">País</label>
                <input type="text" name="det_filial_pais" required
                    value="{{ old('det_filial_pais', $detalhe->det_filial_pais) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                    placeholder="Ex: Brasil">
            </div>

            <!-- CNPJ -->
            <div>
                <label class="text-sm font-medium text-gray-600">CNPJ</label>
                <input type="text" name="det_filial_cnpj"
                    value="{{ old('det_filial_cnpj', $detalhe->det_filial_cnpj) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                    placeholder="Opcional">
            </div>

            <!-- Email -->
            <div>
                <label class="text-sm font-medium text-gray-600">Email</label>
                <input type="email" name="det_filial_email" required
                    value="{{ old('det_filial_email', $detalhe->det_filial_email) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                    placeholder="Ex: contato@filial.com">
            </div>

            <!-- Telefone -->
            <div>
                <label class="text-sm font-medium text-gray-600">Telefone</label>
                <input type="text" name="det_filial_telefone" required
                    value="{{ old('det_filial_telefone', $detalhe->det_filial_telefone) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                    placeholder="Ex: (11) 99999-9999">
            </div>

        </div>

        <div class="flex justify-end gap-4 mt-6">
            <a href="{{ route('detalhes-filial.index', $detalhe->id_filial_id) }}"
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