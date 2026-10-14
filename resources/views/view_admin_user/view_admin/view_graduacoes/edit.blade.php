@extends('layouts.dashboard')

@section('title', 'Editar Graduação')

@section('content')
<x-alert-error />

<div class="w-full px-4 pt-2 pb-6 sm:px-6 lg:px-8">
    <x-page-header
        title="Editar Graduação"
        :breadcrumbs="[
            ['label' => 'Administração', 'url' => route('admin.administracao')],
            ['label' => 'Graduações', 'url' => route('graduacoes')],
            ['label' => 'Editar Graduação']
        ]" />

    <form action="{{ route('graduacoes.update', Crypt::encrypt($graduacao->id_graduacao)) }}" method="POST" onsubmit="bloquearSubmit(event, this)">
        @csrf
        @method('PUT')

        <x-form-card title="Dados da graduação" description="Atualize as informações da graduação nos campos abaixo.">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="id_modalidade" class="mb-2 block text-sm font-semibold text-gray-700">Modalidade</label>
                    <select id="id_modalidade" name="id_modalidade" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                        <option value="">Selecione uma modalidade</option>
                        @foreach($modalidades as $modalidade)
                        <option value="{{ $modalidade->id_modalidade }}" {{ old('id_modalidade', $graduacao->id_modalidade) == $modalidade->id_modalidade ? 'selected' : '' }}>{{ $modalidade->mod_nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="gradu_nome_cor" class="mb-2 block text-sm font-semibold text-gray-700">Nome / Cor</label>
                    <input type="text" id="gradu_nome_cor" name="gradu_nome_cor" maxlength="80" required value="{{ old('gradu_nome_cor', $graduacao->gradu_nome_cor) }}" placeholder="Ex.: Faixa Branca" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                </div>

                <div>
                    <label for="gradu_ordem" class="mb-2 block text-sm font-semibold text-gray-700">Ordem</label>
                    <input type="number" id="gradu_ordem" name="gradu_ordem" min="1" required value="{{ old('gradu_ordem', $graduacao->gradu_ordem) }}" placeholder="Ex.: 1" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                </div>

                <div>
                    <label for="gradu_meta" class="mb-2 block text-sm font-semibold text-gray-700">Meta (Quantidade de Aulas)</label>
                    <input type="number" id="gradu_meta" name="gradu_meta" min="0" required value="{{ old('gradu_meta', $graduacao->gradu_meta) }}" placeholder="Ex.: 30" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                </div>
            </div>

            <x-slot:actions>
                <x-back-button :url="route('graduacoes')" />
                <x-primary-button type="submit" icon="check">Salvar alterações</x-primary-button>
            </x-slot:actions>
        </x-form-card>
    </form>
</div>

<script>
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