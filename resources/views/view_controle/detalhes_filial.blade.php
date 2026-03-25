@extends('layouts.dashboard')

@section('title', 'Detalhes das Filiais')

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
        <li class="text-gray-400">{{ $filial->filial_apelido }}</li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Detalhes da Filial</li>
    </ol>
</nav>
<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('filiais') }}"
            class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>

        <h2 class="text-3xl font-extrabold text-gray-800">
            Detalhes da Filial
        </h2>
    </div>
    @if(!$temDetalhe)
    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] transition-all">
        + Cadastrar Detalhes
    </button>
    @else
    <button disabled
        class="px-6 py-3 bg-gray-300 text-gray-600 rounded-xl cursor-not-allowed">
        Detalhe já cadastrado
    </button>
    @endif
</div>

<!-- CARD DA FILIAL -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#8E251F] rounded-2xl shadow-lg p-6">
        <p class="text-xs uppercase tracking-widest text-gray-500">
            Filial selecionada
        </p>

        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
            {{ $filial->filial_apelido }}
        </h3>

        <p class="mt-2 text-sm text-gray-800">
            Nome: <strong>{{ $filial->filial_nome ?? '-' }}</strong>
        </p>

        <p class="text-sm text-gray-800">
            Responsável: <strong>{{ $filial->filial_nome_responsavel ?? '-' }}</strong>
        </p>
    </div>
</div>

<!-- FORMULÁRIO -->
<div id="cadastroForm" class="hidden mb-10">
    <form id="formCadastro" action="{{ route('detalhes-filial.store', Crypt::encrypt($idFilial)) }}" method="POST">
        @csrf
        <input type="hidden" name="id_filial_id" value="{{ $idFilial }}">

        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">Cadastrar Detalhes da Filial</h3>

            <div class="flex flex-col gap-4">

                <!-- CEP + LOGRADOURO -->
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="text-sm font-medium text-gray-600">CEP</label>
                        <input type="text" id="det_filial_cep" name="det_filial_cep" required
                            placeholder="Ex: 12245-678"
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>

                    <div class="flex-1">
                        <label class="text-sm font-medium text-gray-600">Logradouro</label>
                        <input id="logradouro" type="text" name="det_filial_logradouro" required
                            placeholder="Ex: Avenida Andrômeda"
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>
                </div>

                <!-- NUMERO + COMPLEMENTO -->
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="text-sm font-medium text-gray-600">Número</label>
                        <input type="text" name="det_filial_numero" required
                            placeholder="Ex: 1500"
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>

                    <div class="flex-1">
                        <label class="text-sm font-medium text-gray-600">Complemento</label>
                        <input type="text" name="det_filial_complemento"
                            placeholder="Ex: Bloco A, Sala 3"
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>
                </div>

                <!-- BAIRRO + CIDADE -->
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="text-sm font-medium text-gray-600">Bairro</label>
                        <input id="bairro" type="text" name="det_filial_bairro" required
                            placeholder="Ex: Jardim Satélite"
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>

                    <div class="flex-1">
                        <label class="text-sm font-medium text-gray-600">Cidade</label>
                        <input id="cidade" type="text" name="det_filial_cidade" required
                            placeholder="Ex: São José dos Campos"
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>
                </div>

                <!-- UF + REGIÃO -->
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="text-sm font-medium text-gray-600">UF</label>
                        <select id="uf" name="det_filial_uf" required
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                            <option value="">Selecione o estado</option>
                            @foreach([
                            'AC','AL','AP','AM','BA','CE','DF','ES','GO','MA',
                            'MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN',
                            'RS','RO','RR','SC','SP','SE','TO'
                            ] as $uf)
                            <option value="{{ $uf }}">{{ $uf }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-1">
                        <label class="text-sm font-medium text-gray-600">Região</label>
                        <input type="text" name="det_filial_regiao" required
                            placeholder="Ex: Sudeste"
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>
                </div>

                <!-- PAIS + CNPJ -->
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="text-sm font-medium text-gray-600">País</label>
                        <input type="text" name="det_filial_pais" required
                            value="Brasil"
                            placeholder="Ex: Brasil"
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>

                    <div class="flex-1">
                        <label class="text-sm font-medium text-gray-600">CNPJ</label>
                        <input type="text" id="det_filial_cnpj" name="det_filial_cnpj"
                            placeholder="Ex: 12.345.678/0001-90"
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>
                </div>

                <!-- EMAIL + TELEFONE -->
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="text-sm font-medium text-gray-600">Email</label>
                        <input type="email" name="det_filial_email" required
                            placeholder="Ex: filial@empresa.com"
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>

                    <div class="flex-1">
                        <label class="text-sm font-medium text-gray-600">Telefone</label>
                        <input type="text" id="det_filial_telefone" name="det_filial_telefone" required
                            placeholder="Ex: (12) 99777-6655"
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>
                </div>

            </div>

            <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                <button type="button" onclick="fecharCadastro()" class="px-4 py-2 border rounded-lg">
                    Cancelar
                </button>

                <button type="submit" class="px-5 py-2 bg-[#8E251F] text-white rounded-lg">
                    Salvar
                </button>
            </div>
        </div>
    </form>
</div>

<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">Lista de Detalhes das Filiais</h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gray-300 text-gray-600 text-sm">
                <th class="py-3 px-4">Filial</th>
                <th class="py-3 px-4">CEP</th>
                <th class="py-3 px-4">Cidade</th>
                <th class="py-3 px-4">UF</th>
                <th class="py-3 px-4">Email</th>
                <th class="py-3 px-4">Telefone</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($detalhes as $detalhe)
            <tr class="border-b hover:bg-gray-50 transition">
                <td class="py-3 px-4">{{ $detalhe->filial->filial_nome ?? '' }}</td>
                <td class="py-3 px-4">{{ $detalhe->det_filial_cep }}</td>
                <td class="py-3 px-4">{{ $detalhe->det_filial_cidade }}</td>
                <td class="py-3 px-4">{{ $detalhe->det_filial_uf }}</td>
                <td class="py-3 px-4">{{ $detalhe->det_filial_email }}</td>
                <td class="py-3 px-4">{{ $detalhe->det_filial_telefone }}</td>

                <td class="py-3 px-4 flex gap-2">
                    <!-- Editar -->
                    <a href="{{ route('detalhes-filial.edit', Crypt::encrypt($detalhe->id_det_filial)) }}"
                        style="background-color: #8E251F; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                        Editar
                    </a>

                    <!-- Excluir -->
                    <form action="{{ route('detalhes-filial.destroy', $detalhe->id_det_filial) }}"
                        method="POST"
                        onsubmit="return confirm('Deseja excluir este detalhe da filial?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            style="background-color: #c02600; color: white;"
                            class="px-4 py-2 rounded-lg shadow hover:bg-[#D65A3E] transition duration-200">
                            Excluir
                        </button>
                    </form>
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="7" class="text-center text-gray-500 py-6">
                    Nenhum detalhe cadastrado
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>


<!-- JS -->
<script>
    function toggleCadastro() {
        const form = document.getElementById('cadastroForm');
        form.classList.toggle('hidden');
        form.scrollIntoView({
            behavior: 'smooth'
        });
        document.getElementById('formCadastro').reset();
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }

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
    document.getElementById('det_filial_cnpj').addEventListener('input', function(e) {
        let v = e.target.value;

        // Remove tudo que não for letra ou número
        v = v.replace(/[^a-zA-Z0-9]/g, '');

        // Limita tamanho (14 padrão atual, mas aceita letras)
        if (v.length > 14) v = v.slice(0, 14);

        // Aplica máscara
        v = v.replace(/^([a-zA-Z0-9]{2})([a-zA-Z0-9])/, '$1.$2');
        v = v.replace(/^([a-zA-Z0-9]{2})\.([a-zA-Z0-9]{3})([a-zA-Z0-9])/, '$1.$2.$3');
        v = v.replace(/\.(\w{3})(\w)/, '.$1/$2');
        v = v.replace(/(\w{4})(\w)/, '$1-$2');

        e.target.value = v.toUpperCase(); // padrão mais limpo
    });
</script>

@endsection