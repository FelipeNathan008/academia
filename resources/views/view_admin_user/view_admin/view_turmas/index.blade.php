@extends('layouts.dashboard')

@section('title', 'Turmas')

@section('content')
<x-alert-error />

<div class="w-full px-4 pt-2 pb-6 sm:px-6 lg:px-8">
    <x-page-header
        title="Turmas"
        :breadcrumbs="[
        ['label' => 'Administração', 'url' => route('admin.administracao')],
        ['label' => 'Turmas']
    ]">
        <x-slot:action>
            <div class="flex flex-wrap items-center gap-3">
                <x-back-button :url="route('admin.administracao')" />
                <x-primary-button icon="plus" onclick="toggleCadastro()">Nova turma</x-primary-button>
            </div>
        </x-slot:action>
    </x-page-header>

    <div id="cadastroForm" class="mb-8 hidden">
        <form id="formCadastro" action="{{ route('turmas.store') }}" method="POST" onsubmit="bloquearSubmit(event, this)">
            @csrf
            <x-form-card title="Cadastrar turma" description="Preencha os dados para cadastrar uma nova turma.">
                <div>
                    <label for="turma_nome" class="mb-2 block text-sm font-semibold text-gray-700">Nome da turma</label>
                    <input type="text" id="turma_nome" name="turma_nome" maxlength="100" required value="{{ old('turma_nome') }}" placeholder="Ex.: Adultos, Kids" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                </div>

                <x-slot:actions>
                    <x-secondary-button onclick="fecharCadastro()">Cancelar</x-secondary-button>
                    <x-primary-button type="submit" icon="check">Salvar turma</x-primary-button>
                </x-slot:actions>
            </x-form-card>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-md">
        <div class="border-b border-gray-200 px-6 py-5">
            <form action="{{ route('turmas') }}" method="GET">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    <x-search-input name="busca" id="filtroTurma" label="Buscar turma" :value="request('busca')" placeholder="Pesquise pelo nome da turma..." />

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <x-primary-button type="submit" icon="search">Buscar</x-primary-button>
                        <a href="{{ route('turmas') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-gray-100 px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-200">Limpar</a>
                    </div>
                </div>
            </form>

            <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                <p class="text-sm text-gray-500">Consulte e gerencie as turmas cadastradas.</p>
                <x-record-count :count="$turmas->total()" singular="turma" plural="turmas" />
            </div>
        </div>

        <div class="p-4 sm:p-6">
            @if ($turmas->isEmpty())
            @if (request()->filled('busca'))
            <x-empty-state title="Nenhuma turma encontrada" message="Tente pesquisar por outro nome." :route="route('turmas')" button-text="Limpar filtros" />
            @else
            <x-empty-state title="Nenhuma turma cadastrada" message="Cadastre uma turma para começar." button-type="button" button-action="toggleCadastro()" button-text="Cadastrar turma" />
            @endif
            @else
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="w-full border-collapse text-left">
                    <thead class="bg-gray-700 text-white">
                        <tr>
                            <th class="border-r border-gray-500 px-5 py-4 text-xs font-bold uppercase tracking-wider">Nome da turma</th>
                            <th class="px-5 py-4 text-center text-xs font-bold uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach ($turmas as $turma)
                        <tr class="transition-colors hover:bg-gray-50">
                            <td class="border-r border-gray-200 px-5 py-4 text-sm font-semibold text-gray-800">{{ $turma->turma_nome }}</td>
                            <td class="px-5 py-4">
                                <x-table-actions :edit-route="route('turmas.edit', Crypt::encrypt($turma->id_turma))" />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                <x-pagination-compact :paginator="$turmas" />
            </div>
            @endif
        </div>
    </div>
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

    function toggleCadastro() {
        const cadastro = document.getElementById('cadastroForm');
        const formulario = document.getElementById('formCadastro');
        const abrir = cadastro.classList.contains('hidden');

        cadastro.classList.toggle('hidden', !abrir);

        if (abrir) {
            formulario.reset();
            cadastro.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }
</script>
@endsection