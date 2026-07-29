@extends('layouts.dashboard')

@section('title', 'Graduações')

@section('content')


<x-alert-error />

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.administracao') }}"
            class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>
        <h2 class="text-3xl font-extrabold text-gray-800">Graduações</h2>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('graduacoes.molde.index') }}"
            style="background-color: #174AB9; color: #fff; padding: 12px 24px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,.15); text-decoration: none; transition: .2s;"
            onmouseover="this.style.backgroundColor='#123C96'; this.style.boxShadow='0 6px 12px rgba(0,0,0,.2)'"
            onmouseout="this.style.backgroundColor='#174AB9'; this.style.boxShadow='0 4px 6px rgba(0,0,0,.15)'">
            📋 Copiar molde
        </a>

        <button onclick="toggleCadastro()"
            class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">
            + Cadastrar Graduação
        </button>
    </div>
</div>

<!-- FORMULÁRIO DE CADASTRO -->
<div id="cadastroForm" class="hidden mb-10">
    <form id="formCadastro" action="{{ route('graduacoes.store') }}" method="POST" onsubmit="bloquearSubmit(event, this)">
        @csrf
        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 id="tituloCadastro" class="text-xl font-bold mb-6 text-gray-700">Cadastrar Graduação</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-600">Modalidade</label>

                    <select name="id_modalidade"
                        required
                        class="w-full border rounded-lg px-4 py-2 mt-1">

                        <option value="">Selecione</option>

                        @foreach($modalidades as $modalidade)
                        <option value="{{ $modalidade->id_modalidade }}">
                            {{ $modalidade->mod_nome }}
                        </option>
                        @endforeach

                    </select>
                </div>

                <!-- Nome/Cor -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Nome / Cor</label>
                    <input type="text" name="gradu_nome_cor" maxlength="80" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                        placeholder="Ex: Faixa Branca">
                </div>

                <!-- Grau 
                <div>
                    <label class="text-sm font-medium text-gray-600">Grau</label>
                    <input type="number" name="gradu_grau" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                        placeholder="Ex: 1">
                </div> -->

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Ordem
                    </label>

                    <input
                        type="number"
                        name="gradu_ordem"
                        maxlength="60"
                        required
                        class="w-full border rounded-lg px-4 py-2 mt-1"
                        placeholder="Ex: 1">
                </div>

                <!-- Meta de Aulas -->
                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Meta (Quantidade de Aulas)
                    </label>
                    <input type="number"
                        name="gradu_meta"
                        required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                        placeholder="Ex: 30">
                </div>
            </div>

            <!-- AÇÕES -->
            <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                <button type="button" onclick="fecharCadastro()"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-100 transition">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920] transition">
                    Salvar Graduação
                </button>
            </div>
        </div>
    </form>

</div>

