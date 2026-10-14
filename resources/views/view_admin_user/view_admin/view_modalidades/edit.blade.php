@extends('layouts.dashboard')

@section('title', 'Editar Modalidade')

@section('content')
<x-alert-error />

<div class="w-full px-4 pt-2 pb-6 sm:px-6 lg:px-8">
    <x-page-header
        title="Editar Modalidade"
        :breadcrumbs="[
        ['label' => 'Administração', 'url' => route('admin.administracao')],
        ['label' => 'Modalidades', 'url' => route('modalidades')],
        ['label' => 'Editar Modalidade']
    ]" />

    <form action="{{ route('modalidades.update', Crypt::encrypt($modalidade->id_modalidade)) }}" method="POST" onsubmit="bloquearSubmit(event, this)">
        @csrf
        @method('PUT')

        <x-form-card title="Dados da modalidade" description="Atualize as informações nos campos abaixo.">
            <div>
                <label for="mod_nome" class="mb-2 block text-sm font-semibold text-gray-700">Nome da modalidade</label>
                <input type="text" id="mod_nome" name="mod_nome" maxlength="100" required value="{{ old('mod_nome', $modalidade->mod_nome) }}" placeholder="Ex.: Jiu-Jitsu" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
            </div>

            <div>
                <label for="mod_desc" class="mb-2 block text-sm font-semibold text-gray-700">Descrição</label>
                <textarea id="mod_desc" name="mod_desc" rows="4" maxlength="255" required placeholder="Descreva brevemente a modalidade..." class="w-full resize-y rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">{{ old('mod_desc', $modalidade->mod_desc) }}</textarea>
            </div>

            <x-slot:actions>
                <x-back-button :url="route('modalidades')" />

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