@extends('layouts.dashboard')

@section('title', 'Preço por Modalidade')

@section('content')
<x-alert-error />

<div class="w-full px-4 pt-2 pb-6 sm:px-6 lg:px-8">
    <x-page-header title="Preço por Modalidade" :breadcrumbs="[
        ['label' => 'Administração', 'url' => route('admin.administracao')],
        ['label' => 'Preço por Modalidade']
    ]">
        <x-slot:action>
            <div class="flex flex-wrap items-center gap-3">
                <x-back-button :url="route('admin.administracao')" />
                <x-primary-button icon="plus" onclick="toggleCadastro()">Cadastrar valor</x-primary-button>
            </div>
        </x-slot:action>
    </x-page-header>

    <div id="cadastroForm" class="mb-8 hidden">
        <form id="formCadastro" action="{{ route('preco-aula.store') }}" method="POST" onsubmit="bloquearSubmit(event, this)">
            @csrf
            <x-form-card title="Cadastrar valor" description="Preencha os dados do preço por modalidade.">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="modalidade_id" class="mb-2 block text-sm font-semibold text-gray-700">Modalidade</label>
                        <select id="modalidade_id" name="modalidade_id" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                            <option value="">Selecione uma modalidade</option>
                            @foreach ($modalidades as $modalidade)
                            <option value="{{ $modalidade->id_modalidade }}" {{ old('modalidade_id') == $modalidade->id_modalidade ? 'selected' : '' }}>{{ $modalidade->mod_nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="preco_categoria" class="mb-2 block text-sm font-semibold text-gray-700">Categoria</label>
                        <input type="text" id="preco_categoria" name="preco_categoria" value="{{ old('preco_categoria') }}" maxlength="120" required placeholder="Ex.: Plano Família" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                    </div>

                    <div>
                        <label for="preco_modalidade_mask" class="mb-2 block text-sm font-semibold text-gray-700">Preço da aula</label>
                        <input type="text" id="preco_modalidade_mask" required inputmode="decimal" placeholder="R$ 0,00" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                        <input type="hidden" name="preco_modalidade" id="preco_modalidade">
                    </div>

                    <div>
                        <label for="preco_plano" class="mb-2 block text-sm font-semibold text-gray-700">Plano</label>
                        <select id="preco_plano" name="preco_plano" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                            <option value="">Selecione um plano</option>
                            @foreach (['Mensal', 'Trimestral', 'Semestral', 'Anual'] as $plano)
                            <option value="{{ $plano }}" {{ old('preco_plano') === $plano ? 'selected' : '' }}>{{ $plano }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <x-slot:actions>
                    <x-secondary-button onclick="fecharCadastro()">Cancelar</x-secondary-button>
                    <x-primary-button type="submit" icon="check">Salvar valor</x-primary-button>
                </x-slot:actions>
            </x-form-card>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-md">
        <div class="border-b border-gray-200 px-6 py-5">
            <form action="{{ route('preco-aula') }}" method="GET">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div>
                        <label for="filtroModalidade" class="mb-2 block text-sm font-semibold text-gray-700">Modalidade</label>
                        <select id="filtroModalidade" name="modalidade" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                            <option value="">Todas as modalidades</option>
                            @foreach ($modalidades as $modalidade)
                            <option value="{{ $modalidade->mod_nome }}" {{ request('modalidade') === $modalidade->mod_nome ? 'selected' : '' }}>{{ $modalidade->mod_nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filtroCategoria" class="mb-2 block text-sm font-semibold text-gray-700">Categoria</label>
                        <input type="text" id="filtroCategoria" name="categoria" value="{{ request('categoria') }}" placeholder="Buscar categoria..." class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                    </div>
                    <div>
                        <label for="filtroPlano" class="mb-2 block text-sm font-semibold text-gray-700">Plano</label>
                        <select id="filtroPlano" name="plano" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                            <option value="">Todos os planos</option>
                            @foreach (['Mensal', 'Trimestral', 'Semestral', 'Anual'] as $plano)
                            <option value="{{ $plano }}" {{ request('plano') === $plano ? 'selected' : '' }}>{{ $plano }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filtroPreco" class="mb-2 block text-sm font-semibold text-gray-700">Preço exato</label>
                        <input type="text" id="filtroPreco" name="preco" value="{{ request('preco') }}" inputmode="decimal" placeholder="Ex.: 150,00" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-[#8E251F] focus:ring-2 focus:ring-[#8E251F]/10">
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-3">
                    <x-primary-button type="submit" icon="search">Buscar</x-primary-button>
                    <a href="{{ route('preco-aula') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-200">Limpar</a>
                </div>
            </form>

            <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                <p class="text-sm text-gray-500">Consulte e gerencie os preços cadastrados por modalidade.</p>
                <x-record-count :count="$valores->total()" singular="valor" plural="valores" />
            </div>
        </div>

        <div class="p-4 sm:p-6">
            @if ($valores->isEmpty())
            @if (request()->hasAny(['modalidade', 'categoria', 'plano', 'preco']) && collect(request()->only(['modalidade', 'categoria', 'plano', 'preco']))->filter(fn ($valor) => $valor !== null && $valor !== '')->isNotEmpty())
            <x-empty-state title="Nenhum preço encontrado" message="Tente alterar os filtros utilizados." :route="route('preco-aula')" button-text="Limpar filtros" />
            @else
            <x-empty-state title="Nenhum preço cadastrado" message="Cadastre um valor para começar." button-type="button" button-action="toggleCadastro()" button-text="Cadastrar valor" />
            @endif
            @else
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="w-full border-collapse text-left">
                    <thead class="bg-gray-700 text-white">
                        <tr>
                            <th class="border-r border-gray-500 px-5 py-4 text-xs font-bold uppercase tracking-wider">Modalidade</th>
                            <th class="border-r border-gray-500 px-5 py-4 text-xs font-bold uppercase tracking-wider">Categoria</th>
                            <th class="border-r border-gray-500 px-5 py-4 text-xs font-bold uppercase tracking-wider">Plano</th>
                            <th class="border-r border-gray-500 px-5 py-4 text-xs font-bold uppercase tracking-wider">Preço</th>
                            <th class="px-5 py-4 text-center text-xs font-bold uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach ($valores as $valor)
                        <tr class="transition-colors hover:bg-gray-50">
                            <td class="border-r border-gray-200 px-5 py-4 text-sm font-semibold text-gray-800">{{ $valor->modalidade->mod_nome ?? 'Sem modalidade' }}</td>
                            <td class="border-r border-gray-200 px-5 py-4 text-sm text-gray-600">{{ $valor->preco_categoria ?? 'Sem categoria' }}</td>
                            <td class="border-r border-gray-200 px-5 py-4 text-sm text-gray-600">{{ $valor->preco_plano ?? 'Sem plano' }}</td>
                            <td class="border-r border-gray-200 px-5 py-4 text-sm font-semibold text-gray-800">R$ {{ number_format($valor->preco_modalidade, 2, ',', '.') }}</td>
                            <td class="px-5 py-4">
                                <x-table-actions
                                    :edit-route="route('preco-aula.edit', Crypt::encrypt($valor->id_preco_modalidade))"
                                    :delete-action="route('preco-aula.destroy', Crypt::encrypt($valor->id_preco_modalidade))"
                                    :delete-id="'formExcluirPreco'.$valor->id_preco_modalidade"
                                    delete-modal="modalExclusao" />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                <x-pagination-compact :paginator="$valores" />
            </div>
            @endif
        </div>
    </div>
</div>

<x-modal-confirmacao
    id="modalExclusao"
    titulo="Confirmar exclusão"
    mensagem="Deseja realmente excluir este preço por modalidade?"
    texto-confirmar="Excluir" />

<script>
    function formatarPreco(valor) {
        const digitos = valor.replace(/\D/g, '');
        return digitos ? 'R$ ' + (Number(digitos) / 100).toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) : '';
    }

    function configurarMascara(inputId, hiddenId) {
        const input = document.getElementById(inputId);
        const hidden = document.getElementById(hiddenId);
        if (!input || !hidden) return;

        input.addEventListener('input', function() {
            const digitos = this.value.replace(/\D/g, '');
            this.value = formatarPreco(digitos);
            hidden.value = digitos ? (Number(digitos) / 100).toFixed(2) : '';
        });
    }

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
        const abrir = cadastro.classList.contains('hidden');
        cadastro.classList.toggle('hidden', !abrir);
        if (abrir) {
            cadastro.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
            document.getElementById('modalidade_id').focus();
        }
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }

    configurarMascara('preco_modalidade_mask', 'preco_modalidade');
</script>
@endsection