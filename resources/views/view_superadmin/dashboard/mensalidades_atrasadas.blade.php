@extends('layouts.dashboard')

@section('title', 'Mensalidades Atrasadas')

@section('content')

<nav class="mb-6 text-sm text-gray-500">

    <ol class="flex items-center gap-2">

        <li>
            <a href="{{ route('dashboard-superadmin') }}"
                class="hover:text-[#8E251F] transition">
                Dashboard
            </a>
        </li>

        <li>/</li>

        <li class="font-semibold text-gray-700">
            Mensalidades Atrasadas
        </li>

    </ol>

</nav>

<div class="flex justify-between items-center mb-10">

    <h2 class="text-3xl font-extrabold text-red-600">
        Mensalidades Atrasadas
    </h2>

    <a href="{{ route('dashboard-superadmin') }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
        ← Voltar
    </a>

</div>

<div class="mb-8">

    <div class="bg-white border-l-8 border-red-600 rounded-2xl shadow-lg p-6">

        <p class="text-xs uppercase tracking-widest text-gray-500">
            Total de Mensalidades com Atraso
        </p>

        <h3 class="text-2xl font-extrabold text-red-600 mt-1">
            {{ $mensalidadesAtrasadas }}
        </h3>

    </div>

</div>

<div class="bg-white rounded-2xl shadow-md p-6">

    <h3 class="text-xl font-bold mb-6 text-gray-700">
        Listagem Geral
    </h3>

    <div class="overflow-x-auto">

        <table class="w-full text-left border-collapse">

            <thead>

                <tr class="border-b text-gray-600 text-sm">

                    <th class="py-3 px-4">
                        Empresa
                    </th>

                    <th class="py-3 px-4">
                        Referência
                    </th>

                    <th class="py-3 px-4">
                        Vencimento
                    </th>

                    <th class="py-3 px-4 text-right">
                        Valor
                    </th>

                    <th class="py-3 px-4 text-center">
                        Status
                    </th>
                    <th class="py-3 px-4 text-center">
                        Ações
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($mensalidades as $mensalidade)

                <tr class="border-b hover:bg-red-50 transition">

                    {{-- EMPRESA --}}
                    <td class="py-4 px-4">

                        <p class="font-semibold text-gray-800">
                            {{ $mensalidade->detalheContrato->contrato->contrato_empresa_nome ?? '-' }}
                        </p>

                        <p class="text-sm text-gray-500">
                            Responsável:
                            {{ $mensalidade->detalheContrato->contrato->contrato_empresa_responsavel ?? '-' }}
                        </p>

                    </td>

                    {{-- REFERÊNCIA --}}
                    <td class="py-4 px-4">
                        <p class="font-semibold text-gray-800">
                            {{ $mensalidade->mensa_contrato_referencia ?? '-' }}
                        </p>
                    </td>

                    {{-- VENCIMENTO --}}
                    <td class="py-4 px-4">

                        <p class="font-semibold text-red-600">
                            {{ optional($mensalidade->mensa_contrato_vencimento)->format('d/m/Y') ?? '-' }}
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            {{ optional($mensalidade->mensa_contrato_vencimento)->diffForHumans() ?? '-' }}
                        </p>

                    </td>

                    {{-- VALOR --}}
                    <td class="py-4 px-4 text-right font-bold text-red-600">

                        R$
                        {{ number_format($mensalidade->mensa_contrato_valor, 2, ',', '.') }}

                    </td>

                    {{-- STATUS --}}
                    <td class="py-4 px-4 text-center">

                        @switch($mensalidade->mensa_contrato_status)

                        @case('Comprovante enviado')

                        <span
                            class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg"
                            style="background-color:#2563eb;">
                            Comprovante enviado
                        </span>

                        @break

                        @case('Atrasado')

                        <span
                            class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg"
                            style="background-color:#dc2626;">
                            Atrasado
                        </span>

                        @break

                        @case('Rejeitado')

                        <span
                            class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg"
                            style="background-color:#e71f1f;">
                            Rejeitado
                        </span>

                        @break

                        @case('Pendente')

                        <span
                            class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg"
                            style="background-color:#ca8a04;">
                            Pendente
                        </span>

                        @break

                        @default

                        <span
                            class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg"
                            style="background-color:#9ca3af;">
                            {{ $mensalidade->mensa_contrato_status ?? '-' }}
                        </span>

                        @endswitch

                    </td>

                    <td class="py-4 px-4">

                        <div class="flex justify-center gap-2 flex-wrap">

                            @if($mensalidade->mensa_contrato_status == "Comprovante enviado")
                            <a href="{{ route('superadmin-comprovante', Crypt::encrypt($mensalidade->id_mensalidade_contrato)) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                style="background-color:#174ab9;color:white;"
                                class="px-4 py-2 rounded-lg shadow hover:bg-blue-800 transition">
                                Comprovante
                            </a>

                            <form
                                method="POST"
                                action="{{ route('superadmin-comprovante.aprovar', Crypt::encrypt($mensalidade->id_mensalidade_contrato)) }}"
                                onsubmit="return confirm('Deseja aprovar este comprovante?')">

                                @csrf
                                @method('PUT')
                                <button
                                    type="submit"
                                    class="px-4 py-2 rounded-lg shadow text-white hover:bg-[#166534] transition"
                                    style="background-color:#15803d;">
                                    Aprovar
                                </button>
                            </form>

                            <form
                                method="POST"
                                action="{{ route('superadmin-comprovante.rejeitar', Crypt::encrypt($mensalidade->id_mensalidade_contrato)) }}"
                                onsubmit="return confirm('Deseja rejeitar este comprovante?')">

                                @csrf
                                @method('PUT')

                                <button
                                    type="submit"
                                    class="px-4 py-2 rounded-lg shadow text-white hover:bg-[#b91c1c] transition"
                                    style="background-color:#dc2626;">
                                    Rejeitar
                                </button>

                            </form>
                            @else

                            <form
                                method="POST"
                                action="{{ route('superadmin-comprovante.darBaixa', Crypt::encrypt($mensalidade->id_mensalidade_contrato)) }}"
                                onsubmit="return confirm('Deseja dar Baixa este comprovante?')">
                                @csrf
                                @method('PUT')
                                <button
                                    type="submit"
                                    class="px-4 py-2 rounded-lg shadow text-white hover:bg-[#166534] transition"
                                    style="background-color:#15803d;">
                                    Dar baixa
                                </button>
                            </form>

                            @endif
                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6"
                        class="text-center py-8 text-gray-500">

                        Nenhuma mensalidade em atraso.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if($mensalidades->hasPages())

    <x-pagination-compact :paginator="$mensalidades" />


    @endif

</div>

@endsection