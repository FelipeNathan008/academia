@extends('layouts.dashboard')

@section('title', 'Detalhes das Filiais')

@section('content')

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">Detalhes das Filiais</h2>
    </div>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">
        + Cadastrar Detalhes
    </button>
</div>

<!-- FORMULÁRIO DE CADASTRO -->
<div id="cadastroForm" class="hidden mb-10">
    <form id="formCadastro" action="{{ route('detalhes-filial.store', $idFilial) }}" method="POST">
        @csrf
        <input type="hidden" name="id_filial_id" value="{{ $idFilial }}">
        @csrf
        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">Cadastrar Detalhes da Filial</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Selecionar Filial -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Filial</label>
                    <select name="id_filial_id" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                        <option value="">Selecione a filial</option>
                        @foreach($filiais as $filial)
                        <option value="{{ $filial->id_filial }}">{{ $filial->filial_nome }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- CEP -->
                <div>
                    <label class="text-sm font-medium text-gray-600">CEP</label>
                    <input type="text" name="det_filial_cep" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                        placeholder="Ex: 12345-678">
                </div>

                <!-- Logradouro -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Logradouro</label>
                    <input type="text" name="det_filial_logradouro" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                        placeholder="Ex: Rua das Flores">
                </div>

                <!-- Número -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Número</label>
                    <input type="text" name="det_filial_numero" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                        placeholder="Ex: 123">
                </div>

                <!-- Complemento -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Complemento</label>
                    <input type="text" name="det_filial_complemento"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                        placeholder="Opcional">
                </div>

                <!-- Bairro -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Bairro</label>
                    <input type="text" name="det_filial_bairro" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                        placeholder="Ex: Centro">
                </div>

                <!-- Cidade -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Cidade</label>
                    <input type="text" name="det_filial_cidade" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                        placeholder="Ex: São Paulo">
                </div>

                <!-- UF -->
                <div>
                    <label class="text-sm font-medium text-gray-600">UF</label>
                    <input type="text" name="det_filial_uf" required maxlength="2"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                        placeholder="Ex: SP">
                </div>

                <!-- Região -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Região</label>
                    <input type="text" name="det_filial_regiao" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                        placeholder="Ex: Sudeste">
                </div>

                <!-- País -->
                <div>
                    <label class="text-sm font-medium text-gray-600">País</label>
                    <input type="text" name="det_filial_pais" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                        placeholder="Ex: Brasil">
                </div>

                <!-- CNPJ -->
                <div>
                    <label class="text-sm font-medium text-gray-600">CNPJ</label>
                    <input type="text" name="det_filial_cnpj"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                        placeholder="Opcional">
                </div>

                <!-- Email -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Email</label>
                    <input type="email" name="det_filial_email" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                        placeholder="Ex: contato@filial.com">
                </div>

                <!-- Telefone -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Telefone</label>
                    <input type="text" name="det_filial_telefone" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                        placeholder="Ex: (11) 99999-9999">
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
                    Salvar Detalhes
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
                    <a href="{{ route('detalhes-filial.edit', $detalhe->id_det_filial) }}"
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
</script>

@endsection