@extends('layouts.dashboard')

@section('title', 'Aplicar Molde de Graduação')

@section('content')

<x-alert-error />

<div class="flex items-center gap-4 mb-8">
    <a href="{{ route('graduacoes.molde.index') }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
        ← Voltar
    </a>
    <h2 class="text-3xl font-extrabold text-gray-800">
        Aplicar molde: {{ $modalidadeMolde->mod_nome }}
    </h2>
</div>

<form id="formMolde" action="{{ route('graduacoes.molde.aplicar') }}" method="POST">
    @csrf

    <!-- MODALIDADE DESTINO -->
    <div class="bg-white rounded-2xl shadow-md p-8 mb-6">
        <label class="text-sm font-medium text-gray-600">
            Aplicar para qual modalidade cadastrada?
            <span class="text-red-600">*</span>
        </label>

        <select name="id_modalidade"
            required
            class="w-full md:w-1/2 border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
            <option value="" selected disabled>Selecione uma modalidade</option>

            @foreach ($modalidades as $modalidade)
            <option value="{{ $modalidade->id_modalidade }}">
                {{ $modalidade->mod_nome }}
            </option>
            @endforeach
        </select>
        <p class="text-xs text-gray-400 mt-2">
            Se a modalidade que você precisa ainda não existe, cadastre-a primeiro na tela de Modalidades.
        </p>
    </div>

    @if ($modalidadeMolde->graduacoes->isEmpty())

    <div class="bg-white rounded-2xl shadow-md p-10 text-center text-gray-500">
        Este molde ainda não tem graduações cadastradas.
    </div>

    @else

    <!-- TOOLBAR: BUSCA + SELECIONAR TUDO -->
    <div class="bg-white rounded-2xl shadow-md p-4 mb-2 flex flex-col md:flex-row gap-4 md:items-center md:justify-between sticky top-0 z-20">
        <div class="flex items-center gap-3 flex-1">
            <input type="text" id="buscaMolde" placeholder="🔍 Buscar por nome / cor..."
                class="border rounded-lg px-4 py-2 w-full md:w-80 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
            <span id="contadorMolde" class="text-sm text-gray-500 whitespace-nowrap">
                {{ $modalidadeMolde->graduacoes->count() }} selecionadas
            </span>
        </div>

        <div class="flex gap-2">
            <button type="button" id="btnSelecionarTodos"
                class="px-4 py-2 text-sm border rounded-lg hover:bg-gray-100 transition">
                Selecionar todos
            </button>
            <button type="button" id="btnLimparSelecao"
                class="px-4 py-2 text-sm border rounded-lg hover:bg-gray-100 transition">
                Limpar seleção
            </button>
        </div>
    </div>

    <!-- TABELA -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden mb-24">
        <div class="overflow-x-auto max-h-[65vh] overflow-y-auto">
            <table class="w-full text-left border-collapse">
                <thead class="sticky top-0 bg-gray-50 z-10">
                    <tr class="border-b border-gray-300 text-gray-600 text-xs uppercase tracking-wide">
                        <th class="py-3 px-4 w-10"></th>
                        <th class="py-3 px-4">Nome / Cor</th>
                        <th class="py-3 px-4 w-28">Grau</th>
                        <th class="py-3 px-4 w-28">Ordem</th>
                        <th class="py-3 px-4 w-32">Meta (aulas)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($modalidadeMolde->graduacoes as $i => $g)
                    <tr class="border-b hover:bg-gray-50 transition linha-molde"
                        data-nome="{{ strtolower($g->gradu_nome_cor) }}">

                        <td class="py-2 px-4">
                            <input type="checkbox" name="graduacoes[{{ $i }}][ativo]"
                                value="1" checked
                                class="w-4 h-4 accent-[#8E251F] checkbox-molde">
                        </td>

                        <td class="py-2 px-4">
                            <div class="flex items-center gap-2">
                                <span class="bolinha-faixa"
                                    data-faixa="{{ strtolower($g->gradu_nome_cor) }}"
                                    style="display:inline-block;width:16px;height:16px;border:2px solid #000;
                                    border-radius:50%;flex-shrink:0;background-color:transparent;">
                                </span>

                                <span class="font-medium text-gray-800">
                                    {{ $g->gradu_nome_cor }}
                                </span>

                                <input type="hidden"
                                    name="graduacoes[{{ $i }}][gradu_nome_cor]"
                                    value="{{ $g->gradu_nome_cor }}">
                            </div>
                        </td>

                        <td class="py-2 px-4">
                            <input type="number" name="graduacoes[{{ $i }}][gradu_grau]"
                                value="{{ $g->gradu_grau }}" required
                                class="w-full border rounded-lg px-3 py-1.5 text-sm">
                        </td>

                        <td class="py-2 px-4">
                            <input type="number" name="graduacoes[{{ $i }}][gradu_ordem]"
                                value="{{ $g->gradu_ordem }}" required
                                class="w-full border rounded-lg px-3 py-1.5 text-sm">
                        </td>

                        <td class="py-2 px-4">
                            <input type="number" name="graduacoes[{{ $i }}][gradu_meta]"
                                value="{{ $g->gradu_meta }}" required
                                class="w-full border rounded-lg px-3 py-1.5 text-sm">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex justify-end gap-4 mt-8">
        <a href="{{ route('graduacoes.molde.index') }}"
            class="px-5 py-2 border rounded-lg hover:bg-gray-100 transition">
            Cancelar
        </a>

        <button type="submit"
            class="px-5 py-2 bg-[#8E251F] text-white rounded-lg shadow hover:bg-[#732920] transition">
            Aplicar graduações selecionadas
        </button>
    </div>

    @endif
</form>

<script src="{{ asset('js/faixas.js') }}"></script>
<script>
    aplicarCoresFaixas();

    const busca = document.getElementById('buscaMolde');
    const linhas = document.querySelectorAll('.linha-molde');
    const checkboxes = document.querySelectorAll('.checkbox-molde');
    const contador = document.getElementById('contadorMolde');
    const btnTodos = document.getElementById('btnSelecionarTodos');
    const btnLimpar = document.getElementById('btnLimparSelecao');

    function atualizarContador() {
        const marcados = document.querySelectorAll('.checkbox-molde:checked').length;
        contador.innerText = `${marcados} selecionadas`;
    }

    busca?.addEventListener('input', function() {
        const termo = this.value.trim().toLowerCase();

        linhas.forEach(linha => {
            const nome = linha.dataset.nome;
            linha.style.display = nome.includes(termo) ? '' : 'none';
        });
    });

    checkboxes.forEach(cb => cb.addEventListener('change', atualizarContador));

    btnTodos?.addEventListener('click', function() {
        linhas.forEach(linha => {
            if (linha.style.display !== 'none') {
                linha.querySelector('.checkbox-molde').checked = true;
            }
        });
        atualizarContador();
    });

    btnLimpar?.addEventListener('click', function() {
        checkboxes.forEach(cb => cb.checked = false);
        atualizarContador();
    });

    atualizarContador();
</script>

@endsection