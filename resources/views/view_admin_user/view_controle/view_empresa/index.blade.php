@extends('layouts.dashboard')

@section('title', 'Empresa')

@section('content')

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.controle') }}"
            class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>
        <h2 class="text-3xl font-extrabold text-gray-800">Empresa</h2>
    </div>

    <button onclick="toggleEdicao()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">
        Editar Empresa
    </button>
</div>

<!-- FORM EDIT -->
<div id="editForm" class="hidden mb-10">
    <form action="{{ route('empresa.update', urlencode(Crypt::encrypt($empresa->id_empresa))) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">Editar Empresa</h3>

            <div class="flex flex-col gap-4">

                <!-- Nome + Apelido -->
                <div style="display: flex; gap: 4%;">
                    <div style="flex: 1;">
                        <label class="text-sm text-gray-600">Nome</label>
                        <input type="text" name="emp_nome"
                            value="{{ $empresa->emp_nome }}"
                            required
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>

                    <div style="flex: 1;">
                        <label class="text-sm text-gray-600">Apelido</label>
                        <input type="text" name="emp_apelido"
                            value="{{ $empresa->emp_apelido }}"
                            required
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>
                </div>
                <input type="hidden" name="emp_tipo" value="{{ $empresa->emp_tipo }}">

                <!-- Responsável -->
                <div style="display: flex; gap: 4%;">
                    <div style="flex: 1;">
                        <label class="text-sm text-gray-600">Responsável</label>
                        <input type="text" name="emp_nome_responsavel"
                            value="{{ $empresa->emp_nome_responsavel }}"
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>

                    <div style="flex: 1;">
                        <label class="text-sm text-gray-600">Email</label>
                        <input type="email" name="emp_email_responsavel"
                            value="{{ $empresa->emp_email_responsavel }}"
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>
                </div>



                <!-- Telefone + CPF -->
                <div style="display: flex; gap: 4%;">
                    <div style="flex: 1;">
                        <label class="text-sm text-gray-600">Telefone</label>
                        <input type="text" id="telefone"
                            name="emp_telefone_responsavel"
                            value="{{ $empresa->emp_telefone_responsavel }}"
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>

                    <div style="flex: 1;">
                        <label class="text-sm text-gray-600">CPF</label>
                        <input type="text" name="emp_cpf"
                            value="{{ $empresa->emp_cpf }}"
                            oninput="mascaraCPF(this)"
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>
                </div>

                <!-- PIX -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="text-sm text-gray-600">Chave PIX</label>
                        <input
                            type="text"
                            name="emp_chave_pix"
                            value="{{ old('emp_chave_pix', $empresa->emp_chave_pix) }}"
                            placeholder="CPF, telefone, e-mail ou chave aleatória"
                            required
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Nome do Recebedor</label>
                        <input
                            type="text"
                            name="emp_nome_recebedor"
                            value="{{ old('emp_nome_recebedor', $empresa->emp_nome_recebedor) }}"
                            maxlength="25"
                            required
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Cidade</label>
                        <input
                            type="text"
                            name="emp_cidade"
                            value="{{ old('emp_cidade', $empresa->emp_cidade) }}"
                            maxlength="15"
                            style="text-transform: uppercase;"
                            required
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>

                </div>

                <!-- ENDEREÇO -->
                <div class="border-t pt-6 mt-4">
                    <h4 class="text-lg font-semibold text-gray-700 mb-4">
                        Endereço da Empresa
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="text-sm text-gray-600">CEP</label>
                            <input type="text"
                                id="emp_end_cep"
                                name="emp_end_cep"
                                value="{{ old('emp_end_cep', $empresa->emp_end_cep) }}"
                                maxlength="9"
                                class="w-full border rounded-lg px-4 py-2 mt-1">
                        </div>

                        <div>
                            <label class="text-sm text-gray-600">Logradouro</label>
                            <input type="text"
                                id="emp_end_logradouro"
                                name="emp_end_logradouro"
                                value="{{ old('emp_end_logradouro', $empresa->emp_end_logradouro) }}"
                                class="w-full border rounded-lg px-4 py-2 mt-1">
                        </div>

                        <div>
                            <label class="text-sm text-gray-600">Bairro</label>
                            <input type="text"
                                id="emp_end_bairro"
                                name="emp_end_bairro"
                                value="{{ old('emp_end_bairro', $empresa->emp_end_bairro) }}"
                                class="w-full border rounded-lg px-4 py-2 mt-1">
                        </div>

                        <div>
                            <label class="text-sm text-gray-600">Cidade</label>
                            <input type="text"
                                id="emp_end_cidade"
                                name="emp_end_cidade"
                                value="{{ old('emp_end_cidade', $empresa->emp_end_cidade) }}"
                                class="w-full border rounded-lg px-4 py-2 mt-1">
                        </div>

                        <div>
                            <label class="text-sm text-gray-600">Número</label>
                            <input type="text"
                                name="emp_end_numero"
                                value="{{ old('emp_end_numero', $empresa->emp_end_numero) }}"
                                class="w-full border rounded-lg px-4 py-2 mt-1">
                        </div>

                        <div>
                            <label class="text-sm text-gray-600">Complemento</label>
                            <input type="text"
                                name="emp_end_complemento"
                                value="{{ old('emp_end_complemento', $empresa->emp_end_complemento) }}"
                                class="w-full border rounded-lg px-4 py-2 mt-1">
                        </div>

                        <div>
                            <label class="text-sm text-gray-600">Estado (UF)</label>
                            <input type="text"
                                id="emp_end_estado"
                                name="emp_end_estado"
                                maxlength="2"
                                value="{{ old('emp_end_estado', $empresa->emp_end_estado) }}"
                                style="text-transform: uppercase;"
                                class="w-full border rounded-lg px-4 py-2 mt-1">
                        </div>

                    </div>
                </div>

                <!-- FOTO -->
                <div>
                    <label class="text-sm text-gray-600">Logo</label>
                    <input type="file" name="emp_foto" id="logoInput" accept="image/*">

                    <div style="margin-top:10px;">
                        <img id="previewLogo" style="display:none;max-height:120px;border-radius:8px;">
                    </div>
                </div>

            </div>

            <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                <button type="button" onclick="fecharEdicao()" class="px-4 py-2 border rounded-lg">
                    Cancelar
                </button>

                <button type="submit" class="px-5 py-2 bg-[#8E251F] text-white rounded-lg">
                    Salvar
                </button>
            </div>
        </div>
    </form>
