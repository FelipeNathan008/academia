@extends('layouts.dashboard')

@section('title', 'Graduações do Aluno')

@section('content')

<x-alert-error />

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2 flex-wrap">
        <li>
            <a href="{{ route('responsaveis') }}"
                class="hover:text-[#8E251F] transition">
                Responsáveis
            </a>
        </li>
        <li>/</li>
        <li class="text-gray-400">{{ $responsavel->resp_nome }}</li>

        <li>/</li>
        <li>
            <a href="{{ route('alunos', Crypt::encrypt($responsavel->id_responsavel)) }}"
                class="hover:text-[#8E251F] transition">
                Alunos
            </a>
        </li>
        <li>/</li>
        <li>
            <span class="text-gray-400">
                {{ $aluno->aluno_nome }}
            </span>
        </li>
        <li>/</li>
        <li class="font-semibold text-gray-700">
            Graduações
        </li>
        <li>/</li>
        <li class="text-gray-400">Matrículas</li>
        <li>/</li>
        <li class="text-gray-400">Financeiro</li>
    </ol>
</nav>

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('alunos', Crypt::encrypt($aluno->responsavel_id_responsavel))}}" class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>

        <h2 class="text-3xl font-extrabold text-gray-800">Matrículas / Graduações do Aluno</h2>
    </div>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">
        + Cadastrar Graduação
    </button>
</div>

<!-- CARD DO ALUNO -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#174ab9] rounded-2xl shadow-lg p-6">
        <p class="text-xs uppercase tracking-widest text-gray-500">Aluno selecionado</p>
        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">{{ $aluno->aluno_nome }}</h3>
        <p class="mt-2 text-sm text-gray-600">
            Idade:
            <strong class="text-gray-800">
                {{ $aluno->aluno_nascimento ? \Carbon\Carbon::parse($aluno->aluno_nascimento)->age : '-' }}
            </strong>
        </p>
    </div>
</div>

