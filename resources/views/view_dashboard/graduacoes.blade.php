@extends('layouts.dashboard')

@section('title', 'Graduações dos Alunos')

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
            Graduações
        </li>
    </ol>
</nav>

<div class="flex justify-between items-center mb-10">
    <h2 class="text-3xl font-extrabold text-gray-700">
        Graduações dos Alunos
    </h2>

    <a href="{{ route('dashboard') }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
        ← Voltar
    </a>
</div>

<form method="GET" class="mb-6 flex gap-4 items-end">

    <div>
        <label class="text-sm font-semibold text-gray-700">Modalidade:</label>
        <select name="modalidade"
            class="border rounded-lg px-3 py-2 text-gray-700"
            onchange="this.form.submit()">

            @foreach($modalidades as $modalidade)
            <option value="{{ $modalidade }}"
                {{ ($modalidadeFiltro == $modalidade) ? 'selected' : '' }}>
                {{ ucfirst($modalidade) }}
            </option>
            @endforeach

        </select>
    </div>

    <div>
        <label class="text-sm font-semibold text-gray-700">Faixa:</label>
        <select name="faixa"
            class="border rounded-lg px-3 py-2 text-gray-700"
            onchange="this.form.submit()">

            <option value="">Todas</option>

            @foreach($faixas as $faixa)
            <option value="{{ $faixa }}"
                {{ strtolower($faixaFiltro ?? '') == strtolower($faixa) ? 'selected' : '' }}>
                {{ ucfirst($faixa) }}
            </option>
            @endforeach

        </select>
    </div>

</form>

<div class="bg-white rounded-2xl shadow-md p-6">

    <h3 class="text-xl font-bold mb-6 text-gray-700">
        Listagem de Alunos e Graduações
    </h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Aluno</th>
                <th class="py-3 px-4">Responsável</th>
                <th class="py-3 px-4">Modalidade</th>
                <th class="py-3 px-4">Faixa</th>
                <th class="py-3 px-4">Data</th>
                <th class="py-3 px-4 text-center">Ações</th>
            </tr>
        </thead>

        <tbody>

            @forelse($alunos as $aluno)

            @forelse($aluno->graduacoes ?? [] as $modalidade => $graduacao)

            <tr class="border-b hover:bg-red-50 transition">

                <td class="py-3 px-4 font-semibold">
                    {{ $aluno->aluno_nome ?? '-' }}
                </td>

                <td class="py-3 px-4">
                    {{ $aluno->responsavel->resp_nome ?? '-' }}
                </td>

                <td class="py-3 px-4">
                    {{ ucfirst($modalidade) }}
                </td>

                <td class="py-3 px-4">
                    {{ ucfirst($graduacao->det_gradu_nome_cor) }}
                </td>

                <td class="py-3 px-4">
                    {{ \Carbon\Carbon::parse($graduacao->det_data)->format('d/m/Y') }}
                </td>

                <!-- AÇÕES -->
                <td class="py-3 px-4 text-center">

                    <a href="http://127.0.0.1:8000/alunos/{{ $aluno->id_aluno }}/detalhes"
                        class="px-4 py-2 rounded-lg shadow text-white"
                        style="background-color: #174ab9;">
                        Ver Detalhes
                    </a>

                </td>

            </tr>

            @empty
            <tr>
                <td colspan="7" class="text-center py-6 text-gray-500">
                    Nenhuma graduação cadastrada.
                </td>
            </tr>
            @endforelse

            @empty
            <tr>
                <td colspan="7" class="text-center py-6 text-gray-500">
                    Nenhum aluno encontrado.
                </td>
            </tr>
            @endforelse

        </tbody>
    </table>
</div>

@endsection