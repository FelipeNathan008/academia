@extends('layouts.dashboard')

@section('title', 'Responsáveis')

@section('content')

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">

    <div class="flex items-center gap-4">
        <h2 class="text-3xl font-extrabold text-gray-800">
            Responsáveis
        </h2>
    </div>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md
               hover:bg-[#732920] hover:shadow-lg transition-all">
        + Cadastrar Responsável
    </button>
</div>



<!-- FORMULÁRIO -->
<div id="cadastroForm" class="hidden mb-10">
    <form action="{{ route('responsaveis.store') }}" method="POST">
        @csrf

        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">
                Cadastrar Responsável
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Nome e Parentesco -->
                <div style="display: flex; gap: 4%;">
                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">Nome do Responsável</label>
                        <input type="text" name="resp_nome" required maxlength="120"
                            placeholder="Ex: Maria Lúcia"
                            oninput="validarNome(this)"
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                    </div>

                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">Parentesco</label>
                        <select name="resp_parentesco" required
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                            <option value="">Selecione o parentesco</option>
                            <option>Pai</option>
                            <option>Mãe</option>
                            <option>Responsável Legal</option>
                            <option>Avô(ó)</option>
                            <option>Tio(a)</option>
                            <option>Outro</option>
                        </select>
                    </div>
                </div>

                <!-- Telefone e Email -->
                <div style="display: flex; gap: 4%;">
                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">Email</label>
                        <input type="email" name="resp_email" required maxlength="150"
                            placeholder="Ex: marialucia@email.com"
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                    </div>

                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">Telefone</label>
                        <input type="text" id="resp_telefone" name="resp_telefone" required
                            placeholder="Ex: (99) 99999-9999"
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                    </div>
                </div>


                <!-- CPF e CEP -->
                <div style="display: flex; gap: 4%;">
                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">CPF</label>
                        <input type="text" name="resp_cpf" required maxlength="14"
                            placeholder="000.000.000-00"
                            oninput="mascaraCPF(this)"
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                    </div>

                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">CEP</label>
                        <input type="text" name="resp_cep" id="resp_cep" required maxlength="9"
                            placeholder="00000-000" oninput="mascaraCEP(this)" onblur="buscarCEP()"
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                    </div>
                </div>

                <!-- Cidade e Bairro -->
                <div style="display: flex; gap: 4%; margin-top: 1rem;">
                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">Cidade</label>
                        <input type="text" name="resp_cidade" required placeholder="Ex: São Paulo"
                            oninput="validarTexto(this)"
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                    </div>

                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">Bairro</label>
                        <input type="text" name="resp_bairro" required placeholder="Ex: Centro"
                            oninput="validarTexto(this)"
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                    </div>
                </div>

                <!-- Logradouro, Número e Complemento -->
                <div style="display: flex; gap: 4%; margin-top: 1rem;">
                    <div style="flex: 2;">
                        <label class="text-sm font-medium text-gray-600">Logradouro</label>
                        <input type="text" name="resp_logradouro" required placeholder="Rua, Avenida, etc."
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                    </div>

                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">Número</label>
                        <input type="text" name="resp_numero" placeholder="Ex: 63" required
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                    </div>

                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">Complemento</label>
                        <input type="text" name="resp_complemento" placeholder="Ex: Bloco B, Apto 302"
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                    </div>
                </div>

            </div>

            <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                <button type="button" onclick="fecharCadastro()"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-100">Cancelar</button>

                <button type="submit"
                    class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">Salvar</button>
            </div>
        </div>
    </form>
</div>


<!-- FILTRO -->
<div class="bg-white rounded-2xl shadow-md p-6 mb-8">
    <div class="flex flex-wrap gap-6 items-end justify-center max-w-6xl mx-auto">

        <div class="flex flex-col w-[400px]">
            <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                Buscar Responsável
            </label>
            <input type="text" id="filtroNomeResponsavel"
                placeholder="Digite o nome..."
                class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                       focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
        </div>

        <button id="limparFiltroResponsavel"
            class="h-[48px] px-8 rounded-xl bg-gradient-to-r from-gray-300 to-gray-400
                   text-gray-800 font-semibold hover:from-gray-400 hover:to-gray-500
                   transition shadow-md">
            Limpar filtro
        </button>

    </div>
</div>


<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">
        Responsáveis Cadastrados
    </h3>

    <table class="w-full">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4 text-left">Nome</th>
                <th class="py-3 px-4 text-left">Parentesco</th>
                <th class="py-3 px-4 text-left">Telefone</th>
                <th class="py-3 px-4 text-left">Endereço</th>
                <th class="py-3 px-4 text-left">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($responsaveis as $resp)
            <tr class="border-b hover:bg-gray-50 transition linha-responsavel"
                data-nome="{{ strtolower($resp->resp_nome) }}">

                <td class="py-3 px-4 ">
                    {{ $resp->resp_nome }}
                </td>

                <td class="py-3 px-4">
                    {{ $resp->resp_parentesco }}
                </td>

                <td class="py-3 px-4">
                    {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $resp->resp_telefone) }}
                </td>

                <td class="py-3 px-4">
                    {{ $resp->resp_logradouro }}
                    @if($resp->resp_numero), {{ $resp->resp_numero }}@endif
                    @if($resp->resp_complemento) - {{ $resp->resp_complemento }}@endif
                    , {{ $resp->resp_bairro }}
                    @if($resp->resp_cidade) - {{ $resp->resp_cidade }}@endif
                </td>

                <td class="py-3 px-4">
                    <div class="flex gap-2">

                        <a href="{{ route('alunos', $resp->id_responsavel) }}"
                            style="background-color: #174ab9; color: white;"
                            class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center"> 
                            Alunos
                        </a>

                        <a href="{{ route('responsaveis.edit', $resp->id_responsavel) }}"
                            class="px-4 py-2 rounded-lg shadow text-white bg-[#8E251F] hover:bg-[#732920] transition">
                            Editar
                        </a>

                        <form action="{{ route('responsaveis.destroy', $resp->id_responsavel) }}"
                            method="POST"
                            onsubmit="return confirm('Deseja remover este responsável?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="px-4 py-2 rounded-lg shadow text-white bg-red-600 hover:bg-red-700 transition">
                                Excluir
                            </button>
                        </form>

                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-6 text-gray-500">
                    Nenhum responsável cadastrado
                </td>
            </tr>
            @endforelse
        </tbody>

    </table>
