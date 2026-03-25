@extends('layouts.dashboard')

@section('title', 'Editar Detalhes da Filial')

@section('content')
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('filiais') }}" class="hover:text-[#8E251F] transition">
                Filiais
            </a>
        </li>
        <li>/</li>
        <li class="text-gray-400">{{ $filial->filial_apelido }}</li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Detalhes</li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Editar Detalhe</li>
    </ol>
</nav>

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-8">

    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Editar Detalhes – {{ $filial->filial_nome }}
    </h2>

    <form action="{{ route('detalhes-filial.update', Crypt::encrypt($detalhe->id_det_filial)) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- CEP + LOGRADOURO -->
        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label>CEP</label>
                <input type="text" id="det_filial_cep" name="det_filial_cep" required
                    placeholder="Ex: 12245-678"
                    value="{{ old('det_filial_cep', $detalhe->det_filial_cep) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1">
            </div>

            <div class="flex-1">
                <label>Logradouro</label>
                <input type="text" id="logradouro" name="det_filial_logradouro" required
                    placeholder="Ex: Avenida Andrômeda"
                    value="{{ old('det_filial_logradouro', $detalhe->det_filial_logradouro) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1">
            </div>
        </div>

        <!-- NUMERO + COMPLEMENTO -->
        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label>Número</label>
                <input type="text" name="det_filial_numero" required
                    placeholder="Ex: 1500"
                    value="{{ old('det_filial_numero', $detalhe->det_filial_numero) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1">
            </div>

            <div class="flex-1">
                <label>Complemento</label>
                <input type="text" name="det_filial_complemento"
                    placeholder="Ex: Bloco A, Sala 3"
                    value="{{ old('det_filial_complemento', $detalhe->det_filial_complemento) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1">
            </div>
        </div>

        <!-- BAIRRO + CIDADE -->
        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label>Bairro</label>
                <input type="text" id="bairro" name="det_filial_bairro" required
                    placeholder="Ex: Jardim Satélite"
                    value="{{ old('det_filial_bairro', $detalhe->det_filial_bairro) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1">
            </div>

            <div class="flex-1">
                <label>Cidade</label>
                <input type="text" id="cidade" name="det_filial_cidade" required
                    placeholder="Ex: São José dos Campos"
                    value="{{ old('det_filial_cidade', $detalhe->det_filial_cidade) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1">
            </div>
        </div>

        <!-- UF + REGIÃO -->
        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label>UF</label>
                <select id="uf" name="det_filial_uf" class="w-full border rounded-lg px-4 py-2 mt-1">
                    @foreach([
                    'AC','AL','AP','AM','BA','CE','DF','ES','GO','MA',
                    'MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN',
                    'RS','RO','RR','SC','SP','SE','TO'
                    ] as $uf)
                    <option value="{{ $uf }}"
                        {{ $detalhe->det_filial_uf == $uf ? 'selected' : '' }}>
                        {{ $uf }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1">
                <label>Região</label>
                <input type="text" name="det_filial_regiao" required
                    placeholder="Ex: Sudeste"
                    value="{{ old('det_filial_regiao', $detalhe->det_filial_regiao) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1">
            </div>
        </div>

        <!-- PAIS + CNPJ -->
        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label>País</label>
                <input type="text" name="det_filial_pais" required
                    placeholder="Ex: Brasil"
                    value="{{ old('det_filial_pais', $detalhe->det_filial_pais) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1">
            </div>

            <div class="flex-1">
                <label>CNPJ</label>
                <input type="text" id="det_filial_cnpj" name="det_filial_cnpj"
                    placeholder="Ex: 12.345.678/0001-90"
                    value="{{ old('det_filial_cnpj', $detalhe->det_filial_cnpj) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1">
            </div>
        </div>

        <!-- EMAIL + TELEFONE -->
        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label>Email</label>
                <input type="email" name="det_filial_email" required
                    placeholder="Ex: filial@empresa.com"
                    value="{{ old('det_filial_email', $detalhe->det_filial_email) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1">
            </div>

            <div class="flex-1">
                <label>Telefone</label>
                <input type="text" id="det_filial_telefone" name="det_filial_telefone" required
                    placeholder="Ex: (12) 99777-6655"
                    value="{{ old('det_filial_telefone', $detalhe->det_filial_telefone) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1">
            </div>
        </div>

        <!-- AÇÕES -->
        <div class="flex justify-end gap-4 mt-6">
            <a href="{{ route('detalhes-filial.index', Crypt::encrypt($filial->id_filial)) }}"
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

<!-- JS IGUAL AO CREATE -->
<script>
    // TELEFONE
    document.getElementById('det_filial_telefone').addEventListener('input', function() {
        let v = this.value.replace(/\D/g, '').slice(0, 11);
        if (v.length > 0) v = '(' + v.slice(0, 2) + ') ' + v.slice(2);
        if (v.length > 10) v = v.replace(/(\d{5})(\d{4})/, '$1-$2');
        this.value = v;
    });

    // CEP + VIA CEP
    const cepInput = document.getElementById('det_filial_cep');

    cepInput.addEventListener('input', async () => {
        let cep = cepInput.value.replace(/\D/g, '').slice(0, 8);

        if (cep.length > 5) cep = cep.replace(/(\d{5})(\d)/, '$1-$2');
        cepInput.value = cep;

        if (cep.replace(/\D/g, '').length !== 8) return;

        try {
            const res = await fetch(`https://viacep.com.br/ws/${cep.replace(/\D/g, '')}/json/`);
            const data = await res.json();

            if (data.erro) return;

            document.getElementById('logradouro').value = data.logradouro || '';
            document.getElementById('bairro').value = data.bairro || '';
            document.getElementById('cidade').value = data.localidade || '';
            document.getElementById('uf').value = data.uf || '';

        } catch (e) {
            console.error('Erro CEP:', e);
        }
    });

    // CNPJ
    document.getElementById('det_filial_cnpj').addEventListener('input', function(e) {
        let v = e.target.value;

        v = v.replace(/[^a-zA-Z0-9]/g, '');

        if (v.length > 14) v = v.slice(0, 14);

        v = v.replace(/^([a-zA-Z0-9]{2})([a-zA-Z0-9])/, '$1.$2');
        v = v.replace(/^([a-zA-Z0-9]{2})\.([a-zA-Z0-9]{3})([a-zA-Z0-9])/, '$1.$2.$3');
        v = v.replace(/\.(\w{3})(\w)/, '.$1/$2');
        v = v.replace(/(\w{4})(\w)/, '$1-$2');

        e.target.value = v.toUpperCase();
    });
</script>

@endsection