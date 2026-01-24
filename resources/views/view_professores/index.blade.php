@extends('layouts.dashboard')

@section('title', 'Professores')

@section('content')

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">Professores</h2>
    </div>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">
        + Cadastrar Professor
    </button>
</div>

<!-- FORMULÁRIO DE CADASTRO -->
<div id="cadastroForm" class="hidden mb-10">
    <form id="formCadastro" action="{{ route('professores.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 id="tituloCadastro" class="text-xl font-bold mb-6 text-gray-700">Cadastrar Professor</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- NOME -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Nome Completo</label>
                    <input type="text" name="prof_nome" id="cad_nome" maxlength="120" required
                        placeholder="Ex: João da Silva"
                        oninput="validarNome(this)"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>

                <!-- NASCIMENTO -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Data de Nascimento</label>
                    <input type="date" name="prof_nascimento" id="cad_nascimento" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>

                <!-- DESCRIÇÃO -->
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-600">Observações</label>
                    <textarea name="prof_desc" id="cad_desc" maxlength="120" required rows="3"
                        placeholder="Ex: Professor de Judô, horários flexíveis"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"></textarea>
                </div>

                <!-- FOTO -->
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-600">Foto do Professor</label>
                    <input type="file" name="prof_foto" id="cad_foto" accept="image/*"
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
                    Salvar Professor
                </button>
            </div>
        </div>
    </form>
</div>

<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">Lista de Professores</h3>
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

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
            @forelse ($professores as $professor)
            <tr>
                <td>{{ $professor->prof_nome }}</td>
                <td>{{ $professor->prof_nascimento ? \Carbon\Carbon::parse($professor->prof_nascimento)->format('d/m/Y') : '-' }}</td>
                <td>
                    @if($professor->prof_foto)
                    <div class="w-12 h-12 overflow-hidden">
                        <img src="{{ asset('images/professores/' . $professor->prof_foto) }}"
                            alt="Foto" style="width:48px; height:48px; object-fit:cover;">
                    </div>
                    @else
                    -
                    @endif
                </td>
                <td class="flex gap-2">
                    <a href="{{ route('professores.edit', $professor->id_professor) }}"
                        style="background-color: #8E251F; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                        Editar
                    </a>

                    <!-- Botão Graduações -->
                    <button type="button"
                        data-id="{{ $professor->id_professor }}"
                        onclick="toggleGraduacoes(this)"
                        style="background-color: #2563eb; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200">
                        Graduações
                    </button>

                    <form action="{{ route('professores.destroy', $professor->id_professor) }}" method="POST"
                        onsubmit="return confirm('Deseja excluir este professor?');">
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

            <!-- Linha oculta de Graduações -->
            <tr id="graduacoes-{{ $professor->id_professor }}" class="hidden bg-gray-100">
                <td colspan="4" class="py-2 px-4">
                    <h4 class="font-bold mb-2">Cadastrar Graduações do Professor</h4>

                    <form action="{{ route('detalhes.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="professor_id_professor" value="{{ $professor->id_professor }}">

                        <!-- Selecionar Graduação -->
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-600">Graduação</label>
                            <select name="det_gradu_nome_cor"
                                class="w-full border rounded-lg px-3 py-2"
                                onchange="preencherGrau(this)"
                                required>
                                <option value="">Selecione</option>

                                @foreach($graduacoes as $graduacao)
                                <option value="{{ $graduacao->gradu_nome_cor }}"
                                    data-grau="{{ $graduacao->gradu_grau }}">
                                    {{ $graduacao->gradu_nome_cor }} | {{ $graduacao->gradu_grau }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Grau preenchido automaticamente -->
                        <!-- Grau -->
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-600">Grau</label>
                            <input type="number"
                                name="det_grau"
                                class="w-full border rounded-lg px-3 py-2 bg-gray-100 grau-input"
                                required
                                readonly>
                        </div>

                        <!-- Modalidade -->
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-600">Modalidade</label>
                            <select name="det_modalidade"
                                class="w-full border rounded-lg px-3 py-2"
                                required>
                                <option value="">Selecione</option>
                                <option value="Jiujitsu">Jiujitsu</option>
                            </select>
                        </div>


                        <button type="submit" class="px-4 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">
                            Salvar
                        </button>
                    </form>

                    <!-- Listagem de detalhes já cadastrados -->
                    <div class="mt-6">
                        <h4 class="font-bold mb-3 text-gray-700">Graduações Cadastradas</h4>

                        @php
                        $lista = $detalhes->where('professor_id_professor', $professor->id_professor);
                        @endphp

                        @if($lista->count())
                        <div class="space-y-3">
                            @foreach($lista as $det)
                            <div class="flex justify-between items-center bg-white p-4 rounded-xl shadow-sm border">
                                <div>
                                    <p class="font-semibold text-gray-800">
                                        {{ $det->det_gradu_nome_cor }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Grau: {{ $det->det_grau }} • Modalidade: {{ $det->det_modalidade }}
                                    </p>
                                </div>

                                <!-- Botão Excluir -->
                                <form action="{{ route('detalhes.destroy', $det->id_det_professor) }}"
                                    method="POST"
                                    onsubmit="return confirm('Deseja remover esta graduação?');">
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                        Excluir
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-sm text-gray-500">Nenhuma graduação cadastrada.</p>
                        @endif
                    </div>

                </td>
            </tr>

            @empty
            <tr>
                <td colspan="4" class="text-center text-gray-500 py-6">Nenhum professor cadastrado</td>
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
        document.getElementById('formCadastro').reset();
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }

    function toggleGraduacoes(button) {
        const id = button.getAttribute('data-id');
        const row = document.getElementById(`graduacoes-${id}`);
        row.classList.toggle('hidden');
    }

    // Validação de nome
    function validarNome(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
    }

    function preencherGrau(select) {
        const grau = select.options[select.selectedIndex].dataset.grau || '';
        const inputGrau = select.closest('td').querySelector('.grau-input');
        inputGrau.value = grau;
    }
</script>

@endsection