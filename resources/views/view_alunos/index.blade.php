@extends('layouts.dashboard')

@section('title', 'Alunos')

@section('content')

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">Alunos</h2>
    </div>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] transition">
        + Cadastrar Aluno
    </button>
</div>

<!-- FORMULÁRIO DE CADASTRO -->
<div id="cadastroForm" class="hidden mb-10">
    <form id="formCadastro" action="{{ route('alunos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6">Cadastrar Aluno</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label>Nome Completo</label>
                    <input type="text" name="aluno_nome" required
                        placeholder="Ex: Lucas Andrade"
                        oninput="validarNome(this)"
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                </div>

                <div>
                    <label>Data de Nascimento</label>
                    <input type="date" name="aluno_nascimento" required
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                </div>

                <div class="md:col-span-2">
                    <label>Observações</label>
                    <textarea name="aluno_desc" rows="4" required
                        placeholder="Ex: Aluno iniciante visando melhora na saúde"
                        class="w-full border rounded-lg px-4 py-2 mt-1"></textarea>
                </div>

                <div class="md:col-span-2">
                    <label>Foto do Aluno</label>
                    <input type="file" name="aluno_foto" required
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                </div>
            </div>

            <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                <button type="button" onclick="fecharCadastro()" class="px-4 py-2 border rounded-lg">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-[#8E251F] text-white rounded-lg">
                    Salvar
                </button>
            </div>
        </div>
    </form>
</div>

<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6">Lista de Alunos</h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Nome</th>
                <th class="py-3 px-4">Nascimento</th>
                <th class="py-3 px-4">Idade</th>
                <th class="py-3 px-4">Foto</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($alunos as $aluno)
            @php
            $nascimento = \Carbon\Carbon::parse($aluno->aluno_nascimento);
            $hoje = \Carbon\Carbon::today();
            @endphp

            <tr class="border-b hover:bg-gray-50{{ $nascimento->isBirthday() ? 'bg-green-100' : '' }}
            {{ !$nascimento->isBirthday() && $nascimento->month === $hoje->month ? 'bg-yellow-50' : '' }}">
                <td class="py-3 px-4">{{ $aluno->aluno_nome }}</td>
                <td class="py-3 px-4">
                    {{ \Carbon\Carbon::parse($aluno->aluno_nascimento)->format('d/m/Y') }}
                </td>
                <td class="py-3 px-4">
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
                </td>

                <td class="py-3 px-4">
                    @if($aluno->aluno_foto)
                    <img src="{{ asset('images/alunos/'.$aluno->aluno_foto) }}"
                        style="width:48px;height:48px;object-fit:cover">
                    @else -
                    @endif
                </td>
                <td class="py-3 px-4 flex gap-2">


                    <a href="{{ route('responsaveis.index', $aluno->id_aluno) }}"
                        style="background-color: #3b64bd; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                        Responsáveis
                    </a>

                    <a href="{{ route('detalhes-aluno.index', ['id' => $aluno->id_aluno]) }}"
                        style="background-color: #174ab9; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                    Graduações
                    </a>

                    <a href="{{ route('alunos.edit', $aluno->id_aluno) }}"
                        class="px-4 py-2 bg-[#8E251F] text-white rounded-lg">
                        Editar
                    </a>
                    <!-- Botão Excluir -->
                    <form action="{{ route('alunos.destroy', $aluno->id_aluno) }}"
                        method="POST"
                        onsubmit="return confirm('Deseja remover esta graduação?');">
                        @csrf
                        @method('DELETE')
                        <button class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            Excluir
                        </button>
                    </form>
                </td>
            </tr>


            @empty
            <tr>
                <td colspan="5" class="text-center py-6 text-gray-500">
                    Nenhum aluno cadastrado
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    function toggleCadastro() {
        document.getElementById('cadastroForm').classList.toggle('hidden');
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }

    function toggleGraduacoes(button) {
        const id = button.getAttribute('data-id');
        document.getElementById('graduacoes-' + id).classList.toggle('hidden');
    }

    function validarNome(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
    }
</script>

@endsection