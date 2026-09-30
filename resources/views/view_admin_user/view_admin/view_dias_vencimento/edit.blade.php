@extends('layouts.dashboard')

@section('title', 'Editar Dia de Vencimento')

@section('content')

<x-alert-error />

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Editar Dia de Vencimento</h2>

    <form action="{{ route('dia_vencimento.update', Crypt::encrypt($diaVencimento->id_dia_vencimento)) }}" method="POST" onsubmit="bloquearSubmit(event, this)">
        @csrf
        @method('PUT')

        <div>
            <label class="text-sm font-medium text-gray-600">Dia de Vencimento</label>
            <input type="number" name="dia_vencimento" min="1" max="31" required value="{{ old('dia_vencimento', $diaVencimento->dia_vencimento) }}" class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
            <p class="text-xs text-gray-500 mt-2">Informe um dia entre 1 e 31.</p>
        </div>

        <div class="flex justify-end gap-4 mt-8 border-t pt-6">
            <a href="{{ route('dia_vencimento') }}" class="px-5 py-2 border rounded-lg hover:bg-gray-100 transition">Voltar</a>
            <button type="submit" class="px-6 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920] shadow-md transition">Salvar Alterações</button>
        </div>
    </form>
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
</script>

@endsection