@extends('layouts.dashboard')

@section('title', 'Modalidades')

@section('content')

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">Modalidades</h2>
    </div>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">
        + Cadastrar Modalidade
    </button>
</div>

<!-- FORMULÁRIO DE CADASTRO -->
<div id="cadastroForm" class="hidden mb-10">
    <form id="formCadastro" action="{{ route('modalidades.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">Cadastrar Modalidade</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Nome da Modalidade</label>
                    <input type="text"
                        name="mod_nome"
                        maxlength="100"
                        required
                        placeholder="Ex: Jiu-Jitsu"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>

                <!-- Descrição -->
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-600">Descrição</label>
                    <textarea
                        name="mod_desc"
                        rows="3"
                        required
                        placeholder="Ex: Modalidade de arte marcial focada em luta no solo"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"></textarea>
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
                    Salvar Modalidade
                </button>
            </div>
        </div>
    </form>
</div>

<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">Lista de Modalidades</h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gray-300 text-gray-600 text-sm">
                <th class="py-3 px-4">Nome</th>
                <th class="py-3 px-4">Descrição</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($modalidades as $modalidade)
            <tr class="border-b hover:bg-gray-50 transition">
                <td class="py-3 px-4">{{ $modalidade->mod_nome }}</td>
                <td class="py-3 px-4">{{ $modalidade->mod_desc }}</td>

                <td class="py-3 px-4 flex gap-2">
                    <!-- Editar -->
                    <a href="{{ route('modalidades.edit', $modalidade->id_modalidade) }}"
                        style="background-color: #8E251F; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                        Editar
                    </a>

                    <!-- Excluir -->
                    <form action="{{ route('modalidades.destroy', $modalidade->id_modalidade) }}"
                        method="POST"
                        onsubmit="return confirm('Deseja excluir esta modalidade?');">
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
                <td colspan="3" class="text-center text-gray-500 py-6">
                    Nenhuma modalidade cadastrada
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
        form.scrollIntoView({ behavior: 'smooth' });
        document.getElementById('formCadastro').reset();
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }
</script>

@endsection
