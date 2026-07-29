@extends('layouts.dashboard')

@section('title', 'Comprovantes Pendentes')

@section('content')

<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('dashboard') }}"
                class="hover:text-[#8E251F] transition">
                Dashboard
            </a>
        </li>
        <li>/</li>
        <li class="font-semibold text-gray-700">
            Comprovantes Pendentes
        </li>
    </ol>
</nav>

<div class="flex justify-between items-center mb-10">

    <h2 class="text-3xl font-extrabold text-[#174ab9]">
        Comprovantes Pendentes
    </h2>

    <a href="{{ route('dashboard') }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
        ← Voltar
    </a>

</div>

<div class="mb-8">

    <div class="bg-white border-l-8 border-[#174ab9] rounded-2xl shadow-lg p-6">

        <p class="text-xs uppercase tracking-widest text-gray-500">
            Total de Comprovantes Pendentes
        </p>

        <h3 class="text-2xl font-extrabold text-[#174ab9] mt-1">
            {{ $comprovantes->count() }}
        </h3>

    </div>

</div>

<div class="bg-white rounded-2xl shadow-md p-6">

    <h3 class="text-xl font-bold mb-6 text-gray-700">
        Listagem Geral
    </h3>

    <table class="w-full text-left border-collapse">

        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Aluno</th>
                <th class="py-3 px-4">Mês</th>
                <th class="py-3 px-4">Valor</th>
                <th class="py-3 px-4">Enviado em</th>
                <th class="py-3 px-4 text-center">Status</th>
                <th class="py-3 px-4 text-center">Ações</th>
            </tr>
        </thead>

        <tbody>

            @forelse($comprovantes as $item)

            <tr class="border-b hover:bg-blue-50 transition">

                <!-- Aluno -->
                <td class="py-4 px-4">

                    <p class="font-semibold text-gray-800">
                        {{ $item->mensalidade->matricula->aluno->aluno_nome }}
                    </p>

                    <p class="text-sm text-gray-500">
                        Resp.
                        {{ $item->mensalidade->matricula->aluno->responsavel->resp_nome ?? '-' }}
                    </p>

                    <p class="text-sm text-gray-500">
                        Plano:
                        <strong>{{ $item->mensalidade->matricula->matri_plano ?? '-' }}</strong>
                    </p>

                </td>

                <!-- Mês -->
                <td class="py-4 px-4">
                    {{ ucfirst($item->det_mensa_mes_vigente) }}
                </td>

                <!-- Valor -->
                <td class="py-4 px-4 font-bold text-green-600">
                    R$ {{ number_format($item->det_mensa_valor, 2, ',', '.') }}
                </td>

                <!-- Data -->
                <td class="py-4 px-4">
                    {{ optional($item->det_mensa_comprovante_enviado_em)->diffForHumans() }}
                </td>

                <!-- Status -->
                <td class="py-4 px-4 text-center">
                    <span class="inline-block px-4 py-2 text-xs font-semibold text-white rounded-lg"
                        style="background-color: #2563eb;">
                        Comprovante enviado
                    </span>
                </td>

                <!-- Ações -->
                <td class="py-4 px-4">

                    <div class="flex justify-center gap-2 flex-wrap">

                        <a href="{{ route('mensalidade', Crypt::encrypt( $item->mensalidade->matricula->aluno->id_aluno )) }}"
                            style="background-color:#11388c;color:white;"
                            class="px-4 py-2 rounded-lg shadow hover:bg-blue-800 transition">
                            Financeiro
                        </a>
                      
                        <a href="{{ route('mensalidade.comprovante', Crypt::encrypt($item->id_detalhes_mensalidade)) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            style="background-color:#174ab9;color:white;"
                            class="px-4 py-2 rounded-lg shadow hover:bg-blue-800 transition">
                            Comprovante
                        </a>

                        <form
                            method="POST"
                            action="{{ route('mensalidade.aprovar', Crypt::encrypt($item->id_detalhes_mensalidade)) }}"
                            onsubmit="return confirm('Deseja aprovar este comprovante?')">

                            @csrf
                            @method('PUT')

                            <button
                                class="px-4 py-2 rounded-lg shadow text-white hover:bg-[#166534] transition"
                                style="background-color:#15803d;">
                                Aprovar
                            </button>

                        </form>

                        <form
                            method="POST"
                            action="{{ route('mensalidade.rejeitar', Crypt::encrypt($item->id_detalhes_mensalidade)) }}"
                            onsubmit="return confirm('Deseja rejeitar este comprovante?')">

                            @csrf
                            @method('PUT')

                            <button
                                class="px-4 py-2 rounded-lg shadow text-white hover:bg-[#b91c1c] transition"
                                style="background-color:#dc2626;">
                                Rejeitar
                            </button>

                        </form>

                    </div>

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="6" class="text-center py-8 text-gray-500">

                    Nenhum comprovante aguardando aprovação 🎉

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection