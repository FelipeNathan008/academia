@extends('layouts.dashboard')

@section('title', 'Graduações')

@section('content')
<x-alert-error />

<div class="w-full px-4 pt-2 pb-6 sm:px-6 lg:px-8">
    <x-page-header
        title="Graduações"
        :breadcrumbs="[
            ['label' => 'Administração', 'url' => route('admin.administracao')],
            ['label' => 'Graduações']
        ]">
        <x-slot:action>
            <div class="flex flex-wrap items-center gap-3">
                <x-back-button :url="route('admin.administracao')" />
                <a href="{{ route('graduacoes.molde.index') }}" class="inline-flex items-center justify-center rounded-lg border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-semibold text-blue-700 transition hover:bg-blue-100">
                    Copiar molde
                </a>
                <x-primary-button icon="plus" onclick="toggleCadastro()">Nova graduação</x-primary-button>
            </div>
        </x-slot:action>
    </x-page-header>

    <div id="cadastroForm" class="mb-8 hidden">
        <form id="formCadastro" action="{{ route('graduacoes.store') }}" method="POST" onsubmit="bloquearSubmit(event, this)">
            @csrf
            <x-form-card title="Nova graduação" description="Preencha os dados da graduação nos campos abaixo.">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="id_modalidade" class="mb-2 block text-sm font-semibold text-gray-700">Modalidade</label>
                        <select id="id_modalidade" name="id_modalidade" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                            <option value="">Selecione uma modalidade</option>
                            @foreach ($modalidades as $modalidade)
                            <option value="{{ $modalidade->id_modalidade }}" {{ (string) old('id_modalidade') === (string) $modalidade->id_modalidade ? 'selected' : '' }}>{{ $modalidade->mod_nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="gradu_nome_cor" class="mb-2 block text-sm font-semibold text-gray-700">Nome / Cor</label>
                        <input type="text" id="gradu_nome_cor" name="gradu_nome_cor" maxlength="80" required value="{{ old('gradu_nome_cor') }}" placeholder="Ex.: Faixa Branca" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 placeholder:text-gray-400 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                    </div>

                    <div>
                        <label for="gradu_ordem" class="mb-2 block text-sm font-semibold text-gray-700">Ordem</label>
                        <input type="number" id="gradu_ordem" name="gradu_ordem" min="1" required value="{{ old('gradu_ordem') }}" placeholder="Ex.: 1" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                    </div>

                    <div>
                        <label for="gradu_meta" class="mb-2 block text-sm font-semibold text-gray-700">Meta (quantidade de aulas)</label>
                        <input type="number" id="gradu_meta" name="gradu_meta" min="0" required value="{{ old('gradu_meta') }}" placeholder="Ex.: 30" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                    </div>
                </div>

                <x-slot:actions>
                    <x-secondary-button onclick="fecharCadastro()">Cancelar</x-secondary-button>
                    <x-primary-button type="submit" icon="check">Salvar graduação</x-primary-button>
                </x-slot:actions>
            </x-form-card>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-md">
        <div class="border-b border-gray-200 px-6 py-5">
            <form action="{{ route('graduacoes') }}" method="GET">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="filtroModalidade" class="mb-2 block text-sm font-semibold text-gray-700">Modalidade</label>
                        <select id="filtroModalidade" name="modalidade" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                            <option value="">Todas as modalidades</option>
                            @foreach ($modalidades as $modalidade)
                            <option value="{{ $modalidade->id_modalidade }}" {{ (string) request('modalidade') === (string) $modalidade->id_modalidade ? 'selected' : '' }}>{{ $modalidade->mod_nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="filtroNome" class="mb-2 block text-sm font-semibold text-gray-700">Nome / Cor</label>
                        <select id="filtroNome" name="nome" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                            <option value="">Todas as graduações</option>
                            @foreach ($nomesGraduacao as $nome)
                            <option value="{{ $nome }}" {{ request('nome') === $nome ? 'selected' : '' }}>{{ $nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-3">
                    <x-primary-button type="submit" icon="search">Buscar</x-primary-button>
                    <a href="{{ route('graduacoes') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-200">Limpar</a>
                </div>
            </form>

            <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                <p class="text-sm text-gray-500">Consulte e gerencie as graduações cadastradas.</p>
                <x-record-count :count="$totalGraduacoes" singular="graduação" plural="graduações" />
            </div>
        </div>

        <div class="p-4 sm:p-6">
            @if ($graduacoes->isEmpty())
                @if (request()->filled('modalidade') || request()->filled('nome'))
                    <x-empty-state title="Nenhuma graduação encontrada" message="Tente alterar os filtros utilizados." :route="route('graduacoes')" button-text="Limpar filtros" />
                @else
                    <x-empty-state title="Nenhuma graduação cadastrada" message="Cadastre uma graduação para começar." button-type="button" button-action="toggleCadastro()" button-text="Cadastrar graduação" />
                @endif
            @else
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full border-collapse text-left">
                        <thead class="bg-gray-700 text-white">
                            <tr>
                                <th class="border-r border-gray-500 px-5 py-4 text-xs font-bold uppercase tracking-wider">Modalidade</th>
                                <th class="border-r border-gray-500 px-5 py-4 text-xs font-bold uppercase tracking-wider">Nome / Cor</th>
                                <th class="border-r border-gray-500 px-5 py-4 text-xs font-bold uppercase tracking-wider">Ordem</th>
                                <th class="border-r border-gray-500 px-5 py-4 text-xs font-bold uppercase tracking-wider">Meta (aulas)</th>
                                <th class="px-5 py-4 text-center text-xs font-bold uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($graduacoes as $graduacao)
                            <tr class="transition-colors hover:bg-gray-50">
                                <td class="border-r border-gray-200 px-5 py-4 text-sm text-gray-700">{{ $graduacao->modalidade->mod_nome ?? '-' }}</td>
                                <td class="border-r border-gray-200 px-5 py-4 text-sm font-semibold text-gray-800">
                                    <span class="bolinha-faixa mr-2 inline-block h-5 w-5 rounded-full border-2 border-black align-middle" data-faixa="{{ mb_strtolower($graduacao->gradu_nome_cor) }}" style="background-color: transparent;"></span>
                                    {{ $graduacao->gradu_nome_cor }}
                                </td>
                                <td class="border-r border-gray-200 px-5 py-4 text-sm text-gray-700">{{ $graduacao->gradu_ordem ?? '-' }}</td>
                                <td class="border-r border-gray-200 px-5 py-4 text-sm text-gray-700">{{ $graduacao->gradu_meta ?? '-' }}</td>
                                <td class="px-5 py-4">
                                    <x-table-actions
                                        :edit-route="route('graduacoes.edit', Crypt::encrypt($graduacao->id_graduacao))"
                                        :delete-action="route('graduacoes.destroy', Crypt::encrypt($graduacao->id_graduacao))"
                                        :delete-id="'formExcluirGraduacao'.$graduacao->id_graduacao"
                                        delete-modal="modalExclusao" />
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-5">
                    <x-pagination-compact :paginator="$graduacoes" />
                </div>
            @endif
        </div>
    </div>
</div>

<x-modal-confirmacao id="modalExclusao" titulo="Confirmar exclusão" mensagem="Deseja realmente excluir esta graduação?" texto-confirmar="Excluir" />

<script src="{{ asset('js/faixas.js') }}"></script>
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
            cadastro.scrollIntoView({ behavior: 'smooth', block: 'start' });
            document.getElementById('id_modalidade').focus();
        }
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof aplicarCoresFaixas === 'function') aplicarCoresFaixas();
    });
</script>
@endsection