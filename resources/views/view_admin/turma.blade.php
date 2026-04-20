@extends('layouts.dashboard')

@section('title', 'Turmas')

@section('content')

<x-alert-error />

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <h2 class="text-3xl font-extrabold text-gray-800">Turmas</h2>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] transition">
        + Cadastrar Turma
    </button>
</div>

<!-- FORM -->
<div id="cadastroForm" class="hidden mb-10">
    <form action="{{ route('turmas.store') }}" method="POST" onsubmit="bloquearSubmit(event, this)">
        @csrf

        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">Cadastrar Turma</h3>

            <div>
                <label class="text-sm text-gray-600">Nome da Turma</label>
                <input type="text"
                    name="turma_nome"
                    placeholder="Adultos, Kids"
                    maxlength="100"
                    required
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>

            <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                <button type="button" onclick="fecharCadastro()"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-100">
                    Cancelar
                </button>

                <button type="submit"
                    class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">
                    Salvar
                </button>
            </div>
        </div>
    </form>
</div>

<!-- LISTA -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">Lista de Turmas</h3>

    <table class="w-full">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Nome</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($turmas as $turma)
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3 px-4">{{ $turma->turma_nome }}</td>

                <td class="py-3 px-4 flex gap-2">

                    <a href="{{ route('turmas.edit', Crypt::encrypt($turma->id_turma)) }}"
                        class="px-4 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">
                        Editar
                    </a>

                    <form action="{{ route('turmas.destroy', Crypt::encrypt($turma->id_turma)) }}"
                        method="POST"
                        onsubmit="return confirm('Excluir turma?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Excluir
                        </button>
                    </form>

                </td>
            </tr>

            @empty
            <tr>
                <td colspan="2" class="text-center py-6 text-gray-500">
                    Nenhuma turma cadastrada
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- JS -->
<script>
function bloquearSubmit(e, form) {
    if (!form.checkValidity()) return;

    const btn = form.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerText = 'Salvando...';
}

function toggleCadastro() {
    const form = document.getElementById('cadastroForm');
    form.classList.toggle('hidden');
    form.scrollIntoView({ behavior: 'smooth' });
}

function fecharCadastro() {
    document.getElementById('cadastroForm').classList.add('hidden');
}
</script>

@endsection