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

                    <select
                        id="faixaCadastro"
                        name="id_graduacao"
                        class="w-full border rounded-lg px-3 py-2"
                        required
                        disabled>
                        <option value="">Selecione a faixa</option>
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
    <h3 class="text-xl font-bold mb-6 text-gray-700">Graduações Cadastradas</h3>

    @php
    $lista = $graduacoes;
    @endphp


    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Graduação</th>
                <th class="py-3 px-4">Modalidade</th>
                <th class="py-3 px-4">Data</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            <tr id="msgSelecioneModalidade">
                <td colspan="5" class="text-center py-6 text-gray-500">
                    🔍 Selecione uma <strong>modalidade</strong> no filtro acima para visualizar as graduações cadastradas, ou cadastre a primeira.
                </td>
            </tr>

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

        // CADASTRO DE GRADUAÇÃO
        const modalidadeSelect = document.getElementById('modalidadeCadastro');
        const faixaSelect = document.getElementById('faixaCadastro');

        const graduacoes = Array.from(
            document.querySelectorAll('#graduacoes-data div')
        ).map(item => ({
            id: item.dataset.id,
            modalidade: item.dataset.modalidade,
            faixa: item.dataset.faixa,
            ordem: Number(item.dataset.ordem)
        }));

        modalidadeSelect.addEventListener('change', function() {

            faixaSelect.innerHTML =
                '<option value="">Selecione a faixa</option>';

            const modalidadeId = this.value;

            if (!modalidadeId) {
                faixaSelect.disabled = true;
                return;
            }

            faixaSelect.disabled = false;

            graduacoes
                .filter(g => g.modalidade == modalidadeId)
                .sort((a, b) => a.ordem - b.ordem)
                .forEach(g => {

                    faixaSelect.innerHTML += `
            <option value="${g.id}">
                ${g.faixa}
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
            'graduacao_professor_modalidade'
        );

        if (modalidadeSalva) {
            filtroModalidade.value = modalidadeSalva;
        }

        // salva modalidade
        filtroModalidade.addEventListener('change', function() {
            localStorage.setItem(
                'graduacao_professor_modalidade',
                this.value
            );
        });

        function aplicarFiltro() {

            const modalidade = filtroModalidade.value;
            const graduacao = filtroGraduacao.value;

            const msgSelecione = document.getElementById('msgSelecioneModalidade');

            // Mostra a mensagem só quando nenhuma modalidade foi selecionada
            if (msgSelecione) {
                msgSelecione.style.display = modalidade ? 'none' : '';
            }

            linhas.forEach(linha => {

                const modalidadeLinha = linha.dataset.modalidade;
                const graduacaoLinha = linha.dataset.graduacao;

                let mostrar = true;

                if (!modalidade) {
                    mostrar = false;
                }

                if (modalidade && modalidadeLinha !== modalidade) {
                    mostrar = false;
                }

                if (graduacao && graduacaoLinha !== graduacao) {
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
                'graduacao_professor_modalidade'
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
</script>

@endsection