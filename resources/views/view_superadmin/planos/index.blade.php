@extends('layouts.dashboard')
@section('title', 'Planos')
@section('content')
<x-alert-error />
<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('dashboard-superadmin') }}"
            class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>
        <h2 class="text-3xl font-extrabold text-gray-800">Planos</h2>
    </div>

    <div class="flex gap-3">

        <a href="{{ route('superadmin.planos.visualizar') }}"
            style="background-color: #174AB9; color: #fff; padding: 12px 24px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,.15); text-decoration: none; transition: .2s;"
            onmouseover="this.style.backgroundColor='#123C96'; this.style.boxShadow='0 6px 12px rgba(0,0,0,.2)'"
            onmouseout="this.style.backgroundColor='#174AB9'; this.style.boxShadow='0 4px 6px rgba(0,0,0,.15)'">
            Visualizar
        </a>

        <button onclick="toggleCadastro()"
            class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] transition-all">
            + Novo Plano
        </button>
    </div>

</div>

<!-- FORMULÁRIO -->
<div id="cadastroForm" class="hidden mb-10">
    <form action="{{ route('superadmin.planos.store') }}" method="POST" onsubmit="bloquearSubmit(event, this)">
        @csrf
        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">Cadastrar Plano</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome do Plano -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Nome do Plano</label>
                    <input type="text" name="plano_nome" required oninput="validarNome(this)"
                        placeholder="Ex: Faixa Branca"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>
                <!-- Valor -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Valor (R$)</label>
                    <input type="text" name="plano_valor" id="plano_valor" required placeholder="0,00"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>

                <!-- Quantidade de Matrículas -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Quantidade de Matrículas</label>
                    <input type="number" min="1" name="plano_quantidade" placeholder="Deixe vazio para ilimitado"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                </div>
                <!-- Tipo do Plano -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Tipo do Plano</label>
                    <select name="plano_tipo" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                        <option value="normal">Normal</option>
                        <option value="financeiro">Pacote Financeiro</option>
                    </select>
                </div>

                <!-- Recursos -->
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-600">Recursos</label>
                    <textarea name="plano_desc" rows="8" required placeholder="Um recurso por linha..."
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"></textarea>
                </div>
            </div>
            <!-- BOTÕES -->
            <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                <button type="button" onclick="fecharCadastro()"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-100 transition">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920] transition">
                    Salvar
                </button>
            </div>
        </div>
    </form>
</div>

<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">Lista de Planos</h3>
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gray-300 text-gray-600 text-sm">
                <th class="py-3 px-4">Plano</th>
                <th class="py-3 px-4">Tipo</th>
                <th class="py-3 px-4">Valor</th>
                <th class="py-3 px-4">Matrículas</th>
                <th class="py-3 px-4">Recursos</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($planos as $plano)
            <tr class="border-b hover:bg-gray-50 transition">
                <td class="py-3 px-4 font-semibold">
                    {{ $plano->plano_nome }}
                </td>
                <!-- Tipo -->
                <td class="py-3 px-4">
                    @if($plano->plano_tipo === 'financeiro')
                        <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
                            Pacote Financeiro
                        </span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-600 rounded-full">
                            Normal
                        </span>
                    @endif
                </td>
               
                <!-- Valor -->
                <td class="py-3 px-4">
                    R$ {{ number_format($plano->plano_valor, 2, ',', '.') }}
                </td>
                <!-- Matrículas -->
                <td class="py-3 px-4">
                    {{ $plano->plano_quantidade ?? 'Ilimitadas' }}
                </td>
                <!-- Recursos -->
                <td class="py-3 px-4">
                    <ul class="list-disc ml-5">
                        @foreach($plano->plano_desc as $item)
                        <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </td>
                <!-- Ações -->
                <td class="py-3 px-4 flex gap-2">
                    <!-- Editar -->
                    <a href="{{ route('superadmin.planos.edit', Crypt::encrypt($plano->id_plano)) }}"
                        class="px-4 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920] transition">
                        Editar
                    </a>
                    <!-- Excluir -->
                    <form action="{{ route('superadmin.planos.destroy', Crypt::encrypt($plano->id_plano)) }}"
                        method="POST"
                        onsubmit="return confirm('Excluir plano?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            Excluir
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-gray-500 py-6">
                    Nenhum plano cadastrado
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
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }

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