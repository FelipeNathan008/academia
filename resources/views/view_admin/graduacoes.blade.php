    @extends('layouts.dashboard')

    @section('title', 'Graduações')

    @section('content')

    <!-- TOPO -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800">Graduações</h2>
        </div>

        <button onclick="toggleCadastro()"
            class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">
            + Cadastrar Graduação
        </button>
    </div>

    <!-- FORMULÁRIO DE CADASTRO -->
    <div id="cadastroForm" class="hidden mb-10">
        <form id="formCadastro" action="{{ route('graduacoes.store') }}" method="POST">
            @csrf
            <div class="bg-white rounded-2xl shadow-md p-8">
                <h3 id="tituloCadastro" class="text-xl font-bold mb-6 text-gray-700">Cadastrar Graduação</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nome/Cor -->
                    <div>
                        <label class="text-sm font-medium text-gray-600">Nome / Cor</label>
                        <input type="text" name="gradu_nome_cor" maxlength="80" required
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                            placeholder="Ex: Faixa Branca">
                    </div>

                    <!-- Grau -->
                    <div>
                        <label class="text-sm font-medium text-gray-600">Grau</label>
                        <input type="number" name="gradu_grau" required
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none"
                            placeholder="Ex: 1">
                    </div>
                </div>

                <!-- AÇÕES -->
                <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                    <button type="button" onclick="fecharCadastro()"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-100 transition">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920] transition">
                        Salvar Graduação
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- LISTAGEM -->
    <div class="bg-white rounded-2xl shadow-md p-6">
        <h3 class="text-xl font-bold mb-6 text-gray-700">Lista de Graduações</h3>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-300 text-gray-600 text-sm">
                    <th class="py-3 px-4">Nome / Cor</th>
                    <th class="py-3 px-4">Grau</th>
                    <th class="py-3 px-4">Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($graduacoes as $graduacao)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="py-3 px-4">
                        <span
                            class="bolinha-faixa"
                            data-faixa="{{ strtolower($graduacao->gradu_nome_cor) }}"
                            style="
                                display:inline-block;
                                width:20px;
                                height:20px;
                                border:2px solid #000;
                                border-radius:50%;
                                margin-right:8px;
                                vertical-align:middle;
                                background-color:transparent;
                            ">
                        </span>

                        {{ $graduacao->gradu_nome_cor }}
                    </td>


                    <td class="py-3 px-4">{{ $graduacao->gradu_grau }}</td>
                    <td class="py-3 px-4 flex gap-2">
                        <!-- Editar -->
                        <a href="{{ route('graduacoes.edit', $graduacao->id_graduacao) }}"
                            style="background-color: #8E251F; color: white;"
                            class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                            Editar
                        </a>

                        <!-- Excluir -->
                        <form action="{{ route('graduacoes.destroy', $graduacao->id_graduacao) }}" method="POST"
                            onsubmit="return confirm('Deseja excluir esta graduação?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                style="background-color: #c02600; color: white;"
                                class="px-4 py-2 rounded-lg shadow hover:bg-[#D65A3E] transition duration-200">
                                Excluir
                            </button>
                        </form>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="3" class="text-center text-gray-500 py-6">
                        Nenhuma graduação cadastrada
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    <!-- JS -->
    <script>
        document.querySelectorAll('.bolinha-faixa').forEach(bolinha => {
            const faixa = bolinha.dataset.faixa;

            let cor = 'transparent';

            if (faixa.includes('branca')) cor = '#ffffff';
            else if (faixa.includes('cinza e branca')) cor = '#808080';
            else if (faixa.includes('amarela')) cor = '#facc15';
            else if (faixa.includes('laranja')) cor = '#f97316';
            else if (faixa.includes('verde')) cor = '#22c55e';
            else if (faixa.includes('azul')) cor = '#2563eb';
            else if (faixa.includes('roxa')) cor = '#7c3aed';
            else if (faixa.includes('marrom')) cor = '#78350f';
            else if (faixa.includes('preta')) cor = '#000000';

            bolinha.style.backgroundColor = cor;
        });

        function toggleCadastro() {
            const form = document.getElementById('cadastroForm');
            form.classList.toggle('hidden');
            form.scrollIntoView({
                behavior: 'smooth'
            });
            document.getElementById('formCadastro').reset();
        }

        function fecharCadastro() {
            document.getElementById('cadastroForm').classList.add('hidden');
        }
    </script>

    @endsection