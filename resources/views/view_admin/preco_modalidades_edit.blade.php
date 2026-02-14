@extends('layouts.dashboard')

@section('title', 'Editar Preço')

@section('content')

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Editar Preço - {{ $valor->modalidade->mod_nome ?? 'Modalidade' }}
    </h2>

    <form action="{{ route('preco-aula.update', $valor->id_preco_modalidade) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Modalidade -->
            <div>
                <label class="text-sm font-medium text-gray-600">Modalidade</label>
                <select name="modalidade_id" required
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                    @foreach($modalidades as $modalidade)
                    <option value="{{ $modalidade->id_modalidade }}"
                        {{ $modalidade->id_modalidade == $valor->modalidade_id ? 'selected' : '' }}>
                        {{ $modalidade->mod_nome }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Preço -->
            <div>
                <label class="text-sm font-medium text-gray-600">Preço da Aula</label>

                <input type="text"
                    id="preco_modalidade_mask"
                    required
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">

                <input type="hidden"
                    name="preco_modalidade"
                    id="preco_modalidade"
                    value="{{ old('preco_modalidade', $valor->preco_modalidade) }}">
            </div>

            <!-- Plano -->
            <div>
                <label class="text-sm font-medium text-gray-600">Plano</label>
                <select name="preco_plano" required
                    class="w-full border rounded-lg px-4 py-2 mt-1">

                    <option value="Mensal"
                        {{ $valor->preco_plano == 'Mensal' ? 'selected' : '' }}>
                        Mensal
                    </option>

                    <option value="Trimestral"
                        {{ $valor->preco_plano == 'Trimestral' ? 'selected' : '' }}>
                        Trimestral
                    </option>

                    <option value="Semestral"
                        {{ $valor->preco_plano == 'Semestral' ? 'selected' : '' }}>
                        Semestral
                    </option>

                    <option value="Anual"
                        {{ $valor->preco_plano == 'Anual' ? 'selected' : '' }}>
                        Anual
                    </option>

                </select>
            </div>

        </div>

        <div class="flex justify-end gap-4 mt-6">
            <a href="{{ route('preco-aula') }}"
                class="px-5 py-2 border rounded-lg hover:bg-gray-100 transition">
                Voltar
            </a>

            <button type="submit"
                class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920] transition">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

<script>
    const inputMask = document.getElementById('preco_modalidade_mask');
    const inputHidden = document.getElementById('preco_modalidade');

    function aplicarMascara(valor) {
        valor = valor.replace(/\D/g, '');
        valor = (valor / 100).toFixed(2) + '';
        valor = valor.replace(".", ",");
        valor = valor.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        return 'R$ ' + valor;
    }

    window.addEventListener('load', function() {
        let valorInicial = inputHidden.value;

        if (valorInicial) {
            let valorSemPonto = valorInicial.replace('.', '');
            inputMask.value = aplicarMascara(valorSemPonto);
        }
    });

    inputMask.addEventListener('input', function(e) {

        let valor = e.target.value.replace(/\D/g, '');
        e.target.value = aplicarMascara(valor);

        let valorBanco = e.target.value
            .replace('R$ ', '')
            .replace(/\./g, '')
            .replace(',', '.');

        inputHidden.value = valorBanco;
    });
</script>

@endsection