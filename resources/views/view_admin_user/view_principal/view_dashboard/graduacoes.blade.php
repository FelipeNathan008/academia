@extends('layouts.dashboard')

@section('title', 'Alunos por Graduação')

@section('content')
<style>
    .graduacoes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }

    .graduacao-card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #e5e7eb;
        border-left: 6px solid #d1d5db;
        padding: 22px;
        text-decoration: none;
        color: #111827;
        transition: .25s;
        display: flex;
        flex-direction: column;
        min-height: 210px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, .05);

    }

    .graduacao-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, .10);
    }

    .graduacao-card.ativo {
        border: 2px solid #174ab9;
        border-left-width: 6px;
    }

    .graduacao-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .graduacao-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .graduacao-info h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
    }

    .graduacao-info small {
        color: #6b7280;
        font-size: 13px;
    }

    .bolinha-faixa {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 2px solid #d1d5db;
        flex-shrink: 0;
    }

    .check-card {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #174ab9;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
    }

    .graduacao-total {
        margin-top: 30px;
        font-size: 48px;
        font-weight: 800;
        color: #111827;
    }

    .graduacao-texto {
        color: #6b7280;
        margin-top: 5px;
        font-size: 14px;
    }

    .graduacao-footer {
        margin-top: auto;
        border-top: 1px solid #ececec;
        padding-top: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        color: #174ab9;

    }
</style>
<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">

        <li class="font-semibold text-gray-700">Alunos por Graduação</li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex justify-between items-center mb-8">
    <h2 class="text-3xl font-extrabold text-gray-800">
        Alunos por Graduação
    </h2>

    <a href="{{ route('dashboard') }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
        ← Dashboard
    </a>
</div>

