@extends('layouts.dashboard')

@section('title', 'Aplicar Molde de Graduação')

@section('content')
<x-alert-error />

<div class="w-full px-4 pt-2 pb-6 sm:px-6 lg:px-8">
    <x-page-header
        title="Aplicar Molde de Graduação"
        :breadcrumbs="[
            ['label' => 'Administração', 'url' => route('admin.administracao')],
            ['label' => 'Graduações', 'url' => route('graduacoes')],
            ['label' => 'Moldes de Graduação', 'url' => route('graduacoes.molde.index')],
            ['label' => 'Aplicar Molde']
        ]" />

    <div class="mb-6 flex items-center justify-between gap-3">
        <p class="text-sm text-gray-500">Molde selecionado: <span class="font-semibold text-gray-800">{{ $modalidadeMolde->mod_nome }}</span></p>
        <x-back-button :url="route('graduacoes.molde.index')" />
    </div>

    <form id="formMolde" action="{{ route('graduacoes.molde.aplicar') }}" method="POST" onsubmit="bloquearSubmit(event, this)">
        @csrf

        <x-form-card title="Modalidade de destino" description="Escolha a modalidade que receberá as graduações selecionadas.">
            <div class="max-w-xl">
                <label for="id_modalidade" class="mb-2 block text-sm font-semibold text-gray-700">Aplicar para qual modalidade? <span class="text-red-600">*</span></label>
                <select id="id_modalidade" name="id_modalidade" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                    <option value="" selected disabled>Selecione uma modalidade</option>
                    @foreach($modalidades as $modalidade)
                    <option value="{{ $modalidade->id_modalidade }}" {{ old('id_modalidade') == $modalidade->id_modalidade ? 'selected' : '' }}>{{ $modalidade->mod_nome }}</option>
                    @endforeach
                </select>
                <p class="mt-2 text-xs text-gray-500">Caso a modalidade ainda não esteja cadastrada, crie-a primeiro na tela de Modalidades.</p>
            </div>
        </x-form-card>

        @if($modalidadeMolde->graduacoes->isEmpty())
            <div class="mt-6">
                <x-empty-state
                    title="Nenhuma graduação no molde"
                    description="Este molde ainda não possui graduações cadastradas." />
            </div>
        @else
            <div class="sticky top-0 z-20 mt-6 mb-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex flex-1 flex-col gap-2 sm:flex-row sm:items-center">
                        <div class="relative w-full sm:max-w-sm">
                            <input type="text" id="buscaMolde" placeholder="Buscar por nome ou cor..." class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                        </div>
                        <span id="contadorMolde" class="text-sm text-gray-500">{{ $modalidadeMolde->graduacoes->count() }} selecionadas</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" id="btnSelecionarTodos" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-100">Selecionar todos</button>
                        <button type="button" id="btnLimparSelecao" class="rounded-lg border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-200">Limpar seleção</button>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="max-h-[65vh] overflow-auto">
                    <table class="w-full border-collapse text-left">
                        <thead class="sticky top-0 z-10 bg-gray-800 text-xs uppercase tracking-wide text-white">
                            <tr>
                                <th class="w-12 px-4 py-3"></th>
                                <th class="px-4 py-3">Nome / Cor</th>
                                <th class="w-28 px-4 py-3">Ordem</th>
                                <th class="w-36 px-4 py-3">Meta (aulas)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($modalidadeMolde->graduacoes as $i => $g)
                            <tr class="linha-molde border-b border-gray-100 transition last:border-0 hover:bg-gray-50" data-nome="{{ strtolower($g->gradu_nome_cor) }}">
                                <td class="px-4 py-3">
                                    <input type="checkbox" name="graduacoes[{{ $i }}][ativo]" value="1" checked class="checkbox-molde h-4 w-4 rounded border-gray-300 accent-[#8E251F] focus:ring-[#8E251F]">
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="bolinha-faixa inline-block h-4 w-4 shrink-0 rounded-full border-2 border-black" data-faixa="{{ strtolower($g->gradu_nome_cor) }}" style="background-color:transparent;"></span>
                                        <span class="text-sm font-medium text-gray-800">{{ $g->gradu_nome_cor }}</span>
                                        <input type="hidden" name="graduacoes[{{ $i }}][gradu_nome_cor]" value="{{ $g->gradu_nome_cor }}">
                                    </div>
                                    <input type="hidden" name="graduacoes[{{ $i }}][gradu_grau]" value="0">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="graduacoes[{{ $i }}][gradu_ordem]" value="{{ old("graduacoes.$i.gradu_ordem", $g->gradu_ordem) }}" min="1" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="graduacoes[{{ $i }}][gradu_meta]" value="{{ old("graduacoes.$i.gradu_meta", $g->gradu_meta) }}" min="0" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5 flex flex-col-reverse justify-end gap-3 sm:flex-row">
                <a href="{{ route('graduacoes.molde.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-200">Cancelar</a>
                <x-primary-button type="submit" icon="check">Aplicar graduações selecionadas</x-primary-button>
            </div>
        @endif
    </form>
</div>

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
        if (contador) contador.innerText = `${document.querySelectorAll('.checkbox-molde:checked').length} selecionadas`;
    }

    busca?.addEventListener('input', function() {
        const termo = this.value.trim().toLowerCase();
        linhas.forEach(linha => linha.style.display = linha.dataset.nome.includes(termo) ? '' : 'none');
    });

    checkboxes.forEach(cb => cb.addEventListener('change', atualizarContador));

    btnTodos?.addEventListener('click', function() {
        linhas.forEach(linha => {
            if (linha.style.display !== 'none') linha.querySelector('.checkbox-molde').checked = true;
        });
        atualizarContador();
    });

    btnLimpar?.addEventListener('click', function() {
        checkboxes.forEach(cb => cb.checked = false);
        atualizarContador();
    });

    function bloquearSubmit(event, form) {
        if (!form.checkValidity()) return;
        const selecionadas = form.querySelectorAll('.checkbox-molde:checked').length;
        if (selecionadas === 0) {
            event.preventDefault();
            alert('Selecione pelo menos uma graduação para aplicar o molde.');
            return;
        }
        const botao = form.querySelector('button[type="submit"]');
        if (botao) {
            botao.disabled = true;
            botao.innerText = 'Aplicando...';
        }
    }

    atualizarContador();
</script>
@endsection