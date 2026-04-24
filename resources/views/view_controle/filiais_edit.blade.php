@extends('layouts.dashboard')

@section('title', 'Editar Filial')

@section('content')

<!-- BREADCRUMB -->
<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2 flex-wrap">
        <li>
            <a href="{{ route('filiais') }}" class="hover:text-[#8E251F] transition">
                Filiais
            </a>
        </li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Editar Filial</li>
    </ol>
</nav>

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-8">

    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Editar Filial – {{ $filial->filial_nome }}
    </h2>

    <form action="{{route('filiais.update', Crypt::encrypt($filial->id_filial)) }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Empresa + Nome -->
        <div class="flex gap-4 mb-4">
            <input type="hidden" name="id_emp_id" value="{{ auth()->user()->id_emp_id }}">


            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Nome da Filial</label>
                <input type="text"
                    name="filial_nome"
                    required
                    placeholder="Digite o nome da filial"
                    value="{{ old('filial_nome', $filial->filial_nome) }}"
                    oninput="validarTexto(this)"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>
        </div>

        <!-- Apelido + Responsável -->
        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Apelido</label>
                <input type="text"
                    name="filial_apelido"
                    required
                    placeholder="Digite um apelido"
                    value="{{ old('filial_apelido', $filial->filial_apelido) }}"
                    oninput="validarTexto(this)"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>

            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Responsável</label>
                <input type="text"
                    name="filial_nome_responsavel"
                    required
                    placeholder="Nome do responsável"
                    value="{{ old('filial_nome_responsavel', $filial->filial_nome_responsavel) }}"
                    oninput="validarNome(this)"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>
        </div>

        <!-- Email + Telefone -->
        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Email</label>
                <input type="email"
                    name="filial_email_responsavel"
                    required
                    placeholder="exemplo@email.com"
                    value="{{ old('filial_email_responsavel', $filial->filial_email_responsavel) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>

            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Telefone</label>
                <input type="text"
                    id="filial_telefone"
                    name="filial_telefone_responsavel"
                    required
                    placeholder="(00) 00000-0000"
                    value="{{ old('filial_telefone_responsavel', $filial->filial_telefone_responsavel) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>
        </div>

        <!-- CPF + FOTO -->
        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">CPF</label>
                <input type="text"
                    name="filial_cpf"
                    required
                    placeholder="000.000.000-00"
                    value="{{ old('filial_cpf', $filial->filial_cpf) }}"
                    oninput="mascaraCPF(this)"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>

            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Foto</label>
                <input type="file"
                    name="filial_foto"
                    accept="image/*"
                    class="w-full border rounded-lg px-4 py-2 mt-1">

                <!-- PREVIEW DA IMAGEM -->
                @if($filial->filial_foto)
                <div class="mt-3">
                    <p class="text-sm text-gray-500 mb-1">Imagem atual:</p>
                    <img src="{{ asset('images/emp_filiais_logo/' . $filial->filial_foto) }}"
                        class="w-24 h-24 object-cover rounded-lg border shadow">
                </div>
                @endif
            </div>
        </div>

        <!-- AÇÕES -->
        <div class="flex justify-end gap-4 mt-6">
            <a href="{{ route('filiais') }}"
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

<script>
    function validarNome(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
    }

    function validarTexto(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ0-9\s]/g, '');
    }

    function mascaraCPF(input) {
        let v = input.value.replace(/\D/g, '').slice(0, 11);
        v = v.replace(/(\d{3})(\d)/, '$1.$2')
            .replace(/(\d{3})(\d)/, '$1.$2')
            .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        input.value = v;
    }

    const tel = document.getElementById('filial_telefone');

    tel.addEventListener('input', () => {
        let v = tel.value.replace(/\D/g, '').slice(0, 11);

        let formatado = '';
        if (v.length > 0) formatado = '(' + v.slice(0, 2);
        if (v.length >= 3) formatado += ') ' + v.slice(2, 7);
        if (v.length >= 8) formatado += '-' + v.slice(7, 11);

        tel.value = formatado;
    });
</script>

@endsection