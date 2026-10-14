@extends('layouts.dashboard')

@section('title', 'Visitantes')

@section('content')

<x-alert-error />

<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.principal') }}"
            class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>
        <h2 class="text-3xl font-extrabold text-gray-800">
            Visitantes
        </h2>
    </div>

    <button type="button"
        onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">
        + Cadastrar Visitante
    </button>
</div>

<div id="cadastroForm" class="{{ $errors->any() ? '' : 'hidden' }} mb-8">
    <form action="{{ route('visitantes.store') }}"
        method="POST"
        onsubmit="bloquearSubmit(event, this)">
        @csrf

        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">
                Cadastrar Visitante
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-600">
                        Nome do Visitante
                    </label>
                    <input type="text"
                        name="vis_nome"
                        required
                        maxlength="120"
                        value="{{ old('vis_nome') }}"
                        placeholder="Ex: João da Silva"
                        oninput="validarNome(this)"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        E-mail
                    </label>
                    <input type="email"
                        name="vis_email"
                        maxlength="150"
                        value="{{ old('vis_email') }}"
                        placeholder="Ex: joao@email.com"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Telefone
                    </label>
                    <input type="text"
                        id="vis_telefone"
                        name="vis_telefone"
                        maxlength="15"
                        value="{{ old('vis_telefone') }}"
                        placeholder="Ex: (99) 99999-9999"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Data de Nascimento
                    </label>
                    <input type="date"
                        id="vis_data_nasc"
                        name="vis_data_nasc"
                        max="{{ now()->format('Y-m-d') }}"
                        value="{{ old('vis_data_nasc') }}"
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Status
                    </label>
                    <select name="vis_status"
                        required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
                        <option value="">Selecione o Status</option>
                        <option value="Ativo" {{ old('vis_status', 'Ativo') === 'Ativo' ? 'selected' : '' }}>
                            Ativo
                        </option>
                        <option value="Inativo" {{ old('vis_status') === 'Inativo' ? 'selected' : '' }}>
                            Inativo
                        </option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                <button type="button"
                    onclick="fecharCadastro()"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-100">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">
                    Salvar
                </button>
            </div>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-md p-6 mb-8">
    <div class="flex flex-col gap-6 max-w-6xl mx-auto">
        <div>
            <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                Buscar Visitante
            </label>

            <form method="GET" class="flex flex-wrap gap-2 items-end">
                <input type="text"
                    name="nome"
                    value="{{ request('nome') }}"
                    placeholder="Digite o nome..."
                    class="flex-1 min-w-[250px] border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white focus:ring-2 focus:ring-[#8E251F] focus:outline-none">

                <button type="submit"
                    class="h-[48px] px-6 rounded-xl bg-[#8E251F] text-white hover:bg-[#732920] transition shadow-md">
                    Buscar
                </button>

                <a href="{{ route('visitantes') }}"
                    class="h-[48px] px-6 rounded-xl bg-gray-300 text-gray-800 flex items-center justify-center hover:bg-gray-400 transition shadow-md">
                    Limpar
                </a>
            </form>
        </div>

        <div>
            <label class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide block">
                Filtrar pela primeira letra
            </label>

            <div class="flex flex-wrap gap-2">
                @foreach (range('A', 'Z') as $letra)
                <a href="{{ route('visitantes', ['letra' => $letra]) }}"
                    class="w-10 h-10 flex items-center justify-center rounded-lg font-bold text-sm transition shadow-sm {{ strtoupper(request('letra')) === $letra ? 'bg-[#8E251F] text-white' : 'bg-gray-100 text-gray-700 hover:bg-[#8E251F] hover:text-white' }}">
                    {{ $letra }}
                </a>
                @endforeach
            </div>
        </div>

        <div>
            <label class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide block">
                Status
            </label>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('visitantes', array_filter([
                    'nome' => request('nome'),
                    'letra' => request('letra')
                ])) }}"
                    class="px-4 py-2 rounded-lg font-semibold text-sm transition {{ !request('status') ? 'bg-[#8E251F] text-white' : 'bg-gray-100 text-gray-700 hover:bg-[#8E251F] hover:text-white' }}">
                    Todos
                </a>

                <a href="{{ route('visitantes', array_filter([
                    'nome' => request('nome'),
                    'letra' => request('letra'),
                    'status' => 'Ativo'
                ])) }}"
                    class="px-4 py-2 rounded-lg font-semibold text-sm transition {{ request('status') === 'Ativo' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-green-600 hover:text-white' }}">
                    Ativos
                </a>

                <a href="{{ route('visitantes', array_filter([
                    'nome' => request('nome'),
                    'letra' => request('letra'),
                    'status' => 'Inativo'
                ])) }}"
                    class="px-4 py-2 rounded-lg font-semibold text-sm transition {{ request('status') === 'Inativo' ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-600 hover:text-white' }}">
                    Inativos
                </a>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700 flex items-center gap-4 flex-wrap">
        <span>VISITANTES CADASTRADOS</span>
        <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm">
            Total: {{ $totalVisitantes }}
        </span>
        <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm">
            Filtrados: {{ $visitantes->total() }}
        </span>
    </h3>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b text-gray-600 text-sm">
                    <th class="py-3 px-4 text-left">Nome</th>
                    <th class="py-3 px-4 text-left">Telefone</th>
                    <th class="py-3 px-4 text-left">E-mail</th>
                    <th class="py-3 px-4 text-left">Data Nasc.</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-left">Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($visitantes as $visitante)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="py-3 px-4">
                        {{ Str::upper($visitante->vis_nome) }}
                    </td>

                    <td class="py-3 px-4">
                        @if ($visitante->vis_telefone)
                        {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $visitante->vis_telefone) }}
                        @else
                        -
                        @endif
                    </td>

                    <td class="py-3 px-4">
                        {{ $visitante->vis_email ?? '-' }}
                    </td>

                    <td class="py-3 px-4">
                        {{ $visitante->vis_data_nasc ? $visitante->vis_data_nasc->format('d/m/Y') : '-' }}
                    </td>

                    <td class="py-3 px-4">
                        @if ($visitante->vis_status === 'Ativo')
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            Ativo
                        </span>
                        @else
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-700">
                            Inativo
                        </span>
                        @endif
                    </td>

                    <td class="py-3 px-4">
                        <div class="flex gap-2 flex-wrap">
                            <a href="{{ route('visitantes.grade', Crypt::encrypt($visitante->id_visitante)) }}"
                                class="px-4 py-2 rounded-lg shadow text-white bg-[#174ab9] hover:bg-[#1e40af] transition">
                                Grade de Horário
                            </a>

                            <a href="{{ route('visitantes.edit', Crypt::encrypt($visitante->id_visitante)) }}"
                                class="px-4 py-2 rounded-lg shadow text-white bg-[#8E251F] hover:bg-[#732920] transition">
                                Editar
                            </a>

                            <form action="{{ route('visitantes.destroy', Crypt::encrypt($visitante->id_visitante)) }}"
                                method="POST"
                                onsubmit="return confirm('Deseja remover este visitante?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-2 rounded-lg shadow text-white bg-red-600 hover:bg-red-700 transition">
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-6 text-gray-500">
                        Nenhum visitante cadastrado
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-pagination-compact :paginator="$visitantes" />

</div>

<script>
    function toggleCadastro() {
        const form = document.getElementById('cadastroForm');

        if (!form.classList.contains('hidden')) {
            fecharCadastro();
            return;
        }

        form.classList.remove('hidden');
        form.scrollIntoView({
            behavior: 'smooth'
        });
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }

    function bloquearSubmit(event, form) {
        if (!form.checkValidity()) {
            return;
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

    const tel = document.getElementById('vis_telefone');

    if (tel) {
        tel.addEventListener('input', () => {
            let v = tel.value.replace(/\D/g, '');

            if (v.length > 11) {
                v = v.slice(0, 11);
            }

            let formatado = '';

            if (v.length > 0) {
                formatado = '(' + v.slice(0, 2);
            }

            if (v.length >= 3) {
                formatado += ') ' + v.slice(2, 7);
            }

            if (v.length >= 8) {
                formatado += '-' + v.slice(7, 11);
            }

            tel.value = formatado;
        });
    }

    const dataNasc = document.getElementById('vis_data_nasc');

    if (dataNasc) {
        dataNasc.addEventListener('change', () => {
            if (dataNasc.value > dataNasc.max) {
                dataNasc.value = dataNasc.max;
            }
        });
    }
</script>

@endsection