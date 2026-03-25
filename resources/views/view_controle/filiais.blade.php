@extends('layouts.dashboard')

@section('title', 'Filiais')

@section('content')
@php
use Illuminate\Support\Facades\Crypt;
@endphp
<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-800">Filiais</h2>
    </div>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">
        + Cadastrar Filial
    </button>
</div>

<!-- FORMULÁRIO DE CADASTRO -->
<div id="cadastroForm" class="hidden mb-10">
    <form id="formCadastro" action="{{ route('filiais.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">Cadastrar Filial</h3>

            <div class="flex flex-col gap-4">

                <!-- Empresa + Nome -->
                <div style="display: flex; gap: 4%;">
                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">Empresa</label>
                        <select name="id_emp_id" required class="w-full border rounded-lg px-4 py-2 mt-1">
                            <option value="" disabled selected>Selecione a empresa</option>
                            @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id_empresa }}">{{ $empresa->emp_nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">Nome da Filial</label>
                        <input type="text" name="filial_nome" placeholder="Digite o nome da filial"
                            oninput="validarTexto(this)" required
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>
                </div>

                <!-- Apelido + Responsável -->
                <div style="display: flex; gap: 4%;">
                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">Apelido</label>
                        <input type="text" name="filial_apelido" placeholder="Digite um apelido"
                            oninput="validarTexto(this)" required
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>

                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">Responsável</label>
                        <input type="text" name="filial_nome_responsavel" placeholder="Nome do responsável"
                            oninput="validarNome(this)" required
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>
                </div>

                <!-- Email + Telefone -->
                <div style="display: flex; gap: 4%;">
                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">Email</label>
                        <input type="email" name="filial_email_responsavel" placeholder="exemplo@email.com"
                            required class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>

                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">Telefone</label>
                        <input type="text" id="filial_telefone" name="filial_telefone_responsavel"
                            placeholder="(00) 00000-0000" required
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>
                </div>

                <!-- CPF + FOTO -->
                <div style="display: flex; gap: 4%;">
                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">CPF</label>
                        <input type="text" name="filial_cpf" placeholder="000.000.000-00"
                            oninput="mascaraCPF(this)"
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>

                    <div style="flex: 1;">
                        <label class="text-sm font-medium text-gray-600">Foto</label>
                        <input type="file" name="filial_foto" accept="image/*"
                            class="w-full border rounded-lg px-4 py-2 mt-1">
                    </div>
                </div>

            </div>

            <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                <button type="button" onclick="fecharCadastro()" class="px-4 py-2 border rounded-lg">
                    Cancelar
                </button>

                <button type="submit" class="px-5 py-2 bg-[#8E251F] text-white rounded-lg">
                    Salvar
                </button>
            </div>
        </div>
    </form>
</div>
<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">Lista de Filiais</h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gray-300 text-gray-600 text-sm">
                <th class="py-3 px-4">Logo</th>
                <th class="py-3 px-4">Nome</th>
                <th class="py-3 px-4">Apelido</th>
                <th class="py-3 px-4">Responsável</th>
                <th class="py-3 px-4">Email</th>
                <th class="py-3 px-4">Telefone</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($filiais as $filial)
            <tr class="border-b hover:bg-gray-50 transition">
                <td class="py-3 px-4">
                    @if($filial->filial_foto)
                    <div class="w-12 h-12 overflow-hidden">
                        <img src="{{ asset('images/emp_filiais_logo/' . $filial->filial_foto) }}"
                            alt="Foto" style="width:48px; height:48px; object-fit:cover;">
                    </div>
                    @else
                    -
                    @endif
                </td>
                <td class="py-3 px-4">{{ $filial->filial_nome }}</td>
                <td class="py-3 px-4">{{ $filial->filial_apelido }}</td>
                <td class="py-3 px-4">{{ $filial->filial_nome_responsavel }}</td>
                <td class="py-3 px-4">{{ $filial->filial_email_responsavel }}</td>
                <td class="py-3 px-4">{{ $filial->filial_telefone_responsavel }}</td>

                <td class="py-3 px-4 flex gap-2">

                    <a href="{{ route('detalhes-filial.index', Crypt::encrypt($filial->id_filial)) }}"
                        style="background-color: #174ab9; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e40af] transition duration-200 text-center">
                        Detalhes
                    </a>

                    <a href="{{ route('usuarios.index', ['filial' => Crypt::encrypt($filial->id_filial)]) }}"
                        style="background-color: #15803d; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#1e593a] transition duration-200 text-center">
                        Users
                    </a>


                    <!-- Editar -->
                    <a href="{{route('filiais.edit', Crypt::encrypt($filial->id_filial)) }}"
                        style="background-color: #8E251F; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                        Editar
                    </a>

                    <!-- Excluir -->
                    <form action="{{ route('filiais.destroy', Crypt::encrypt($filial->id_filial))  }}"
                        method="POST"
                        onsubmit="return confirm('Deseja excluir esta filial?');">
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
                <td colspan="6" class="text-center text-gray-500 py-6">
                    Nenhuma filial cadastrada
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
        document.getElementById('formCadastro').reset();
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }

    function validarNome(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
    }

    function validarTexto(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ0-9\s]/g, '');
    }

    function mascaraCPF(input) {
        let value = input.value.replace(/\D/g, '').slice(0, 11);

        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

        input.value = value;
    }

    const tel = document.getElementById('filial_telefone');

    tel.addEventListener('input', () => {
        let v = tel.value.replace(/\D/g, '');

        if (v.length > 11) v = v.slice(0, 11);

        let formatado = '';

        if (v.length > 0) formatado = '(' + v.slice(0, 2);
        if (v.length >= 3) formatado += ') ' + v.slice(2, 7);
        if (v.length >= 8) formatado += '-' + v.slice(7, 11);

        tel.value = formatado;
    });
</script>

@endsection