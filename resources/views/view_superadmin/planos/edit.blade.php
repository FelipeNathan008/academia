@extends('layouts.dashboard')
@section('title', 'Editar Plano')
@section('content')
<x-alert-error />
<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('superadmin.planos') }}"
            class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>
        <h2 class="text-3xl font-extrabold text-gray-800">Editar Plano</h2>
    </div>
</div>

<!-- FORMULÁRIO -->
<div class="mb-10">
    <form action="{{ route('superadmin.planos.update', Crypt::encrypt($plano->id_plano)) }}" method="POST"
        onsubmit="bloquearSubmit(event, this)">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">Editar Plano</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome do Plano -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Nome do Plano</label>
                    <input type="text" name="plano_nome" required oninput="validarNome(this)"
                        value="{{ old('plano_nome', $plano->plano_nome) }}"
                        placeholder="Ex: Faixa Branca"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>
                <!-- Valor -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Valor (R$)</label>
                    <input type="text" name="plano_valor" id="plano_valor" required
                        value="{{ old('plano_valor', number_format($plano->plano_valor, 2, ',', '.')) }}"
                        placeholder="0,00"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>
                <!-- Quantidade de Matrículas -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Quantidade de Matrículas</label>
                    <input type="number" min="1" name="plano_quantidade"
                        value="{{ old('plano_quantidade', $plano->plano_quantidade) }}"
                        placeholder="Deixe vazio para ilimitado"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>
                <!-- Tipo do Plano -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Tipo do Plano</label>
                    <select name="plano_tipo" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                        <option value="normal" {{ old('plano_tipo', $plano->plano_tipo) === 'normal' ? 'selected' : '' }}>
                            Normal
                        </option>
                        <option value="financeiro" {{ old('plano_tipo', $plano->plano_tipo) === 'financeiro' ? 'selected' : '' }}>
                            Pacote Financeiro
                        </option>
                    </select>
                </div>
                
                <!-- Recursos -->
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-600">Recursos</label>
                    <textarea name="plano_desc" rows="8" required placeholder="Um recurso por linha..."
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">{{ old('plano_desc', is_array($plano->plano_desc) ? implode("\n", $plano->plano_desc) : $plano->plano_desc) }}</textarea>
                </div>
            </div>
            <!-- BOTÕES -->
            <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                <a href="{{ route('superadmin.planos') }}"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-100 transition">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920] transition">
                    Salvar
                </button>
            </div>
        </div>
    </form>
</div>

<!-- JS -->
<script>
    function bloquearSubmit(event, form) {
        if (!form.checkValidity()) {
            return; // deixa validação normal do HTML
        }
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Salvando...';
        }
    }

    function validarNome(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
    }

    document.addEventListener("DOMContentLoaded", function() {
        const valor = document.getElementById('plano_valor');
        if (valor) {
            valor.addEventListener('input', function(e) {
                let v = e.target.value.replace(/\D/g, '');
                v = (parseInt(v || 0, 10) / 100).toFixed(2) + '';
                v = v.replace(".", ",");
                v = v.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                e.target.value = v;
            });
        }
    });
</script>
@endsection