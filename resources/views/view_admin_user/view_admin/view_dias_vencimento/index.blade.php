@extends('layouts.dashboard')

@section('title', 'Dias de Vencimento')

@section('content')

<x-alert-error />

<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.administracao') }}" class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">← Voltar</a>
        <h2 class="text-3xl font-extrabold text-gray-800">Dias de Vencimento</h2>
    </div>
    <button onclick="toggleCadastro()" class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">+ Cadastrar Dia</button>
</div>

<div id="cadastroForm" class="hidden mb-10">
    <form action="{{ route('dia_vencimento.store') }}" method="POST" onsubmit="bloquearSubmit(event, this)">
        @csrf
        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">Cadastrar Dia de Vencimento</h3>

            <div class="max-w-md">
                <label class="text-sm font-medium text-gray-600">Dia de Vencimento</label>
                <input type="number" name="dia_vencimento" min="1" max="31" required value="{{ old('dia_vencimento') }}" placeholder="Ex.: 8" class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                <p class="text-xs text-gray-500 mt-2">Informe um dia entre 1 e 31.</p>
            </div>

            <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                <button type="button" onclick="fecharCadastro()" class="px-4 py-2 border rounded-lg hover:bg-gray-100 transition">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920] transition">Salvar Dia</button>
            </div>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">Lista de Dias de Vencimento</h3>

    <table class="w-full text-left">
        <thead>
            <tr class="border-b text-sm text-gray-600">
                <th class="py-3 px-4">Dia de Vencimento</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse($diasVencimento as $dia)
            <tr class="border-b hover:bg-gray-50 transition">
                <td class="py-3 px-4 font-semibold text-gray-700">Dia {{ str_pad($dia->dia_vencimento, 2, '0', STR_PAD_LEFT) }}</td>
                <td class="py-3 px-4 flex gap-2">
                    <a href="{{ route('dia_vencimento.edit', Crypt::encrypt($dia->id_dia_vencimento)) }}" class="px-4 py-2 rounded-lg shadow text-white bg-[#8E251F] hover:bg-[#732920] transition">Editar</a>

                    <form action="{{ route('dia_vencimento.destroy', Crypt::encrypt($dia->id_dia_vencimento)) }}" method="POST" onsubmit="return confirm('Deseja excluir este dia de vencimento?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 rounded-lg shadow text-white bg-red-600 hover:bg-red-700 transition">Excluir</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="text-center py-6 text-gray-500">Nenhum dia de vencimento cadastrado</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function bloquearSubmit(event, form) {
    if (!form.checkValidity()) return;
    const btn = form.querySelector('button[type="submit"]');
    if (btn) {
        btn.disabled = true;
        btn.innerText = 'Salvando...';
    }
}

function toggleCadastro() {
    document.getElementById('cadastroForm').classList.toggle('hidden');
}

function fecharCadastro() {
    document.getElementById('cadastroForm').classList.add('hidden');
}
</script>

@endsection