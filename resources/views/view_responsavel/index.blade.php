@extends('layouts.dashboard')

@section('title', 'Responsáveis')

@section('content')
<style>
    .responsavel-sem-aluno td {
        background-color: #fee2e2;
        /* equivalente ao red-100 */
    }

    .responsavel-sem-aluno:hover td {
        background-color: #fecaca;
        /* equivalente ao red-200 */
    }

    .btn-acoes {
        min-width: 120px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>

<x-alert-error />

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li class="font-semibold text-gray-700">Responsáveis</li>

        <li>/</li>
        <li class="text-gray-400">Alunos</li>
        <li>/</li>
        <li class="text-gray-400">Graduações</li>
        <li>/</li>
        <li class="text-gray-400">Matrículas</li>
        <li>/</li>
        <li class="text-gray-400">Financeiro</li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">

    <div class="flex items-center gap-4">
        <h2 class="text-3xl font-extrabold text-gray-800">
            Matrículas / Responsáveis
        </h2>

    </div>

    <button onclick="toggleCadastro()"
        class="btn-acoes px-6 bg-[#8E251F] text-white rounded-xl shadow-md
               hover:bg-[#732920] hover:shadow-lg transition-all">
        + Cadastrar Responsável
    </button>
</div>

<!-- FORMULÁRIO -->
<div id="cadastroForm" class="hidden mb-10">
    <form action="{{ route('responsaveis.store') }}" method="POST" onsubmit="bloquearSubmit(event, this)">
        @csrf

        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">
                Cadastrar Responsável
            </h3>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Nome e Tipo -->
                <div style="display: flex; gap: 4%;">
                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">Nome do Responsável</label>
                        <input type="text" name="resp_nome" required maxlength="120"
                            placeholder="Ex: Maria Lúcia"
                            oninput="validarNome(this)"
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                    </div>

                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">Tipo</label>
                        <select name="resp_parentesco" required
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                            <option value="">Selecione o Tipo</option>
                            <option>Responsável</option>
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
                    class="btn-acoes px-4 border rounded-lg hover:bg-gray-100">Cancelar</button>

                <x-button color="primary">
                    Salvar
                </x-button>

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
            <form method="GET" class="flex gap-2 items-end">

                <input type="text" name="nome"
                    value="{{ request('nome') }}"
                    placeholder="Digite o nome..."
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
               focus:ring-2 focus:ring-[#8E251F] focus:outline-none">

                <button type="submit"
                    class="btn-acoes px-6 rounded-xl bg-[#8E251F] text-white
               hover:bg-[#732920] transition shadow-md">
                    Buscar
                </button>

                <a href="{{ route('responsaveis') }}"
                    class="btn-acoes px-6 rounded-xl bg-gray-300 text-gray-800
           hover:bg-gray-400 transition shadow-md">
                    Limpar
                </a>

            </form>
        </div>

    </div>
</div>


<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700 flex items-center gap-4 flex-wrap">

        <span>RESPONSÁVEIS CADASTRADOS</span>

        <!-- TOTAL GERAL -->
        <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm">
            Total: {{ $totalResponsaveis }}
        </span>

        <!-- TOTAL FILTRADO -->
        <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm">
            Filtrados: {{ $responsaveis->total() }}
        </span>

    </h3>
    <table class="w-full">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4 text-left">Nome</th>
                <th class="py-3 px-4 text-left">Tipo</th>
                <th class="py-3 px-4 text-left">Telefone</th>
                <th class="py-3 px-4 text-left">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($responsaveis as $resp)
            <tr class="border-b transition linha-responsavel
                {{ $resp->alunos_count == 0 
                    ? 'responsavel-sem-aluno' 
                    : 'hover:bg-gray-50' }}">

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
                    <div class="flex gap-2">

                        <a href="{{ route('alunos', Crypt::encrypt($resp->id_responsavel)) }}"
                            style="background-color: #174ab9; color: white;"
                            class="btn-acoes px-4 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                            Alunos
                        </a>


                        <a href="{{ route('responsaveis.edit', Crypt::encrypt($resp->id_responsavel)) }}"
                            class="btn-acoes px-4 rounded-lg shadow text-white bg-[#8E251F] hover:bg-[#732920] transition">
                            Editar
                        </a>

                        <form action="{{ route('responsaveis.destroy', Crypt::encrypt($resp->id_responsavel)) }}"
                            method="POST"
                            onsubmit="return confirm('Deseja remover este responsável?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="btn-acoes px-4 rounded-lg shadow text-white bg-red-600 hover:bg-red-700 transition">
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
    <div class="mt-6">
        {{ $responsaveis->links() }}
    </div>
</div>

<script src="{{ asset('js/utils/buscarCep.js') }}"></script>
<script src="{{ asset('js/utils/mascaras.js') }}"></script>
<script src="{{ asset('js/utils/validacoes.js') }}"></script>
<script src="{{ asset('js/responsaveis/index.js') }}"></script>

@endsection