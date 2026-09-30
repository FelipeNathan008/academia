@extends('layouts.dashboard')

@section('title', 'Empresa')

@section('content')
@php
$usuario = auth()->user();
$empresa = $usuario->empresa;
$degustacaoEncerrada = $empresa && $empresa->emp_status === 'Degustação' && $empresa->emp_degustacao_fim &&
now()->startOfDay()->gt($empresa->emp_degustacao_fim->startOfDay());
@endphp

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        @if(!$degustacaoEncerrada)
        <a href="{{ route('admin.controle') }}" class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">← Voltar</a>
        @endif
        <h2 class="text-3xl font-extrabold text-gray-800">Empresa</h2>
    </div>

    <button onclick="toggleEdicao()" class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">
        Editar Empresa
    </button>
</div>

<!-- FORM EDIT -->
<div id="editForm" class="hidden mb-10">
    <form id="formEditEmpresa"
        action="{{ route('empresa.update', urlencode(Crypt::encrypt($empresa->id_empresa))) }}"
        method="POST" enctype="multipart/form-data" onsubmit="bloquearSubmit(event,this)">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">Editar Empresa</h3>

            <!-- DADOS PRINCIPAIS -->
            <div class="border-b pb-8">
                <h4 class="text-lg font-bold text-gray-700 mb-5">Dados da Empresa</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Nome da Empresa</label>
                        <input type="text" name="emp_nome" value="{{ old('emp_nome',$empresa->emp_nome) }}"
                            maxlength="255" placeholder="Academia Exemplo" required
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Nome Fantasia / Apelido</label>
                        <input type="text" name="emp_apelido" value="{{ old('emp_apelido',$empresa->emp_apelido) }}"
                            maxlength="255" placeholder="Ex.: Team Silva" required
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Responsável</label>
                        <input type="text" name="emp_nome_responsavel"
                            value="{{ old('emp_nome_responsavel',$empresa->emp_nome_responsavel) }}"
                            maxlength="255" placeholder="Nome do responsável" required
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">E-mail</label>
                        <input type="email" name="emp_email_responsavel"
                            value="{{ old('emp_email_responsavel',$empresa->emp_email_responsavel) }}"
                            maxlength="255" placeholder="email@empresa.com" required
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Telefone</label>
                        <input type="text" id="emp_telefone" name="emp_telefone_responsavel"
                            value="{{ old('emp_telefone_responsavel',$empresa->emp_telefone_responsavel) }}"
                            placeholder="(00) 00000-0000" maxlength="15" required
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">CPF do Responsável</label>
                        <input type="text" id="emp_cpf" name="emp_cpf"
                            value="{{ old('emp_cpf',$empresa->emp_cpf) }}"
                            placeholder="000.000.000-00" maxlength="14" required
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                    </div>
                </div>
            </div>

            <input type="hidden" name="emp_tipo" value="{{ $empresa->emp_tipo }}">

            <!-- PIX -->
            <div class="border-b py-8">
                <h4 class="text-lg font-bold text-gray-700 mb-5">Dados para Recebimento PIX</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Chave PIX</label>
                        <input type="text" name="emp_chave_pix"
                            value="{{ old('emp_chave_pix',$empresa->emp_chave_pix) }}"
                            placeholder="CPF, telefone, e-mail ou chave aleatória" required
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Nome do Recebedor (PIX) Limite de 25 caracteres</label>
                        <input type="text" name="emp_nome_recebedor"
                            value="{{ old('emp_nome_recebedor',$empresa->emp_nome_recebedor) }}"
                            placeholder="Ex.: ACADEMIA SILVA" maxlength="25" required
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Cidade (PIX) Limite de 15 caracteres</label>
                        <input type="text" name="emp_cidade"
                            value="{{ old('emp_cidade',$empresa->emp_cidade) }}"
                            placeholder="Ex.: SAO PAULO" maxlength="15"
                            style="text-transform:uppercase;" required
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- ENDEREÇO -->
            <div class="border-b py-8">
                <h4 class="text-lg font-bold text-gray-700 mb-5">Endereço da Empresa</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-600">CEP</label>
                        <input type="text" id="emp_end_cep" name="emp_end_cep"
                            value="{{ old('emp_end_cep',$empresa->emp_end_cep) }}"
                            placeholder="00000-000" maxlength="9"
                            oninput="mascaraCEP(this)" onblur="buscarCEP()" required
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-600">Logradouro</label>
                        <input type="text" id="emp_end_logradouro" name="emp_end_logradouro"
                            value="{{ old('emp_end_logradouro',$empresa->emp_end_logradouro) }}"
                            placeholder="Rua, Avenida, etc." required
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Número</label>
                        <input type="text" name="emp_end_numero"
                            value="{{ old('emp_end_numero',$empresa->emp_end_numero) }}"
                            placeholder="Ex: 123" maxlength="10" required
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Complemento</label>
                        <input type="text" name="emp_end_complemento"
                            value="{{ old('emp_end_complemento',$empresa->emp_end_complemento) }}"
                            placeholder="Sala, bloco, etc. (opcional)" maxlength="100"
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Bairro</label>
                        <input type="text" id="emp_end_bairro" name="emp_end_bairro"
                            value="{{ old('emp_end_bairro',$empresa->emp_end_bairro) }}"
                            placeholder="Ex: Centro" required
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Cidade</label>
                        <input type="text" id="emp_end_cidade" name="emp_end_cidade"
                            value="{{ old('emp_end_cidade',$empresa->emp_end_cidade) }}"
                            placeholder="Ex: São José dos Campos" required
                            class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Estado</label>
                        <select id="emp_end_estado" name="emp_end_estado"
                            required
                            class="w-full border rounded-lg px-4 py-2 mt-1 bg-white focus:ring-2 focus:ring-[#8E251F] focus:outline-none">
                            <option value="">Selecione o estado</option>
                            @foreach([
                            'AC' => 'Acre',
                            'AL' => 'Alagoas',
                            'AP' => 'Amapá',
                            'AM' => 'Amazonas',
                            'BA' => 'Bahia',
                            'CE' => 'Ceará',
                            'DF' => 'Distrito Federal',
                            'ES' => 'Espírito Santo',
                            'GO' => 'Goiás',
                            'MA' => 'Maranhão',
                            'MT' => 'Mato Grosso',
                            'MS' => 'Mato Grosso do Sul',
                            'MG' => 'Minas Gerais',
                            'PA' => 'Pará',
                            'PB' => 'Paraíba',
                            'PR' => 'Paraná',
                            'PE' => 'Pernambuco',
                            'PI' => 'Piauí',
                            'RJ' => 'Rio de Janeiro',
                            'RN' => 'Rio Grande do Norte',
                            'RS' => 'Rio Grande do Sul',
                            'RO' => 'Rondônia',
                            'RR' => 'Roraima',
                            'SC' => 'Santa Catarina',
                            'SP' => 'São Paulo',
                            'SE' => 'Sergipe',
                            'TO' => 'Tocantins'
                            ] as $uf => $nome)
                            <option value="{{ $uf }}" {{ old('emp_end_estado', $empresa->emp_end_estado) == $uf ? 'selected' : '' }}>
                                {{ $uf }} - {{ $nome }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- LOGO -->
            <div class="py-8">
                <h4 class="text-lg font-bold text-gray-700 mb-5">Logo da Empresa</h4>

                <input type="file" id="emp_foto" name="emp_foto" accept="image/*"
                    class="w-full border rounded-lg px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-[#8E251F] focus:outline-none">

                <div id="previewContainer" style="display:none;margin-top:15px;text-align:center;">
                    <img id="preview" src="#" alt="Preview"
                        style="width:150px;height:150px;object-fit:cover;border-radius:12px;border:2px solid #e5e7eb;box-shadow:0 5px 15px rgba(0,0,0,.15);">
                </div>
            </div>

            <!-- AÇÕES -->
            <div class="flex justify-end gap-4 border-t pt-6">
                <button type="button" onclick="fecharEdicao()"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-100 transition">
                    Cancelar
                </button>

                <button type="submit"
                    class="px-5 py-2 bg-[#8E251F] text-white rounded-lg hover:bg-[#732920] transition">
                    Salvar Empresa
                </button>
            </div>
        </div>
    </form>
</div>

<!-- DADOS EXIBIDOS -->
<div class="space-y-8">

    <!-- DEGUSTAÇÃO -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b bg-yellow-50">
            <h3 class="text-lg font-bold text-yellow-700">Período de Degustação</h3>
        </div>

        <div class="grid md:grid-cols-3 gap-6 p-6">
            <div>
                <p class="text-xs uppercase text-gray-500">Início</p>
                <p class="font-semibold">{{ \Carbon\Carbon::parse($empresa->emp_degustacao_inicio)->format('d/m/Y') }}</p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500">Fim</p>
                <p class="font-semibold">{{ \Carbon\Carbon::parse($empresa->emp_degustacao_fim)->format('d/m/Y') }}</p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500">Status</p>

                @switch($empresa->emp_status)
                @case('Degustação')
                <span class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-700 font-semibold">Degustação</span>
                @break
                @case('Cliente')
                <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 font-semibold">Cliente</span>
                @break
                @case('Inadimplente')
                <span class="inline-flex px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 font-semibold">Inadimplente</span>
                @break
                @case('Suspenso')
                <span class="inline-flex px-3 py-1 rounded-full bg-orange-100 text-orange-700 font-semibold">Suspenso</span>
                @break
                @case('Encerrado')
                <span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-700 font-semibold">Encerrado</span>
                @break
                @default
                <span class="inline-flex px-3 py-1 rounded-full bg-gray-100 text-gray-700 font-semibold">{{ $empresa->emp_status ?? 'Não definido' }}</span>
                @endswitch
            </div>
        </div>
    </div>

    <!-- DADOS DA EMPRESA -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h3 class="text-lg font-bold text-gray-700">Dados da Empresa</h3>
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
                <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">{{ $empresa->emp_tipo }}</span>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500">Logo</p>
                @if($empresa->emp_foto)
                <img src="{{ asset('images/empresas/'.$empresa->emp_foto) }}" class="w-20 h-20 rounded-xl object-cover border">
                @else
                <span class="text-gray-400">Sem logo</span>
                @endif
            </div>

            <!-- ENDEREÇO -->
            <div class="md:col-span-2 border-t pt-6 mt-2">
                <h4 class="text-lg font-bold text-gray-700 mb-4">Endereço da Empresa</h4>

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
            <h3 class="text-lg font-bold text-green-800">Dados para Recebimento PIX</h3>
        </div>

        <div class="grid md:grid-cols-2 gap-6 p-6">
            <div class="md:col-span-2">
                <p class="text-xs uppercase text-gray-500">Chave PIX</p>
                <p class="font-mono break-all text-green-700">{{ $empresa->emp_chave_pix }}</p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500">Nome do Recebedor</p>
                <p class="font-semibold">{{ $empresa->emp_nome_recebedor }}</p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500">Cidade</p>
                <p class="font-semibold">{{ $empresa->emp_cidade }}</p>
            </div>
        </div>
    </div>
</div>

<script>
    const inputFoto = document.getElementById('emp_foto'),
        preview = document.getElementById('preview'),
        previewContainer = document.getElementById('previewContainer');

    if (inputFoto) {
        inputFoto.addEventListener('change', function() {
            const arquivo = this.files[0];
            if (arquivo) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(arquivo);
            } else previewContainer.style.display = 'none';
        });
    }

    function mascaraCEP(input) {
        let value = input.value.replace(/\D/g, '').slice(0, 8);
        if (value.length > 5) value = value.replace(/^(\d{5})(\d)/, '$1-$2');
        input.value = value;
    }

    function buscarCEP() {
        const cepInput = document.getElementById('emp_end_cep');
        if (!cepInput) return;

        const cep = cepInput.value.replace(/\D/g, '');
        if (cep.length !== 8) return;

        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(res => res.json())
            .then(data => {
                if (data.erro) {
                    alert('CEP não encontrado.');
                    return;
                }
                document.getElementById('emp_end_logradouro').value = data.logradouro || '';
                document.getElementById('emp_end_bairro').value = data.bairro || '';
                document.getElementById('emp_end_cidade').value = data.localidade || '';
                document.getElementById('emp_end_estado').value = data.uf || '';
            })
            .catch(() => alert('Erro ao consultar o CEP.'));
    }

    const cpfInput = document.getElementById('emp_cpf');

    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            let v = e.target.value.replace(/\D/g, '');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = v;
        });
    }

    const telefoneInput = document.getElementById('emp_telefone');

    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            let v = e.target.value.replace(/\D/g, '');
            if (v.length > 11) v = v.substring(0, 11);

            if (v.length > 10) {
                v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
                v = v.replace(/(\d{5})(\d)/, '$1-$2');
            } else {
                v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
                v = v.replace(/(\d{4})(\d)/, '$1-$2');
            }

            e.target.value = v;
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
            btn.style.opacity = '0.7';
        }
    }
</script>

@endsection