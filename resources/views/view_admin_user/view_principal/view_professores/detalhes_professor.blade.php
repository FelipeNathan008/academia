@extends('layouts.dashboard')

@section('title', 'Graduações do Professor')

@section('content')


<x-alert-error />

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('professores') }}" class="hover:text-[#8E251F] transition">
                Professores
            </a>
        </li>
        <li>/</li>
        <li class="text-gray-400">{{ $professor->prof_nome }}</li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Graduações</li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('professores') }}" class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>

        <h2 class="text-3xl font-extrabold text-gray-800">Graduações do Professor</h2>
    </div>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">
        + Cadastrar Graduação
    </button>
</div>

<!-- CARD DO PROFESSOR -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#174ab9] rounded-2xl shadow-lg p-6">
        <p class="text-xs uppercase tracking-widest text-gray-500">Professor selecionado</p>
        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">{{ $professor->prof_nome }}</h3>
        <p class="mt-2 text-sm text-gray-600">
            Telefone:
            <strong class="text-gray-800">
                {{ $professor->prof_telefone
                    ? preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $professor->prof_telefone)
                    : '-' }}
            </strong>
        </p>
    </div>
</div>

<!-- FORMULÁRIO -->
<div id="cadastroForm" class="hidden mb-10">
    <form action="{{ route('detalhes-professor.store', Crypt::encrypt($professor->id_professor)) }}" method="POST" enctype="multipart/form-data" onsubmit="bloquearSubmit(event, this)">
        @csrf
        <input type="hidden" name="professor_id_professor" value="{{ $professor->id_professor }}">

        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">Cadastrar Graduação do Professor</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-600">Graduação</label>
                    <select id="modalidadeCadastro"
                        class="w-full border rounded-lg px-3 py-2">
                        <option value="">Selecione a modalidade</option>

                        @foreach($modalidades as $modalidade)
                        <option value="{{ $modalidade->id_modalidade }}">
                            {{ $modalidade->mod_nome }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Faixa
                    </label>

                    <select id="faixaCadastro"
                        class="w-full border rounded-lg px-3 py-2"
                        disabled>
                        <option value="">Selecione a faixa</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Grau
                    </label>

                    <select name="id_graduacao"
                        id="grauCadastro"
                        class="w-full border rounded-lg px-3 py-2"
                        required
                        disabled>
                        <option value="">Selecione o grau</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Data</label>
                    <input type="date"
                        name="det_data"
                        id="dataGraduacaoProfessor"
                        class="w-full border rounded-lg px-3 py-2"
                        max="{{ now()->format('Y-m-d') }}"
                        required>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-600">Certificado (Imagem ou PDF) - Opcional</label>
                    <input type="file" name="det_certificado" class="w-full border rounded-lg px-3 py-2" accept=".jpg,.jpeg,.png,.pdf">
                </div>
            </div>

            <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                <button type="button" onclick="fecharCadastro()" class="px-4 py-2 border rounded-lg hover:bg-gray-100">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">Salvar</button>
            </div>
        </div>
    </form>
</div>

<!-- FILTROS -->
<div class="bg-white rounded-2xl shadow-md p-6 overflow-x-auto mb-8">

    <div class="flex justify-center">
        <div class="flex flex-wrap gap-6 items-end justify-center">

            <!-- Graduação -->
            <div class="flex flex-col w-[250px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Graduação
                </label>
                <select id="filtroGraduacao"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                           focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">
                    <option value="">Todas</option>
                    @foreach($graduacoesTotais->pluck('gradu_nome_cor')->unique() as $cor)
                    <option value="{{ strtolower($cor) }}">
                        {{ $cor }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Modalidade -->
            <div class="flex flex-col w-[250px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Modalidade
                </label>
                <select id="filtroModalidade"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
                    focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">
                    <option value="">Todas</option>

                    @foreach($modalidades as $modalidade)
                    <option value="{{ $modalidade->id_modalidade }}">
                        {{ $modalidade->mod_nome }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Limpar -->
            <button id="limparFiltrosGraduacao"
                class="h-[48px] px-6 rounded-xl bg-gray-300
                       text-gray-800 font-semibold hover:bg-gray-400
                       transition shadow-md">
                Limpar filtros
            </button>

        </div>
    </div>

</div>


<!-- LISTAGEM EM TABELA -->
<div class="bg-white rounded-2xl shadow-md p-6 mb-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">Graduações Cadastradas</h3>

    @php
    $ordem = [
    'cinza e branca' => 1,
    'cinza' => 2,
    'cinza e preta' => 3,

    'amarela e branca' => 4,
    'amarela' => 5,
    'amarela e preta' => 6,

    'laranja e branca' => 7,
    'laranja' => 8,
    'laranja e preta' => 9,

    'verde e branca' => 10,
    'verde' => 11,
    'verde e preta' => 12,

    'branca' => 13,
    'azul' => 14,
    'roxa' => 15,
    'marrom' => 16,
    'preta' => 17,
    ];

    $lista = $graduacoes->sort(function ($a, $b) use ($ordem) {

    $faixaA = strtolower($a->det_gradu_nome_cor);
    $faixaB = strtolower($b->det_gradu_nome_cor);

    $ordA = $ordem[$faixaA] ?? 99;
    $ordB = $ordem[$faixaB] ?? 99;

    $grauA = intval($a->det_grau);
    $grauB = intval($b->det_grau);

    return $ordA === $ordB
    ? $grauB <=> $grauA // grau maior primeiro
        : $ordB <=> $ordA; // faixa invertida
            });
            @endphp


            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b text-gray-600 text-sm">
                        <th class="py-3 px-4">Graduação</th>
                        <th class="py-3 px-4">Grau</th>
                        <th class="py-3 px-4">Modalidade</th>
                        <th class="py-3 px-4">Data</th>
                        <th class="py-3 px-4">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($lista as $det)
                    <tr class="border-b hover:bg-gray-50 transition linha-graduacao"
                        data-graduacao="{{ strtolower($det->graduacao->gradu_nome_cor) }}"
                        data-modalidade="{{ $det->graduacao->id_modalidade }}">

                        <td class="py-3 px-4">
                            <span class="bolinha-faixa"
                                data-faixa="{{ strtolower($det->graduacao->gradu_nome_cor) }}"
                                style="display:inline-block;width:16px;height:16px;border-radius:50%;margin-right:8px;border:2px solid #000;">
                            </span>

                            {{ $det->graduacao->gradu_nome_cor }}
                        </td>

                        <td class="py-3 px-4">
                            {{ $det->graduacao->gradu_grau }}
                        </td>

                        <td class="py-3 px-4">
                            {{ $det->graduacao->modalidade->mod_nome }}
                        </td>
                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($det->det_data)->format('d/m/Y') }}</td>

                        <td class="py-3 px-4 flex gap-2">

                            @if($det->det_certificado)
                            <a href="{{ route('certificado.show', ['path' => Crypt::encrypt($det->det_certificado)]) }}"
                                target="_blank"
                                style="background-color: #174ab9; color: white;"
                                class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                                Ver Certificado
                            </a>
                            @endif

                            <a href="{{ route('detalhes-professor.edit', Crypt::encrypt($det->id_det_professor)) }}"
                                style="background-color: #8E251F; color: white;"
                                class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                                Editar
                            </a>

                            <form action="{{ route('detalhes-professor.destroy', Crypt::encrypt($det->id_det_professor)) }}" method="POST" onsubmit="return confirm('Deseja remover esta graduação?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Excluir</button>
                            </form>
                        </td>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500">Nenhuma graduação cadastrada</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
</div>
<div id="graduacoes-data" style="display:none;">
    @foreach($graduacoesTotais as $g)
    <div
        data-id="{{ $g->id_graduacao }}"
        data-modalidade="{{ $g->id_modalidade }}"
        data-faixa="{{ $g->gradu_nome_cor }}"
        data-grau="{{ $g->gradu_grau }}"
        data-ordem="{{ $g->gradu_ordem }}">
    </div>
    @endforeach
</div>
<script src="{{ asset('js/faixas.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const inputData = document.getElementById('dataGraduacaoProfessor');

        if (inputData) {
            inputData.addEventListener('change', function() {

                const hoje = new Date();
                hoje.setHours(0, 0, 0, 0);

                const dataSelecionada = new Date(this.value);

                if (dataSelecionada > hoje) {
                    alert('A data da graduação não pode ser futura.');

                    this.value = '';
                    this.focus();
                }
            });
        }

    });

    function toggleCadastro() {
        const form = document.getElementById('cadastroForm');
        form.classList.toggle('hidden');
        form.scrollIntoView({
            behavior: 'smooth'
        });
    }

    function bloquearSubmit(event, form) {

        if (!form.checkValidity()) {
            return; // deixa validação normal do HTML
        }

        const btn = form.querySelector('button[type="submit"]');

        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Salvando...';
        }
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }

    // CADASTRO DE GRADUAÇÃO
    const modalidadeSelect = document.getElementById('modalidadeCadastro');
    const faixaSelect = document.getElementById('faixaCadastro');
    const grauSelect = document.getElementById('grauCadastro');

    const graduacoes = Array.from(
        document.querySelectorAll('#graduacoes-data div')
    ).map(item => ({
        id: item.dataset.id,
        modalidade: item.dataset.modalidade,
        faixa: item.dataset.faixa,
        grau: item.dataset.grau,
        ordem: Number(item.dataset.ordem)
    }));

    modalidadeSelect.addEventListener('change', function() {

        faixaSelect.innerHTML =
            '<option value="">Selecione a faixa</option>';

        grauSelect.innerHTML =
            '<option value="">Selecione o grau</option>';

        grauSelect.disabled = true;

        const modalidadeId = this.value;

        if (!modalidadeId) {
            faixaSelect.disabled = true;
            return;
        }

        faixaSelect.disabled = false;

        const faixas = [...new Set(
            graduacoes
            .filter(g => g.modalidade == modalidadeId)
            .sort((a, b) => a.ordem - b.ordem)
            .map(g => g.faixa)
        )];

        faixas.forEach(faixa => {
            faixaSelect.innerHTML += `
                <option value="${faixa}">
                    ${faixa}
                </option>
            `;
        });
    });

    faixaSelect.addEventListener('change', function() {

        grauSelect.innerHTML =
            '<option value="">Selecione o grau</option>';

        const modalidadeId = modalidadeSelect.value;
        const faixa = this.value;

        if (!faixa) {
            grauSelect.disabled = true;
            return;
        }

        grauSelect.disabled = false;

        graduacoes
            .filter(g =>
                g.modalidade == modalidadeId &&
                g.faixa == faixa
            )
            .sort((a, b) => a.ordem - b.ordem)
            .forEach(g => {

                grauSelect.innerHTML += `
                    <option value="${g.id}">
                        Grau ${g.grau}
                    </option>
                `;
            });
    });


    document.addEventListener('DOMContentLoaded', function() {

        const filtroGraduacao = document.getElementById('filtroGraduacao');
        const filtroModalidade = document.getElementById('filtroModalidade');
        const limparBtn = document.getElementById('limparFiltrosGraduacao');
        const linhas = document.querySelectorAll('.linha-graduacao');

        function aplicarFiltro() {

            const graduacao = filtroGraduacao.value;
            const modalidade = filtroModalidade.value;

            linhas.forEach(linha => {

                const graduacaoLinha = linha.dataset.graduacao || '';
                const modalidadeLinha = linha.dataset.modalidade || '';

                let mostrar = true;

                if (graduacao && graduacaoLinha !== graduacao) {
                    mostrar = false;
                }

                if (modalidade && modalidadeLinha !== modalidade) {
                    mostrar = false;
                }

                linha.style.display = mostrar ? '' : 'none';
            });
        }

        filtroGraduacao.addEventListener('change', aplicarFiltro);
        filtroModalidade.addEventListener('change', aplicarFiltro);

        limparBtn.addEventListener('click', function() {
            filtroGraduacao.value = '';
            filtroModalidade.value = '';
            aplicarFiltro();
        });

        aplicarCoresFaixas(); // <- FALTAVA ISSO

    });
</script>

@endsection