</div>

<script>
    function toggleCadastro() {
        const form = document.getElementById('cadastroForm');
        form.classList.toggle('hidden');
        form.scrollIntoView({
            behavior: 'smooth'
        });
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }

    function validarNome(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
    }

    function validarTexto(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ0-9\s]/g, '');
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

    const tel = document.getElementById('resp_telefone');

    tel.addEventListener('input', () => {
        let v = tel.value.replace(/\D/g, '');

        if (v.length > 11) v = v.slice(0, 11);

        let formatado = '';

        if (v.length > 0) formatado = '(' + v.slice(0, 2);
        if (v.length >= 3) formatado += ') ' + v.slice(2, 7);
        if (v.length >= 8) formatado += '-' + v.slice(7, 11);

        tel.value = formatado;
    });

    function buscarCEP() {
        const cepInput = document.getElementById('resp_cep');
        const cep = cepInput.value.replace(/\D/g, '');
        if (cep.length !== 8) return;

        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(res => res.json())
            .then(data => {
                if (data.erro) {
                    alert('CEP não encontrado');
                    return;
                }
                document.querySelector('input[name="resp_logradouro"]').value = data.logradouro || '';
                document.querySelector('input[name="resp_bairro"]').value = data.bairro || '';
                document.querySelector('input[name="resp_cidade"]').value = data.localidade || '';
            })
            .catch(() => alert('Erro ao buscar o CEP'));
    }

    document.addEventListener('DOMContentLoaded', function() {

        const filtroNome = document.getElementById('filtroNomeResponsavel');
        const limparBtn = document.getElementById('limparFiltroResponsavel');
        const linhas = document.querySelectorAll('.linha-responsavel');

        function aplicarFiltro() {
            const nome = filtroNome.value.toLowerCase();

            linhas.forEach(linha => {
                const nomeResponsavel = linha.dataset.nome || '';

                let mostrar = true;

                if (nome && !nomeResponsavel.includes(nome)) {
                    mostrar = false;
                }

                linha.style.display = mostrar ? '' : 'none';
            });
        }

        filtroNome.addEventListener('input', aplicarFiltro);

        limparBtn.addEventListener('click', function() {
            filtroNome.value = '';
            aplicarFiltro();
        });

    });
</script>

@endsection