<!-- FORMULÁRIO -->
<div id="cadastroForm" class="hidden mb-10">
    <form action="{{ route('detalhes-aluno.store', Crypt::encrypt($aluno->id_aluno)) }}" method="POST" onsubmit="bloquearSubmit(event, this)" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="aluno_id_aluno" value="{{ $aluno->id_aluno }}">

        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">Cadastrar Graduação do Aluno</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Modalidade
                    </label>

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
                        class="w-full border rounded-lg px-3 py-2"
                        min="{{ $aluno->aluno_nascimento }}"
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

            <!-- Modalidade -->
            <div class="flex flex-col w-[250px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Modalidade
                </label>

                <select id="filtroModalidade"
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">

                    <option value="">Selecione uma modalidade</option>

                    @foreach($modalidades as $modalidade)
                    <option value="{{ $modalidade->id_modalidade }}">
                        {{ $modalidade->mod_nome }}
                    </option>
                    @endforeach

                </select>
            </div>

            <!-- Graduação -->
            <div class="flex flex-col w-[250px]">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide text-center">
                    Graduação
                </label>

                <select id="filtroGraduacao"
                    disabled
                    class="border border-gray-300 rounded-xl px-4 py-3 text-sm bg-white
               focus:ring-2 focus:ring-[#8E251F] focus:outline-none text-center">

                    <option value="">
                        Primeiro selecione uma modalidade
                    </option>

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
    <h3 class="text-xl font-bold mb-6 text-gray-700">GRADUAÇÕES CADASTRADAS</h3>

    @php
    $lista = $graduacoes;
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

            @foreach ($lista as $det)
            <tr class="border-b hover:bg-gray-50 transition linha-graduacao"
                data-modalidade="{{ $det->graduacao->id_modalidade }}"
                data-graduacao="{{ strtolower($det->graduacao->gradu_nome_cor) }}">

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

                <td class="py-3 px-4">
                    {{ \Carbon\Carbon::parse($det->det_data)->format('d/m/Y') }}
                </td>

                <td class="py-3 px-4 flex gap-2">

                    @if($det->det_certificado)
                    <a href="{{ route('detalhes-aluno.showCertificado', ['path' => Crypt::encrypt($det->det_certificado)]) }}"
                        target="_blank"
                        class="px-4 py-2 rounded-lg shadow text-white"
                        style="background:#174ab9;">
                        Ver Certificado
                    </a>
                    @endif

                    <a href="{{ route('detalhes-aluno.edit', Crypt::encrypt($det->id_det_aluno)) }}"
                        class="px-4 py-2 rounded-lg shadow text-white"
                        style="background:#8E251F;">
                        Editar
                    </a>

                    <form action="{{ route('detalhes-aluno.destroy', Crypt::encrypt($det->id_det_aluno)) }}"
                        method="POST"
                        onsubmit="return confirm('Deseja remover esta graduação?');">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Excluir
                        </button>
                    </form>

                </td>

            </tr>
            @endforeach

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

        // ======================================
        // FILTROS
        // ======================================

        const filtroModalidade = document.getElementById('filtroModalidade');
        const filtroGraduacao = document.getElementById('filtroGraduacao');
        const limparBtn = document.getElementById('limparFiltrosGraduacao');
        const linhas = document.querySelectorAll('.linha-graduacao');

        const modalidadeSalva = localStorage.getItem(
            'graduacao_aluno_modalidade'
        );

        if (modalidadeSalva) {
            filtroModalidade.value = modalidadeSalva;
        }

        // salva modalidade
        filtroModalidade.addEventListener('change', function() {
            localStorage.setItem(
                'graduacao_aluno_modalidade',
                this.value
            );
        });

        function aplicarFiltro() {

            const modalidade = filtroModalidade.value;
            const graduacao = filtroGraduacao.value;

            linhas.forEach(linha => {

                const modalidadeLinha = linha.dataset.modalidade;
                const graduacaoLinha = linha.dataset.graduacao;

                let mostrar = true;

                // exatamente igual ao outro código
                if (!modalidade) {
                    mostrar = false;
                }

                if (
                    modalidade &&
                    modalidadeLinha !== modalidade
                ) {
                    mostrar = false;
                }

                if (
                    graduacao &&
                    graduacaoLinha !== graduacao
                ) {
                    mostrar = false;
                }

                linha.style.display = mostrar ? '' : 'none';
            });
        }

        filtroModalidade.addEventListener('change', function() {

            const modalidade = this.value;

            filtroGraduacao.innerHTML =
                '<option value="">Todas</option>';

            filtroGraduacao.value = '';

            if (!modalidade) {

                filtroGraduacao.disabled = true;

                filtroGraduacao.innerHTML =
                    '<option value="">Primeiro selecione uma modalidade</option>';

                aplicarFiltro();
                return;
            }

            filtroGraduacao.disabled = false;

            const graduacoesDisponiveis = new Set();

            linhas.forEach(linha => {

                if (
                    linha.dataset.modalidade === modalidade
                ) {
                    graduacoesDisponiveis.add(
                        linha.dataset.graduacao
                    );
                }
            });

            [...graduacoesDisponiveis]
            .sort()
                .forEach(graduacao => {

                    filtroGraduacao.innerHTML += `
                <option value="${graduacao}">
                    ${graduacao.charAt(0).toUpperCase() + graduacao.slice(1)}
                </option>
            `;
                });

            aplicarFiltro();
        });

        filtroGraduacao.addEventListener(
            'change',
            aplicarFiltro
        );

        limparBtn.addEventListener('click', function() {

            localStorage.removeItem(
                'graduacao_aluno_modalidade'
            );

            filtroModalidade.value = '';

            filtroGraduacao.disabled = true;

            filtroGraduacao.innerHTML =
                '<option value="">Primeiro selecione uma modalidade</option>';

            aplicarFiltro();
        });

        if (filtroModalidade.value) {
            filtroModalidade.dispatchEvent(
                new Event('change')
            );
        } else {
            aplicarFiltro();
        }

        aplicarCoresFaixas();

    });

    // FUNÇÕES GLOBAIS

    function toggleCadastro() {
        const form = document.getElementById('cadastroForm');
        form.classList.toggle('hidden');
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
</script>

@endsection