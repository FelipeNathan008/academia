@extends('layouts.dashboard')

@section('title', 'Editar Preço')

@section('content')
<x-alert-error />

<div class="w-full px-4 pt-2 pb-6 sm:px-6 lg:px-8">
    <x-page-header title="Editar Preço" :breadcrumbs="[
        ['label' => 'Administração', 'url' => route('admin.administracao')],
        ['label' => 'Preço por Modalidade', 'url' => route('preco-aula')],
        ['label' => 'Editar Preço']
    ]" />

    <div class="mx-auto max-w-4xl">
        <form id="formEdicao" action="{{ route('preco-aula.update', Crypt::encrypt($valor->id_preco_modalidade)) }}" method="POST" onsubmit="bloquearSubmit(event, this)">
            @csrf
            @method('PUT')

            <x-form-card title="Dados do preço" description="Altere as informações do valor cadastrado.">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="modalidade_id" class="mb-2 block text-sm font-semibold text-gray-700">Modalidade</label>
                        <select id="modalidade_id" name="modalidade_id" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                            @foreach ($modalidades as $modalidade)
                            <option value="{{ $modalidade->id_modalidade }}" {{ old('modalidade_id', $valor->modalidade_id) == $modalidade->id_modalidade ? 'selected' : '' }}>{{ $modalidade->mod_nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="preco_categoria" class="mb-2 block text-sm font-semibold text-gray-700">Categoria</label>
                        <input type="text" id="preco_categoria" name="preco_categoria" value="{{ old('preco_categoria', $valor->preco_categoria) }}" maxlength="120" required placeholder="Ex.: Plano Família" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                    </div>

                    <div>
                        <label for="preco_modalidade_mask" class="mb-2 block text-sm font-semibold text-gray-700">Preço da aula</label>
                        <input type="text" id="preco_modalidade_mask" required inputmode="decimal" placeholder="R$ 0,00" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                        <input type="hidden" name="preco_modalidade" id="preco_modalidade" value="{{ old('preco_modalidade', $valor->preco_modalidade) }}">
                    </div>

                    <div>
                        <label for="preco_plano" class="mb-2 block text-sm font-semibold text-gray-700">Plano</label>
                        <select id="preco_plano" name="preco_plano" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                            @foreach (['Mensal', 'Trimestral', 'Semestral', 'Anual'] as $plano)
                            <option value="{{ $plano }}" {{ old('preco_plano', $valor->preco_plano) === $plano ? 'selected' : '' }}>{{ $plano }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <x-slot:actions>
                    <x-back-button :url="route('preco-aula')" />
                    <x-primary-button type="submit" icon="check">Salvar alterações</x-primary-button>
                </x-slot:actions>
            </x-form-card>
        </form>
    </div>
</div>

<script>
    const inputMask = document.getElementById('preco_modalidade_mask');
    const inputHidden = document.getElementById('preco_modalidade');

    function formatarPreco(digitos) {
        return digitos ? 'R$ ' + (Number(digitos) / 100).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '';
    }

    function atualizarPreco() {
        const digitos = inputMask.value.replace(/\D/g, '');
        inputMask.value = formatarPreco(digitos);
        inputHidden.value = digitos ? (Number(digitos) / 100).toFixed(2) : '';
    }

    const precoInicial = Number(inputHidden.value);
    if (Number.isFinite(precoInicial) && inputHidden.value !== '') {
        inputMask.value = formatarPreco(String(Math.round(precoInicial * 100)));
    }

    inputMask.addEventListener('input', atualizarPreco);

    function bloquearSubmit(event, form) {
        if (!form.checkValidity()) return;
        const botao = form.querySelector('button[type="submit"]');
        if (botao) {
            botao.disabled = true;
            botao.innerText = 'Salvando...';
        }
    }
</script>
@endsection
