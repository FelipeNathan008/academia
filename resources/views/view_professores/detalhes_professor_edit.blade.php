@extends('layouts.dashboard')

@section('title', 'Editar Graduação do Professor')

@section('content')

<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li><a href="{{ route('professores.index') }}" class="hover:text-[#8E251F] transition">Professores</a></li>
        <li>/</li>
        <li class="text-gray-400">{{ $professor->prof_nome }}</li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Editar Graduação</li>
    </ol>
</nav>

@if ($errors->any())
<div class="bg-red-100 text-red-700 p-3 rounded mb-3">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Editar Graduação – {{ $detalhe->det_gradu_nome_cor }}</h2>

    <form action="{{ route('detalhes-professor.update', $detalhe->id_det_professor) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="text-sm font-medium text-gray-600">Graduação</label>
                <select name="det_gradu_nome_cor" onchange="preencherGraus(this)" class="w-full border rounded-lg px-3 py-2" required>
                    <option value="">Selecione uma graduação</option>
                    @php $cores = []; @endphp
                    @foreach($graduacoesTotais as $g)
                        @if(!in_array($g->gradu_nome_cor, $cores))
                            @php
                                $cores[] = $g->gradu_nome_cor;
                                $grausDaCor = $graduacoesTotais->filter(fn($x) => $x->gradu_nome_cor == $g->gradu_nome_cor)->pluck('gradu_grau')->sort()->values()->all();
                            @endphp
                            <option value="{{ $g->gradu_nome_cor }}" data-graus="{{ implode(',', $grausDaCor) }}" {{ $detalhe->det_gradu_nome_cor == $g->gradu_nome_cor ? 'selected' : '' }}>
                                {{ $g->gradu_nome_cor }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Grau</label>
                <select name="det_grau" class="w-full border rounded-lg px-3 py-2 grau-input" required>
                    <!-- Será preenchido pelo JS -->
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Modalidade</label>
                <select name="det_modalidade" class="w-full border rounded-lg px-3 py-2" required>
                    <option value="">Selecione</option>
                    @foreach($modalidades as $modalidade)
                        <option value="{{ $modalidade->mod_nome }}" {{ $detalhe->det_modalidade == $modalidade->mod_nome ? 'selected' : '' }}>
                            {{ $modalidade->mod_nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Data</label>
                <input type="date" name="det_data" class="w-full border rounded-lg px-3 py-2" value="{{ $detalhe->det_data }}" required>
            </div>

            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-600">Certificado (Imagem ou PDF) - Opcional</label>
                <input type="file" name="det_certificado" class="w-full border rounded-lg px-3 py-2" accept=".jpg,.jpeg,.png,.pdf">
                @if($detalhe->det_certificado)
                    <a href="{{ asset($detalhe->det_certificado) }}" target="_blank" 
                       style="display:inline-block; background-color:#174ab9; color:white; padding:6px 12px; border-radius:6px; margin-top:6px; text-decoration:none;">
                        Ver o arquivo atual
                    </a>
                @endif
            </div>
        </div>

        <div class="flex justify-end gap-4 mt-8">
            <a href="{{ route('detalhes-professor.index', $professor->id_professor) }}" class="px-5 py-2 border rounded-lg hover:bg-gray-100">Voltar</a>
            <button type="submit" class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920]">Salvar Alterações</button>
        </div>
    </form>
</div>

<script>
function preencherGraus(select) {
    const grauSelect = select.closest('form').querySelector('.grau-input');
    grauSelect.innerHTML = '';
    if (!select.value) {
        grauSelect.innerHTML = '<option value="">Selecione primeiro uma graduação</option>';
        return;
    }
    const graus = select.selectedOptions[0].dataset.graus.split(',').sort((a,b)=>a-b);
    grauSelect.innerHTML = '<option value="">Selecione um grau</option>';
    graus.forEach(g => {
        const option = document.createElement('option');
        option.value = g;
        option.textContent = g;
        // Marca o grau atual
        if (g == "{{ $detalhe->det_grau }}") option.selected = true;
        grauSelect.appendChild(option);
    });
}

// Preencher os graus ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    const selectFaixa = document.querySelector('select[name="det_gradu_nome_cor"]');
    if (selectFaixa) preencherGraus(selectFaixa);
});
</script>

@endsection