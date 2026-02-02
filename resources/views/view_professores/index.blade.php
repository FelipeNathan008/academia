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

                <!-- TELEFONE -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Telefone</label>
                    <input type="text"
                        name="prof_telefone"
                        id="prof_telefone"
                        required
                        placeholder="(99) 99999-9999"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>

                <!-- DESCRIÇÃO -->
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-600">Observações</label>
                    <textarea name="prof_desc" id="cad_desc" required rows="3"
                        placeholder="Ex: Professor de Judô, horários flexíveis"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"></textarea>
                </div>

                <!-- FOTO -->
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-600">Foto do Professor</label>
                    <input type="file" name="prof_foto" id="cad_foto" required accept="image/*"
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

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gray-300 text-gray-600 text-sm">
                <th class="py-3 px-4">Nome</th>
                <th class="py-3 px-4">Nascimento</th>
                <th class="py-3 px-4">Idade</th>
                <th class="py-3 px-4">Telefone</th>
                <th class="py-3 px-4">Foto</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($professores as $professor)

            @php
            $nascimento = $professor->prof_nascimento
            ? \Carbon\Carbon::parse($professor->prof_nascimento)
            : null;
            $hoje = \Carbon\Carbon::today();
            @endphp

            <tr class="border-b transition professor-row"
                data-prof="{{ $professor->id_professor }}">
                <td class="py-3 px-4">{{ $professor->prof_nome }}</td>

                <td class="py-3 px-4">
                    {{ $professor->prof_nascimento
                    ? \Carbon\Carbon::parse($professor->prof_nascimento)->format('d/m/Y')
                    : '-' }}
                </td>

                <td class="py-3 px-4">
                    @if($nascimento)
                    {{ $nascimento->age }} anos

                    @if ($nascimento->isBirthday())
                    <span style="margin-left:6px; padding:2px 8px; font-size:0.75rem; font-weight:600;
                    border-radius:9999px; color:#166534; background-color:#bbf7d0;">
                        🧁 Hoje
                    </span>
                    @elseif ($nascimento->month === $hoje->month)
                    <span style="margin-left:6px; padding:2px 8px; font-size:0.75rem; font-weight:600;
                    border-radius:9999px; color:#854d0e; background-color:#fef3c7;">
                        🎉 Este mês
                    </span>
                    @endif
                    @else
                    -
                    @endif
                </td>


                <td class="py-3 px-4">
                    @if($professor->prof_telefone)
                    {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $professor->prof_telefone) }}
                    @else
                    -
                    @endif
                </td>
                <td class="py-3 px-4">
                    @if($professor->prof_foto)
                    <div class="w-12 h-12 overflow-hidden">
                        <img src="{{ asset('images/professores/' . $professor->prof_foto) }}"
                            alt="Foto" style="width:48px; height:48px; object-fit:cover;">
                    </div>
                    @else
                    -
                    @endif
                </td>
                <td class="py-3 px-4 flex gap-2">

                    <!-- Botão Graduações -->
                    <a href="{{ route('detalhes-professor.index', ['id' => $professor->id_professor]) }}"
                        style="background-color: #174ab9; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                        Graduações
                    </a>

                    <a href="{{ route('professores.edit', $professor->id_professor) }}"
                        style="background-color: #8E251F; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                        Editar
                    </a>

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


    // Validação de nome
    function validarNome(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
    }


    const tel = document.getElementById('prof_telefone');

    if (tel) {
        tel.addEventListener('input', () => {
            let v = tel.value.replace(/\D/g, '');

            if (v.length > 11) v = v.slice(0, 11);

            let f = '';
            if (v.length > 0) f = '(' + v.slice(0, 2);
            if (v.length >= 3) f += ') ' + v.slice(2, 7);
            if (v.length >= 8) f += '-' + v.slice(7, 11);

            tel.value = f;
        });
    }
</script>

@endsection