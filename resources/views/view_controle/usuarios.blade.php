@extends('layouts.dashboard')

@section('title', 'Usuários')

@section('content')
@php
use Illuminate\Support\Facades\Crypt;
@endphp


<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('filiais') }}" class="hover:text-[#8E251F] transition">
                Filiais
            </a>
        </li>
        <li>/</li>
        <li class="text-gray-400">{{ $filial->filial_apelido }}</li>
        <li>/</li>
        <li class="font-semibold text-gray-700">Usuários da Filial</li>
    </ol>
</nav>
<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">
        <a href="{{ route('filiais') }}"
            class="flex items-center gap-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 transition">
            ← Voltar
        </a>

        <h2 class="text-3xl font-extrabold text-gray-800">
            Usuários da Filial
        </h2>
    </div>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">
        + Cadastrar Usuário
    </button>
</div>

@if ($errors->any())
<div id="alertErro" class="mb-6 flex items-start gap-3 p-4 bg-red-100 border-l-4 border-red-600 text-red-800 rounded-xl shadow-md animate-fade-in">

    <div class="text-red-600 text-xl">
        ⚠️
    </div>

    <div class="flex-1">
        <h4 class="font-bold mb-1">Erro ao cadastrar:</h4>
        <ul class="text-sm space-y-1">
            @foreach ($errors->all() as $error)
            <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>

    <button onclick="fecharAlerta()" class="text-red-600 hover:text-red-800 font-bold text-lg">
        ×
    </button>
</div>
@endif


<!-- CARD DA FILIAL -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#8E251F] rounded-2xl shadow-lg p-6">
        <p class="text-xs uppercase tracking-widest text-gray-500">
            Filial selecionada
        </p>

        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
            {{ $filial->filial_apelido }}
        </h3>

        <p class="mt-2 text-sm text-gray-800">
            Nome: <strong>{{ $filial->filial_nome ?? '-' }}</strong>
        </p>

        <p class="text-sm text-gray-800">
            Responsável: <strong>{{ $filial->filial_nome_responsavel ?? '-' }}</strong>
        </p>
    </div>
</div>
<!-- FORMULÁRIO DE CADASTRO -->
<div id="cadastroForm" class="hidden mb-10">
    <form id="formCadastro" action="{{ route('usuarios.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">Cadastrar Usuário</h3>

            <input type="hidden" name="id_emp_id" value="{{ $empresa->id_empresa ?? '' }}">
            <input type="hidden" name="id_filial_id" value="{{ $filial->id_filial ?? '' }}">

            <div class="flex flex-col gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Nome</label>
                    <input type="text" name="name" placeholder="Digite o nome do usuário" required
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Email</label>
                    <input type="email" name="email" placeholder="exemplo@email.com" required
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Senha</label>
                    <input type="password" name="password"
                        placeholder="Digite a senha"
                        required
                        pattern=".{8,}"
                        title="A senha deve ter no mínimo 8 caracteres"
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                    <small class="text-gray-500 text-sm">Mínimo 8 caracteres</small>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Função</label>
                    <select name="role" required class="w-full border rounded-lg px-4 py-2 mt-1">
                        <option value="" disabled selected>Selecione a função</option>
                        <option value="admin">Administrador</option>
                        <option value="user">Usuário</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-4 border-t pt-6 mt-8">
                <button type="button" onclick="fecharCadastro()" class="px-4 py-2 border rounded-lg">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-[#8E251F] text-white rounded-lg">Salvar</button>
            </div>
        </div>
    </form>
</div>

<!-- LISTAGEM -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">Lista de Usuários</h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gray-300 text-gray-600 text-sm">
                <th class="py-3 px-4">Nome</th>
                <th class="py-3 px-4">Email</th>
                <th class="py-3 px-4">Função</th>
                <th class="py-3 px-4">Empresa</th>
                <th class="py-3 px-4">Filial</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($users as $user)
            <tr class="border-b hover:bg-gray-50 transition">
                <td class="py-3 px-4">{{ $user->name }}</td>
                <td class="py-3 px-4">{{ $user->email }}</td>
                <td class="py-3 px-4">{{ $user->role }}</td>
                <td class="py-3 px-4">{{ $user->empresa->emp_nome ?? '-' }}</td>
                <td class="py-3 px-4">{{ $user->filial->filial_nome ?? '-' }}</td>

                <td class="py-3 px-4 flex gap-2">
                    <a href="{{ route('usuarios.edit', Crypt::encrypt($user->id)) }}"
                        style="background-color: #8E251F; color: white;"
                        class="px-4 py-2 rounded-lg shadow hover:bg-[#732920] transition duration-200 text-center">
                        Editar
                    </a>

                    <form action="{{ route('usuarios.destroy', Crypt::encrypt($user->id)) }}" method="POST"
                        onsubmit="return confirm('Deseja excluir este usuário?');">
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
                    Nenhum usuário cadastrado
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

    function validarTexto(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ0-9\s]/g, '');
    }


    function fecharAlerta() {
        const alerta = document.getElementById('alertErro');
        if (alerta) {
            alerta.style.display = 'none';
        }
    }
</script>
@endsection