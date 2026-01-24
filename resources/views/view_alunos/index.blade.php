@extends('layouts.dashboard')

@section('title', 'Alunos')

@section('content')

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">Alunos</h2>
    </div>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">
        + Cadastrar Aluno
    </button>
</div>

<!-- FORMULÁRIO DE CADASTRO -->
<div id="cadastroForm" class="hidden mb-10">
    <form id="formCadastro" action="{{ route('alunos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 id="tituloCadastro" class="text-xl font-bold mb-6 text-gray-700">Cadastrar Aluno</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- NOME -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Nome Completo</label>
                    <input type="text" name="aluno_nome" id="cad_nome" maxlength="120" required
                        placeholder="Ex: João da Silva"
                        oninput="validarNome(this)"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>

                <!-- NASCIMENTO -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Data de Nascimento</label>
                    <input type="date" name="aluno_nascimento" id="cad_nascimento" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>

                <!-- DESCRIÇÃO -->
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-600">Observações</label>
                    <textarea name="aluno_desc" id="cad_desc" maxlength="120" required
                        placeholder="Ex: Aluno iniciante, sem restrições médicas"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                        rows="3"></textarea>
                </div>

                <!-- FOTO -->
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-600">Foto do Aluno</label>
                    <input type="file" name="aluno_foto" id="cad_foto" accept="image/*" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 bg-gray-50">
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
                    Salvar Aluno
                </button>
            </div>
        </div>
    </form>
</div>

<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">Lista de Alunos</h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gray-300 text-gray-600 text-sm">
                <th class="py-3 px-4">Nome</th>
                <th class="py-3 px-4">Nascimento</th>
                <th class="py-3 px-4">Foto</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($alunos as $aluno)
            <tr id="aluno-row-{{ $aluno->id_aluno }}"
                data-id="{{ $aluno->id_aluno }}"
                data-nome="{{ $aluno->aluno_nome }}"
                data-nascimento="{{ $aluno->aluno_nascimento }}"
                data-foto="{{ $aluno->aluno_foto }}"
                data-desc="{{ $aluno->aluno_desc }}">

                <!-- NOME -->
                <td>{{ $aluno->aluno_nome }}</td>

                <!-- NASCIMENTO FORMATO BRASILEIRO -->
                <td>
                    {{ $aluno->aluno_nascimento ? \Carbon\Carbon::parse($aluno->aluno_nascimento)->format('d/m/Y') : '-' }}
                </td>

                <!-- FOTO -->
                <td>
                    @if($aluno->aluno_foto)
                    <div class="w-12 h-12 overflow-hidden">
                        <img src="{{ asset('images/alunos/' . $aluno->aluno_foto) }}"
                            alt="Foto"
                            style="width:48px; height:48px; object-fit:cover;">
                    </div>
                    @else
                    -
                    @endif
                </td>

                <!-- AÇÕES -->
                <td>
                    <div class="flex gap-2">
                        <!-- Botão Editar -->
                        <a href="{{ route('alunos.edit', $aluno->id_aluno) }}"
                            style="background-color: #8E251F; color: white;"
                            class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                            Editar
                        </a>

                        <!-- Botão Excluir -->
                        <form action="{{ route('alunos.destroy', $aluno->id_aluno) }}" method="POST"
                            onsubmit="return confirm('Tem certeza que deseja excluir este aluno?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                style="background-color: #c02600; color: white;"
                                class="px-4 py-2 rounded-lg shadow hover:bg-[#D65A3E] transition duration-200">
                                Excluir
                            </button>
                        </form>
                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Nenhum aluno cadastrado</td>
            </tr>
            @endforelse
        </tbody>


    </table>
</div>

<!-- JS -->
<script>
    // Toggle Cadastro
    function toggleCadastro() {
        const form = document.getElementById('cadastroForm');
        form.classList.toggle('hidden');
        form.scrollIntoView({
            behavior: 'smooth'
        });
        // Resetar campos
        document.getElementById('formCadastro').reset();
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }


    // Validação de nome
    function validarNome(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
    }
</script>

@endsection