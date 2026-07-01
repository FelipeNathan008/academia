@extends('layouts.dashboard')

@section('title', 'Editar Graduação do Professor')

@section('content')

<x-alert-error />

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
        <li class="font-semibold text-gray-700">Editar Graduação</li>
    </ol>
</nav>

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Editar Graduação – {{ $professor->prof_nome }}
    </h2>

    <form action="{{ route('detalhes-professor.update', Crypt::encrypt($detalhe->id_det_professor)) }}"
        method="POST"
        enctype="multipart/form-data"
        onsubmit="bloquearSubmit(event, this)">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="text-sm font-medium text-gray-600">Modalidade</label>
                <select id="modalidadeEdicao" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Selecione a modalidade</option>
                    @foreach($modalidades as $modalidade)
                    <option value="{{ $modalidade->id_modalidade }}"
                        {{ $detalhe->graduacao->id_modalidade == $modalidade->id_modalidade ? 'selected' : '' }}>
                        {{ $modalidade->mod_nome }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Faixa</label>
                <select id="faixaEdicao" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Selecione a faixa</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Grau</label>
                <select name="id_graduacao" id="grauEdicao"
                    class="w-full border rounded-lg px-3 py-2" required>
                    <option value="">Selecione o grau</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Data</label>
                <input type="date" name="det_data"
                    class="w-full border rounded-lg px-3 py-2"
                    value="{{ $detalhe->det_data }}"
                    min="{{ $detalhe->professor->prof_nascimento }}"
                    max="{{ now()->format('Y-m-d') }}"
                    required>
            </div>

            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-600">
                    Certificado (Imagem ou PDF) - Opcional
                </label>
                <input type="file" name="det_certificado"
                    class="w-full border rounded-lg px-3 py-2"
                    accept=".jpg,.jpeg,.png,.pdf">

                @if($detalhe->det_certificado)
                <a href="{{ route('detalhes-professor.showCertificado', ['path' => Crypt::encrypt($detalhe->det_certificado)]) }}"
                    target="_blank"
                    class="inline-block mt-2 px-4 py-2 rounded-lg text-white"
                    style="background:#174ab9;">
                    Ver arquivo atual
                </a>
                @endif
            </div>

        </div>

        <div class="flex justify-end gap-4 mt-8">
            <a href="{{ route('detalhes-professor.index', Crypt::encrypt($professor->id_professor)) }}"
                class="px-5 py-2 border rounded-lg hover:bg-gray-100">
                Voltar
            </a>
            <button type="submit"
                class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">
                Salvar Alterações
            </button>
        </div>
    </form>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const modalidadeSelect = document.getElementById('modalidadeEdicao');
        const faixaSelect = document.getElementById('faixaEdicao');
        const grauSelect = document.getElementById('grauEdicao');

        const graduacoes = Array.from(
            document.querySelectorAll('#graduacoes-data div')
        ).map(item => ({
            id: item.dataset.id,
            modalidade: item.dataset.modalidade,
            faixa: item.dataset.faixa,
            grau: item.dataset.grau,
            ordem: Number(item.dataset.ordem)
        }));

        const graduacaoAtual = "{{ $detalhe->id_graduacao }}";
        const faixaAtual = "{{ $detalhe->graduacao->gradu_nome_cor }}";

        modalidadeSelect.addEventListener('change', function() {
            faixaSelect.innerHTML = '<option value="">Selecione a faixa</option>';
            grauSelect.innerHTML = '<option value="">Selecione o grau</option>';

            const modalidadeId = this.value;
            if (!modalidadeId) {
                faixaSelect.disabled = true;
                grauSelect.disabled = true;
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
                const selected = faixa == faixaAtual ? 'selected' : '';
                faixaSelect.innerHTML += `<option value="${faixa}" ${selected}>${faixa}</option>`;
            });

            faixaSelect.dispatchEvent(new Event('change'));
        });

        faixaSelect.addEventListener('change', function() {
            grauSelect.innerHTML = '<option value="">Selecione o grau</option>';

            const modalidadeId = modalidadeSelect.value;
            const faixa = this.value;

            if (!faixa) {
                grauSelect.disabled = true;
                return;
            }

            grauSelect.disabled = false;

            graduacoes
                .filter(g => g.modalidade == modalidadeId && g.faixa == faixa)
                .sort((a, b) => a.ordem - b.ordem)
                .forEach(g => {
                    const selected = g.id == graduacaoAtual ? 'selected' : '';
                    grauSelect.innerHTML += `<option value="${g.id}" ${selected}>Grau ${g.grau}</option>`;
                });
        });

        modalidadeSelect.dispatchEvent(new Event('change'));
    });

    function bloquearSubmit(event, form) {
        if (!form.checkValidity()) return;
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Salvando...';
        }
    }
</script>

@endsection