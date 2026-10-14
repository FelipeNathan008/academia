@extends('layouts.dashboard')

@section('title', 'Editar Turma')

@section('content')
<x-alert-error />

<div class="w-full px-4 pt-2 pb-6 sm:px-6 lg:px-8">
    <x-page-header
        title="Editar Turma"
        :breadcrumbs="[
        ['label' => 'Administração', 'url' => route('admin.administracao')],
        ['label' => 'Turmas', 'url' => route('turmas')],
        ['label' => 'Editar Turma']
    ]" />
    <form action="{{ route('turmas.update', Crypt::encrypt($turma->id_turma)) }}" method="POST" onsubmit="bloquearSubmit(event, this)">
        @csrf
        @method('PUT')

        <x-form-card title="Dados da Turma" description="Atualize o nome da turma nos campos abaixo.">
            <div>
                <label for="turma_nome" class="mb-2 block text-sm font-semibold text-gray-700">Nome da Turma</label>
                <input type="text" id="turma_nome" name="turma_nome" maxlength="100" required value="{{ old('turma_nome', $turma->turma_nome) }}" placeholder="Ex.: Adultos, Kids" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
            </div>

            <x-slot:actions>
                <x-back-button :url="route('turmas')" />
                <x-primary-button type="submit" icon="check">Salvar Alterações</x-primary-button>
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