<!-- FILTROS -->
<div class="bg-white rounded-2xl shadow-md p-6 mb-8">

    <div class="flex justify-center">
        <div class="flex flex-wrap gap-6 items-end justify-center">

            <div class="flex flex-col w-[250px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Modalidade
                </label>

                <select id="filtroModalidade"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white">

                    <option value="">Selecione uma modalidade</option>

                    @foreach($modalidades as $modalidade)
                    <option value="{{ $modalidade->id_modalidade }}">
                        {{ $modalidade->mod_nome }}
                    </option>
                    @endforeach

                </select>
            </div>

            <!-- Nome / Cor -->
            <div class="flex flex-col w-[250px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Nome / Cor
                </label>
                <select id="filtroNome" disabled
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">
                    <option value="">Primeiro selecione uma modalidade</option>
                    @foreach($graduacoes->pluck('gradu_nome_cor')->unique() as $nome)
                    <option value="{{ strtolower($nome) }}">
                        {{ $nome }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Grau 
            <div class="flex flex-col w-[150px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Grau
                </label>
                <select id="filtroGrau" disabled
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">
                    <option value="">Primeiro selecione uma modalidade</option>
                    @foreach($graduacoes->pluck('gradu_grau')->unique() as $grau)
                    <option value="{{ $grau }}">
                        {{ $grau }}
                    </option>
                    @endforeach
                </select>
            </div> -->

            <!-- Limpar -->
            <button id="limparFiltros"
                class="h-[48px] px-6 rounded-xl bg-gray-300
                       text-gray-800 font-semibold hover:bg-gray-400
                       transition shadow-md">
                Limpar filtros
            </button>

        </div>
    </div>

</div>


<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">Lista de Graduações</h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gray-300 text-gray-600 text-sm">
                <th class="py-3 px-4">Modalidade</th>
                <th class="py-3 px-4">Nome / Cor</th>
                <th class="py-3 px-4">Ordem</th>
                <th class="py-3 px-4">Meta (aulas)</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            <tr id="msgSelecioneModalidade">
                <td colspan="5" class="text-center py-6 text-gray-500">
                    🔍 Selecione uma <strong>modalidade</strong> no filtro acima para visualizar as graduações cadastradas, ou cadastre a primeira.
                </td>
            </tr>

            @forelse ($graduacoes as $graduacao)
            <tr class="border-b hover:bg-gray-50 transition linha-graduacao"
                data-modalidade="{{ $graduacao->id_modalidade }}"
                data-nome="{{ strtolower($graduacao->gradu_nome_cor) }}">

                <td class="py-3 px-4">
                    {{ $graduacao->modalidade->mod_nome ?? '-' }}
                </td>

                <td class="py-3 px-4">
                    <span
                        class="bolinha-faixa"
                        data-faixa="{{ strtolower($graduacao->gradu_nome_cor) }}"
                        style="
                                display:inline-block;
                                width:20px;
                                height:20px;
                                border:2px solid #000;
                                border-radius:50%;
                                margin-right:8px;
                                vertical-align:middle;
                                background-color:transparent;
                            ">
                    </span>

                    {{ $graduacao->gradu_nome_cor }}
                </td>


                <td class="py-3 px-4">
                    {{ $graduacao->gradu_ordem ?? '-'}}
                </td>

                <td class="py-3 px-4">
                    {{ $graduacao->gradu_meta ?? '-' }}
                </td>


                <td class="py-3 px-4 flex gap-2">
                    <!-- Editar -->
                    <a href="{{ route('graduacoes.edit', Crypt::encrypt($graduacao->id_graduacao)) }}"
                        style="background-color: #8E251F; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                        Editar
                    </a>

                    <!-- Excluir -->
                    <form action="{{ route('graduacoes.destroy',  Crypt::encrypt($graduacao->id_graduacao)) }}" method="POST"
                        onsubmit="return confirm('Deseja excluir esta graduação?');">
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
            <tr class="linha-vazia-geral">
                <td colspan="5" class="text-center text-gray-500 py-6">
                    Nenhuma graduação cadastrada
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>

<script src="{{ asset('js/faixas.js') }}"></script>
<script>
    aplicarCoresFaixas();

    function bloquearSubmit(event, form) {
        if (!form.checkValidity()) {
            return;
        }
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Salvando...';
        }
    }

    const filtroModalidade = document.getElementById('filtroModalidade');
    const filtroNome = document.getElementById('filtroNome');
    const limparBtn = document.getElementById('limparFiltros');
    const linhas = document.querySelectorAll('.linha-graduacao');

    // Salva a modalidade escolhida
    filtroModalidade.addEventListener('change', function() {
        localStorage.setItem('graduacao_modalidade', this.value);
    });

    // Restaura a modalidade ao carregar a página
    document.addEventListener('DOMContentLoaded', function() {

        const modalidadeSalva = localStorage.getItem('graduacao_modalidade');

        if (modalidadeSalva) {

            filtroModalidade.value = modalidadeSalva;

            // dispara o evento para preencher Nome
            filtroModalidade.dispatchEvent(
                new Event('change')
            );
        } else {
            aplicarFiltro();
        }
    });

    function aplicarFiltro() {

        const modalidade = filtroModalidade.value;
        const nome = filtroNome.value;

        const msgSelecione = document.getElementById('msgSelecioneModalidade');

        // Mostra a mensagem só quando nenhuma modalidade foi selecionada
        if (msgSelecione) {
            msgSelecione.style.display = modalidade ? 'none' : '';
        }

        linhas.forEach(linha => {

            const modalidadeLinha = linha.dataset.modalidade;
            const nomeLinha = linha.dataset.nome;

            let mostrar = true;

            // NÃO mostra nada enquanto não selecionar modalidade
            if (!modalidade) {
                mostrar = false;
            }

            if (modalidade && modalidadeLinha !== modalidade) {
                mostrar = false;
            }

            if (nome && nomeLinha !== nome) {
                mostrar = false;
            }

            linha.style.display = mostrar ? '' : 'none';
        });
    }

    filtroModalidade.addEventListener('change', function() {

        const modalidade = this.value;

        filtroNome.innerHTML = '<option value="">Todas</option>';

        filtroNome.value = '';

        if (!modalidade) {

            filtroNome.disabled = true;

            filtroNome.innerHTML =
                '<option value="">Primeiro selecione uma modalidade</option>';


            aplicarFiltro();
            return;
        }

        filtroNome.disabled = false;

        const nomes = new Set();

        linhas.forEach(linha => {

            if (linha.dataset.modalidade === modalidade) {

                nomes.add(linha.dataset.nome);
            }
        });

        [...nomes].sort().forEach(nome => {

            filtroNome.innerHTML += `
                <option value="${nome}">
                    ${nome.charAt(0).toUpperCase() + nome.slice(1)}
                </option>
            `;
        });

        aplicarFiltro();
    });

    filtroNome.addEventListener('change', aplicarFiltro);

    limparBtn.addEventListener('click', function() {

        localStorage.removeItem('graduacao_modalidade');

        filtroModalidade.value = '';

        filtroNome.disabled = true;

        filtroNome.innerHTML =
            '<option value="">Primeiro selecione uma modalidade</option>';



        aplicarFiltro();
    });

    function toggleCadastro() {

        const form = document.getElementById('cadastroForm');

        form.classList.toggle('hidden');

        form.scrollIntoView({
            behavior: 'smooth'
        });

        document.getElementById('formCadastro').reset();
    }

    function fecharCadastro() {

        document.getElementById('cadastroForm')
            .classList.add('hidden');
    }
</script>

@endsection