@extends('layouts.dashboard')

@section('title', 'Preço por Modalidade')

@section('content')

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">Preço por Modalidade</h2>
    </div>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] transition-all">
        + Cadastrar Valor
    </button>
</div>

<!-- FORMULÁRIO -->
<div id="cadastroForm" class="hidden mb-10">
    <form action="{{ route('preco-aula.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">Cadastrar Valor</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Modalidade -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Modalidade</label>
                    <select name="modalidade_id" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                        <option value="">Selecione</option>
                        @foreach($modalidades as $modalidade)
                        <option value="{{ $modalidade->id_modalidade }}">
                            {{ $modalidade->mod_nome }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Preço -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Preço da Aula</label>
                    <input type="text" id="preco_modalidade_mask" required
                        class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                        placeholder="R$ 0,00">

                    <input type="hidden" name="preco_modalidade" id="preco_modalidade">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Plano</label>
                    <select name="preco_plano" required
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                        <option value="">Selecione</option>
                        <option value="Mensal">Mensal</option>
                        <option value="Trimestral">Trimestral</option>
                        <option value="Semestral">Semestral</option>
                        <option value="Anual">Anual</option>
                    </select>
                </div>
            </div>

            <!-- BOTÕES -->
            <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                <button type="button" onclick="fecharCadastro()"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-100 transition">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920] transition">
                    Salvar
                </button>
            </div>
        </div>
    </form>
</div>

<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">Lista de Valores</h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gray-300 text-gray-600 text-sm">
                <th class="py-3 px-4">Modalidade</th>
                <th class="py-3 px-4">Plano</th>
                <th class="py-3 px-4">Preço</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($valores as $valor)
            <tr class="border-b hover:bg-gray-50 transition">

                <!-- Nome Modalidade -->
                <td class="py-3 px-4">
                    {{ $valor->modalidade->mod_nome ?? 'Sem modalidade' }}
                </td>

                <!-- Nome Modalidade -->
                <td class="py-3 px-4">
                    {{ $valor->preco_plano ?? 'Sem plano' }}
                </td>


                <!-- Preço -->
                <td class="py-3 px-4">
                    R$ {{ number_format($valor->preco_modalidade, 2, ',', '.') }}
                </td>

                <!-- Ações -->
                <td class="py-3 px-4 flex gap-2">

                    <!-- Editar -->
                    <a href="{{ route('preco-aula.edit', $valor->id_preco_modalidade) }}"
                        class="px-4 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920] transition">
                        Editar
                    </a>

                    <!-- Excluir -->
                    <form action="{{ route('preco-aula.destroy', $valor->id_preco_modalidade) }}"
                        method="POST"
                        onsubmit="return confirm('Deseja excluir este valor?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            Excluir
                        </button>
                    </form>

                </td>
            </tr>

            @empty
            <tr>
                <td colspan="4" class="text-center text-gray-500 py-6">
                    Nenhum valor cadastrado
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- JS -->
<script>
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

    const inputMask = document.getElementById('preco_modalidade_mask');
    const inputHidden = document.getElementById('preco_modalidade');

    inputMask.addEventListener('input', function(e) {

        let valor = e.target.value.replace(/\D/g, '');

        valor = (valor / 100).toFixed(2) + '';
        valor = valor.replace(".", ",");
        valor = valor.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');

        e.target.value = 'R$ ' + valor;

        // envia para o hidden no formato correto para o banco (1234.56)
        let valorBanco = e.target.value
            .replace('R$ ', '')
            .replace(/\./g, '')
            .replace(',', '.');

        inputHidden.value = valorBanco;
    });
</script>

@endsection