</div>

<div class="space-y-8">

    <!-- DEGUSTAÇÃO -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">

        <div class="px-6 py-4 border-b bg-yellow-50">
            <h3 class="text-lg font-bold text-yellow-700">
                Período de Degustação
            </h3>
        </div>
        <div class="grid md:grid-cols-3 gap-6 p-6">
            <div>
                <p class="text-xs uppercase text-gray-500">
                    Início
                </p>

                <p class="font-semibold">
                    {{ \Carbon\Carbon::parse($empresa->emp_degustacao_inicio)->format('d/m/Y') }}
                </p>
            </div>
            <div>
                <p class="text-xs uppercase text-gray-500">
                    Fim
                </p>

                <p class="font-semibold">
                    {{ \Carbon\Carbon::parse($empresa->emp_degustacao_fim)->format('d/m/Y') }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500">
                    Status
                </p>

                @switch($empresa->emp_status)

                @case('Degustação')
                <span class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-700 font-semibold">
                    Degustação
                </span>
                @break

                @case('Cliente')
                <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 font-semibold">
                    Cliente
                </span>
                @break

                @case('Inadimplente')
                <span class="inline-flex px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 font-semibold">
                    Inadimplente
                </span>
                @break

                @case('Suspenso')
                <span class="inline-flex px-3 py-1 rounded-full bg-orange-100 text-orange-700 font-semibold">
                    Suspenso
                </span>
                @break

                @case('Encerrado')
                <span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-700 font-semibold">
                    Encerrado
                </span>
                @break

                @default
                <span class="inline-flex px-3 py-1 rounded-full bg-gray-100 text-gray-700 font-semibold">
                    {{ $empresa->emp_status ?? 'Não definido' }}
                </span>

                @endswitch
            </div>
        </div>
    </div>

    <!-- DADOS DA EMPRESA -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">

        <div class="px-6 py-4 border-b bg-gray-50">
            <h3 class="text-lg font-bold text-gray-700">
                Dados da Empresa
            </h3>
        </div>

        <div class="grid md:grid-cols-2 gap-6 p-6">

            <div>
                <p class="text-xs uppercase text-gray-500">Nome</p>
                <p class="font-semibold text-gray-800">{{ $empresa->emp_nome }}</p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500">Apelido</p>
                <p class="font-semibold text-gray-800">{{ $empresa->emp_apelido }}</p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500">Responsável</p>
                <p class="font-semibold text-gray-800">{{ $empresa->emp_nome_responsavel }}</p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500">Email</p>
                <p class="font-semibold text-gray-800">{{ $empresa->emp_email_responsavel }}</p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500">Telefone</p>
                <p class="font-semibold text-gray-800">{{ $empresa->emp_telefone_responsavel }}</p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500">CPF</p>
                <p class="font-semibold text-gray-800">{{ $empresa->emp_cpf }}</p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500">Tipo</p>
                <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">
                    {{ $empresa->emp_tipo }}
                </span>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500">Logo</p>

                @if($empresa->emp_foto)
                <img
                    src="{{ asset('images/empresas/'.$empresa->emp_foto) }}"
                    class="w-20 h-20 rounded-xl object-cover border">
                @else
                <span class="text-gray-400">Sem logo</span>
                @endif
            </div>

            <!-- ENDEREÇO -->
            <div class="md:col-span-2 border-t pt-6 mt-2">
                <h4 class="text-lg font-bold text-gray-700 mb-4">
                    Endereço da Empresa
                </h4>

                <div class="grid md:grid-cols-2 gap-6">

                    <div>
                        <p class="text-xs uppercase text-gray-500">CEP</p>
                        <p class="font-semibold">{{ $empresa->emp_end_cep ?: '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-500">Logradouro</p>
                        <p class="font-semibold">{{ $empresa->emp_end_logradouro ?: '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-500">Número</p>
                        <p class="font-semibold">{{ $empresa->emp_end_numero ?: '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-500">Complemento</p>
                        <p class="font-semibold">{{ $empresa->emp_end_complemento ?: '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-500">Bairro</p>
                        <p class="font-semibold">{{ $empresa->emp_end_bairro ?: '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-500">Cidade</p>
                        <p class="font-semibold">{{ $empresa->emp_end_cidade ?: '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-500">Estado</p>
                        <p class="font-semibold">{{ $empresa->emp_end_estado ?: '-' }}</p>
                    </div>

                </div>
            </div>

        </div>

    </div>

    <!-- PIX -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">

        <div class="px-6 py-4 border-b bg-green-50">

            <h3 class="text-lg font-bold text-green-800">
                Dados para Recebimento PIX
            </h3>
        </div>
        <div class="grid md:grid-cols-2 gap-6 p-6">
            <div class="md:col-span-2">
                <p class="text-xs uppercase text-gray-500">
                    Chave PIX
                </p>
                <p class="font-mono break-all text-green-700">
                    {{ $empresa->emp_chave_pix }}
                </p>
            </div>
            <div>
                <p class="text-xs uppercase text-gray-500">
                    Nome do Recebedor
                </p>

                <p class="font-semibold">
                    {{ $empresa->emp_nome_recebedor }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500">
                    Cidade
                </p>
                <p class="font-semibold">
                    {{ $empresa->emp_cidade }}
                </p>
            </div>
        </div>

    </div>


</div>

<!-- JS -->
<script>
    const input = document.getElementById("logoInput");

    if (input) {
        input.addEventListener("change", function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById("previewLogo");
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = "block";
                }
                reader.readAsDataURL(file);
            }
        });
    }


    const cepInput = document.getElementById('emp_end_cep');

    if (cepInput) {

        // Máscara
        cepInput.addEventListener('input', function() {

            let cep = this.value.replace(/\D/g, '');

            if (cep.length > 8) {
                cep = cep.substring(0, 8);
            }

            if (cep.length > 5) {
                cep = cep.replace(/^(\d{5})(\d)/, '$1-$2');
            }

            this.value = cep;
        });

        // Busca automática
        cepInput.addEventListener('blur', async function() {

            const cep = this.value.replace(/\D/g, '');

            if (cep.length !== 8) {
                return;
            }

            try {

                const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                const endereco = await response.json();

                if (endereco.erro) {
                    alert('CEP não encontrado.');
                    return;
                }

                document.getElementById('emp_end_logradouro').value = endereco.logradouro ?? '';
                document.getElementById('emp_end_bairro').value = endereco.bairro ?? '';
                document.getElementById('emp_end_cidade').value = endereco.localidade ?? '';
                document.getElementById('emp_end_estado').value = endereco.uf ?? '';

            } catch (e) {

                alert('Erro ao consultar o CEP.');

            }

        });

    }

    function toggleEdicao() {
        const form = document.getElementById('editForm');
        form.classList.toggle('hidden');
        form.scrollIntoView({
            behavior: 'smooth'
        });
    }

    function fecharEdicao() {
        document.getElementById('editForm').classList.add('hidden');
    }

    function bloquearSubmit(event, form) {
        if (!form.checkValidity()) return;

        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Salvando...';
        }
    }

    function mascaraCPF(input) {
        let value = input.value.replace(/\D/g, '').slice(0, 11);

        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

        input.value = value;
    }

    const tel = document.getElementById('telefone');

    if (tel) {
        tel.addEventListener('input', () => {
            let v = tel.value.replace(/\D/g, '');

            if (v.length > 11) v = v.slice(0, 11);

            let formatado = '';

            if (v.length > 0) formatado = '(' + v.slice(0, 2);
            if (v.length >= 3) formatado += ') ' + v.slice(2, 7);
            if (v.length >= 8) formatado += '-' + v.slice(7, 11);

            tel.value = formatado;
        });
    }
</script>

@endsection