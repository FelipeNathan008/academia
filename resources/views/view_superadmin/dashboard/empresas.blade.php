@extends('layouts.dashboard')

@section('title', 'Empresas Clientes')

@section('content')
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li><a href="{{ route('dashboard-superadmin') }}" class="hover:text-[#8E251F] transition">Dashboard</a></li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Empresas Clientes</li>
    </ol>
</nav>

<div class="flex justify-between items-center mb-10">
    <h2 class="text-3xl font-extrabold text-[#8E251F]">Empresas Clientes</h2>
    <a href="{{ route('dashboard-superadmin') }}" class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">← Voltar</a>
</div>

<div class="mb-8">
    <div class="bg-white border-l-8 border-[#8E251F] rounded-2xl shadow-lg p-6">

        <p class="text-xs uppercase tracking-widest text-gray-500">Total de Empresas Clientes</p>
        <h3 class="text-2xl font-extrabold text-[#8E251F] mt-1">{{ $totalEmpresas }}</h3>
    </div>
</div>

<!-- FILTRO -->
<div class="bg-white rounded-2xl shadow-md p-6 mb-8">
    <div class="flex flex-wrap gap-6 items-end justify-center max-w-6xl mx-auto">

        <div class="flex flex-col w-[220px]">
            <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                Buscar Empresa
            </label>

            <input
                type="text"
                name="empresa"
                form="formFiltrosEmpresas"
                value="{{ request('empresa') }}"
                placeholder="Nome ou apelido..."
                class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
        </div>

        <div class="flex flex-col w-[220px]">
            <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                Responsável
            </label>

            <input
                type="text"
                name="responsavel"
                form="formFiltrosEmpresas"
                value="{{ request('responsavel') }}"
                placeholder="Nome do responsável..."
                class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
        </div>

        <div class="flex flex-col w-[180px]">
            <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                Status
            </label>

            <select
                name="status"
                form="formFiltrosEmpresas"
                class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white focus:ring-2 focus:ring-[#8E251F] focus:outline-none">

                <option value="">Todos</option>

                <option value="Cliente" @selected(request('status')==='Cliente' )>
                    Cliente
                </option>

                <option value="Degustação" @selected(request('status')==='Degustação' )>
                    Degustação
                </option>

            </select>
        </div>

        <div class="flex flex-col w-[180px]">
            <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                Tipo
            </label>

            <select
                name="tipo"
                form="formFiltrosEmpresas"
                class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white focus:ring-2 focus:ring-[#8E251F] focus:outline-none">

                <option value="">Todos</option>

                @foreach(\App\Models\Empresa::whereNot('emp_tipo', 'PS')
                ->whereNotNull('emp_tipo')
                ->distinct()
                ->orderBy('emp_tipo')
                ->pluck('emp_tipo') as $tipo)

                <option value="{{ $tipo }}" @selected(request('tipo')===$tipo)>
                    {{ $tipo }}
                </option>

                @endforeach

            </select>
        </div>

        <form
            id="formFiltrosEmpresas"
            method="GET"
            action="{{ route('superadmin.empresas') }}"
            class="flex gap-2 items-end">

            <button
                type="submit"
                class="h-[48px] px-6 rounded-xl bg-[#8E251F] text-white hover:bg-[#732920] transition shadow-md">
                Buscar
            </button>

            <a
                href="{{ route('superadmin.empresas') }}"
                class="h-[48px] px-6 rounded-xl bg-gray-300 text-gray-800 flex items-center justify-center hover:bg-gray-400 transition shadow-md">
                Limpar
            </a>

        </form>

    </div>
</div>

<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">Listagem Geral</h3>

    <h3 class="text-xl font-bold mb-6 text-gray-700 flex items-center gap-4 flex-wrap">
        <span>EMPRESAS</span>

        <span class="bg-gray-200 px-3 py-1 rounded-full text-sm">
            Total: {{ $totalEmpresas }}
        </span>

        <span class="bg-gray-200 px-3 py-1 rounded-full text-sm">
            Filtrados: {{ $empresas->total() }}
        </span>
    </h3>


    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b text-gray-600 text-sm">
                    <th class="py-3 px-4">Empresa</th>
                    <th class="py-3 px-4">Responsável</th>
                    <th class="py-3 px-4">Contato</th>
                    <th class="py-3 px-4 text-center">Matrículas / Contrato</th>
                    <th class="py-3 px-4 text-center">Status</th>
                    <th class="py-3 px-4 text-center">Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse($empresas as $empresa)
                <tr class="border-b hover:bg-red-50 transition">
                    <td class="py-4 px-4">
                        <p class="font-semibold text-gray-800">{{ $empresa->emp_nome ?? '-' }}</p>
                        <p class="text-sm text-gray-500">{{ $empresa->emp_apelido ?? '-' }}</p>
                        <p class="text-xs text-gray-500 mt-1">Tipo: {{ $empresa->emp_tipo ?? '-' }}</p>
                    </td>

                    <td class="py-4 px-4">
                        <p class="font-semibold text-gray-800">{{ $empresa->emp_nome_responsavel ?? '-' }}</p>
                        <p class="text-sm text-gray-500">CPF: {{ $empresa->emp_cpf ?? '-' }}</p>
                    </td>

                    <td class="py-4 px-4">
                        <p class="text-gray-700">{{ $empresa->emp_email_responsavel ?? '-' }}</p>
                        <p class="text-sm text-gray-500">{{ $empresa->emp_telefone_responsavel ?? '-' }}</p>
                    </td>

                    <td class="py-4 px-4 text-center">
                        @if($empresa->detalhe_plano && $empresa->detalhe_plano->det_plano_tipo === 'financeiro')
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                            Pacote adicional
                        </span>
                        <p class="text-xs text-gray-500 mt-2">
                            {{ $empresa->contagem_matriculas }} matrículas
                        </p>
                        @elseif($empresa->limite_matriculas !== null)
                        <div class="min-w-[150px]">
                            <p class="font-bold text-gray-800">
                                {{ $empresa->contagem_matriculas }}
                                <span class="text-gray-400 font-medium">/ {{ $empresa->limite_matriculas }}</span>
                            </p>

                            <div class="w-full bg-gray-100 rounded-full h-2 mt-2 overflow-hidden">
                                @php
                                $corBarra = match($empresa->cor_matriculas) {
                                'red' => 'bg-red-500',
                                'yellow' => 'bg-yellow-500',
                                'green' => 'bg-green-500',
                                default => 'bg-gray-400',
                                };
                                $percentual = $empresa->percentual_matriculas ?? 0;
                                @endphp

                                <div class="h-2 rounded-full {{ $corBarra }}"
                                    data-width="{{ $percentual }}">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ number_format($empresa->percentual_matriculas ?? 0, 0) }}% utilizado
                            </p>

                            @if($empresa->cor_matriculas === 'red')
                            <span class="inline-flex mt-2 px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                ⚠ Limite atingido/excedido
                            </span>
                            @elseif($empresa->cor_matriculas === 'yellow')
                            <span class="inline-flex mt-2 px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                ⚠ Próximo do limite
                            </span>
                            @else
                            <span class="inline-flex mt-2 px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                Dentro do limite
                            </span>
                            @endif
                        </div>
                        @elseif($empresa->contrato_atual)
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                            Limite não definido
                        </span>
                        <p class="text-xs text-gray-500 mt-2">
                            {{ $empresa->contagem_matriculas }} matrículas
                        </p>
                        @else
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                            Sem contrato
                        </span>
                        <p class="text-xs text-gray-500 mt-2">
                            {{ $empresa->contagem_matriculas }} matrículas
                        </p>
                        @endif
                    </td>

                    <td class="py-4 px-4 text-center">
                        @if($empresa->emp_status === 'Cliente')
                        <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg" style="background-color:#15803d;">Cliente</span>
                        @elseif($empresa->emp_status === 'Degustação')
                        <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg" style="background-color:#ca8a04;">Degustação</span>
                        @else
                        <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg" style="background-color:#6b7280;">{{ $empresa->emp_status ?? '-' }}</span>
                        @endif
                    </td>

                    <td class="py-4 px-4 text-center">
                        <a href="{{ route('superadmin.empresa.contratos', Crypt::encrypt($empresa->id_empresa)) }}"
                            class="inline-block px-4 py-2 rounded-lg shadow text-white transition hover:bg-[#732920]"
                            style="background-color:#8E251F;">
                            Detalhes
                        </a>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-8 text-gray-500">
                        Nenhuma empresa cliente cadastrada.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($empresas->hasPages())
    <x-paginationact :paginator="$empresas" />
    @endif
</div>
<script>
    document.querySelectorAll('[data-width]').forEach(element => {
        element.style.width = `${element.dataset.width}%`;
    });
</script>

@endsection