<!-- FILTRO MODALIDADE -->
<div class="bg-white rounded-2xl shadow-md p-6 mb-8">
    <div class="mb-8">
        <form method="GET"
            action="{{ route('dashboard.graduacoes') }}"
            class="flex flex-col items-center gap-4">

            <div class="flex flex-col w-full max-w-sm">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">
                    Modalidade
                </label>

                <select name="modalidade"
                    onchange="this.form.submit()"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                       focus:ring-2 focus:ring-[#174ab9] focus:outline-none">

                    <option value="">Selecione a modalidade</option>

                    @foreach ($modalidades as $modalidade)
                    <option value="{{ $modalidade }}"
                        {{ $modalidadeSelecionada == $modalidade ? 'selected' : '' }}>
                        {{ $modalidade }}
                    </option>
                    @endforeach

                </select>
            </div>

            @if($modalidadeSelecionada && $faixaSelecionada)
            <a href="{{ route('dashboard.graduacoes', ['modalidade' => $modalidadeSelecionada]) }}"
                class="h-[48px] px-5 rounded-xl bg-gray-200 text-gray-700 font-semibold
           hover:bg-gray-300 transition flex items-center justify-center">
                ✕ Limpar faixa
            </a>
            @endif


        </form>
    </div>


    @if(!$modalidadeSelecionada)

    <div class="bg-white rounded-2xl shadow-md p-12 text-center text-gray-400">
        <p class="text-lg">Selecione uma modalidade para ver as graduações.</p>
    </div>

    @elseif($porGraduacao->isEmpty())

    <div class="bg-white rounded-2xl shadow-md p-12 text-center text-gray-400">
        <p class="text-lg">Nenhuma graduação cadastrada para esta modalidade.</p>
    </div>

    @else

    <!-- CARDS DE GRADUAÇÃO -->
    <div class="graduacoes-grid">

        @foreach ($porGraduacao as $grad)

        @php
        $faixaNome = strtolower(trim($grad->gradu_nome_cor));
        $ativo = $faixaSelecionada && strtolower($faixaSelecionada) === $faixaNome;
        @endphp

        <a href="{{ route('dashboard.graduacoes', array_filter([
                'modalidade'=>$modalidadeSelecionada,
                'faixa'=>$faixaNome
            ])) }}#listagem-alunos"
            class="graduacao-card {{ $ativo ? 'ativo' : '' }}"
            data-faixa-nome="{{ $faixaNome }}">

            <div class="graduacao-top">

                <div class="graduacao-info">

                    <span class="bolinha-faixa"
                        data-faixa="{{ $faixaNome }}">
                    </span>

                    <div>

                        <h4>{{ $grad->gradu_nome_cor }}</h4>


                    </div>

                </div>

                @if($ativo)
                <span class="check-card">✓</span>
                @endif

            </div>

            <div class="graduacao-total">
                {{ $grad->total }}
            </div>

            <div class="graduacao-texto">
                {{ $grad->total == 1 ? 'Aluno cadastrado' : 'Alunos cadastrados' }}
            </div>

            <div class="graduacao-footer">

                <span>
                    {{ $ativo ? 'Listagem aberta' : 'Visualizar alunos' }}
                </span>

                <span>➜</span>

            </div>

        </a>

        @endforeach

    </div>

    @endif

    <!-- LISTAGEM DE ALUNOS DA FAIXA SELECIONADA -->
    @if($faixaSelecionada && $modalidadeSelecionada)

    <div id="listagem-alunos" class="bg-white rounded-2xl shadow-md p-6">

        <h3 class="text-xl font-bold mb-2 text-gray-700 flex items-center gap-3 flex-wrap">

            <span class="bolinha-faixa inline-block w-5 h-5 rounded-full border-2 border-gray-300 shadow-sm"
                data-faixa="{{ strtolower($faixaSelecionada) }}">
            </span>

            <span class="capitalize">{{ $faixaSelecionada }}</span>

            <span class="text-sm text-gray-400 font-normal">
                — {{ $modalidadeSelecionada }}
            </span>

            <span class="ml-auto bg-gray-200 text-gray-700 text-sm px-3 py-1 rounded-full font-semibold">
                {{ $alunosDaFaixa->count() }}
                {{ $alunosDaFaixa->count() == 1 ? 'aluno' : 'alunos' }}
            </span>

        </h3>

        <p class="text-sm text-gray-400 mb-6">
            Alunos cuja última graduação em "{{ $modalidadeSelecionada }}" é esta faixa.
        </p>

        @if($alunosDaFaixa->isEmpty())

        <p class="text-center py-8 text-gray-400">
            Nenhum aluno encontrado nesta faixa.
        </p>

        @else

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b text-gray-600 text-sm">
                    <th class="py-3 px-4">Aluno</th>
                    <th class="py-3 px-4">Graduação</th>
                    <th class="py-3 px-4">Grau</th>
                    <th class="py-3 px-4">Data</th>
                    <th class="py-3 px-4 text-center">Ações</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($alunosDaFaixa as $aluno)
                <tr class="border-b hover:bg-gray-50 transition">

                    <td class="py-3 px-4 font-semibold text-gray-800">
                        {{ $aluno->aluno_nome }}
                    </td>

                    <td class="py-3 px-4">
                        <div class="flex items-center gap-2">
                            <span class="bolinha-faixa inline-block w-4 h-4 rounded-full border border-gray-300 flex-shrink-0"
                                data-faixa="{{ strtolower($aluno->gradu_nome_cor) }}">
                            </span>
                            <span class="capitalize text-sm">
                                {{ $aluno->gradu_nome_cor }}
                            </span>
                        </div>
                    </td>

                    <td class="py-3 px-4 text-gray-600">
                        {{ $aluno->gradu_grau ?? '-' }}
                    </td>

                    <td class="py-3 px-4 text-gray-600">
                        {{ $aluno->det_data
                        ? \Carbon\Carbon::parse($aluno->det_data)->format('d/m/Y')
                        : '-' }}
                    </td>

                    <td class="py-3 px-4 text-center">
                        <a href="{{ route('alunos', Crypt::encrypt($aluno->responsavel_id_responsavel)) }}"
                            style="background-color: #174ab9; color: white;"
                            class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 inline-block">
                            Ver Aluno
                        </a>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>

        @endif

    </div>

    @endif
</div>
<script src="{{ asset('js/faixas.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        if (typeof aplicarCoresFaixas === 'function') {
            aplicarCoresFaixas();
        }

        const CORES_BORDER = {
            'branca': '#d1d5db',
            'cinza': '#6b7280',
            'cinza e branca': '#9ca3af',
            'cinza e preta': '#4b5563',
            'cinza e azul': '#3b82f6',
            'amarela': '#eab308',
            'amarela e branca': '#facc15',
            'amarela e preta': '#ca8a04',
            'laranja': '#f97316',
            'laranja e branca': '#fb923c',
            'laranja e preta': '#ea580c',
            'verde': '#22c55e',
            'verde e branca': '#4ade80',
            'verde e preta': '#15803d',
            'azul': '#2563eb',
            'roxa': '#7c3aed',
            'marrom': '#78350f',
            'preta': '#111827',
            'vermelha': '#dc2626',
        };

        document.querySelectorAll('[data-faixa-nome]').forEach(card => {
            const faixa = card.dataset.faixaNome;
            card.style.borderLeftColor = CORES_BORDER[faixa] || '#d1d5db';
        });

    });
</script>

@endsection