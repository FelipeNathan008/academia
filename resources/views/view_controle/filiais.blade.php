@extends('layouts.dashboard')

@section('title', 'Filiais')

@section('content')

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">Filiais</h2>
    </div>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">
        + Cadastrar Filial
    </button>
</div>

<!-- FORMULÁRIO DE CADASTRO -->
<div id="cadastroForm" class="hidden mb-10">
    <form id="formCadastro" action="{{ route('filiais.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">Cadastrar Filial</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Empresa -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Empresa</label>
                    <select name="id_emp_id" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                        <option value="">Selecione a empresa</option>
                        @foreach($empresas as $empresa)
                        <option value="{{ $empresa->id_empresa }}">{{ $empresa->emp_nome }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nome -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Nome da Filial</label>
                    <input type="text"
                        name="filial_nome"
                        maxlength="100"
                        required
                        placeholder="Ex: Filial Centro"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>

                <!-- Apelido -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Apelido da Filial</label>
                    <input type="text"
                        name="filial_apelido"
                        maxlength="50"
                        required
                        placeholder="Ex: Centro"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>

                <!-- Nome do Responsável -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Nome do Responsável</label>
                    <input type="text"
                        name="filial_nome_responsavel"
                        maxlength="100"
                        required
                        placeholder="Ex: João Silva"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>

                <!-- Email do Responsável -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Email do Responsável</label>
                    <input type="email"
                        name="filial_email_responsavel"
                        maxlength="100"
                        required
                        placeholder="Ex: joao@email.com"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>

                <!-- Telefone do Responsável -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Telefone do Responsável</label>
                    <input type="text"
                        name="filial_telefone_responsavel"
                        maxlength="20"
                        required
                        placeholder="Ex: (11) 99999-9999"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>

                <!-- CPF do Responsável -->
                <div>
                    <label class="text-sm font-medium text-gray-600">CPF do Responsável</label>
                    <input type="text"
                        name="filial_cpf"
                        maxlength="20"
                        placeholder="Ex: 000.000.000-00"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>

                <!-- Foto -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Foto (URL)</label>
                    <input type="text"
                        name="filial_foto"
                        placeholder="Ex: http://site.com/foto.jpg"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
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
                    Salvar Filial
                </button>
            </div>
        </div>
    </form>
</div>

<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">Lista de Filiais</h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gray-300 text-gray-600 text-sm">
                <th class="py-3 px-4">Nome</th>
                <th class="py-3 px-4">Apelido</th>
                <th class="py-3 px-4">Responsável</th>
                <th class="py-3 px-4">Email</th>
                <th class="py-3 px-4">Telefone</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($filiais as $filial)
            <tr class="border-b hover:bg-gray-50 transition">
                <td class="py-3 px-4">{{ $filial->filial_nome }}</td>
                <td class="py-3 px-4">{{ $filial->filial_apelido }}</td>
                <td class="py-3 px-4">{{ $filial->filial_nome_responsavel }}</td>
                <td class="py-3 px-4">{{ $filial->filial_email_responsavel }}</td>
                <td class="py-3 px-4">{{ $filial->filial_telefone_responsavel }}</td>

                <td class="py-3 px-4 flex gap-2">

                    <a href="{{ route('detalhes-filial.index', $filial->id_filial) }}"
                        style="background-color: #174ab9; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                        Endereços/Contatos
                    </a>

                    <a href="#"
                        style="background-color: #15803d; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                        Users
                    </a>

                    <!-- Editar -->
                    <a href="{{ route('filiais.edit', $filial->id_filial) }}"
                        style="background-color: #8E251F; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                        Editar
                    </a>

                    <!-- Excluir -->
                    <form action="{{ route('filiais.destroy', $filial->id_filial) }}"
                        method="POST"
                        onsubmit="return confirm('Deseja excluir esta filial?');">
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
                <td colspan="6" class="text-center text-gray-500 py-6">
                    Nenhuma filial cadastrada
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
</script>

@endsection