@extends('layouts.dashboard')

@section('title', 'Usuários da Empresa')

@section('content')
@php
use Illuminate\Support\Facades\Crypt;
@endphp

<!-- TOPO -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-10">
    <div class="flex items-center gap-4">

        <h2 class="text-3xl font-extrabold text-gray-800">
            Usuários da Empresa
        </h2>
    </div>

    <button onclick="toggleCadastro()"
        class="px-6 py-3 bg-[#8E251F] text-white rounded-xl shadow-md hover:bg-[#732920] hover:shadow-lg transition-all">
        + Cadastrar Usuário
    </button>
</div>

@if ($errors->any())
<div id="alertErro" class="mb-6 flex items-start gap-3 p-4 bg-red-100 border-l-4 border-red-600 text-red-800 rounded-xl shadow-md">

    <div class="text-red-600 text-xl">⚠️</div>

    <div class="flex-1">
        <h4 class="font-bold mb-1">Erro ao cadastrar:</h4>
        <ul class="text-sm space-y-1">
            @foreach ($errors->all() as $error)
            <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>

    <button onclick="fecharAlerta()" class="text-red-600 font-bold text-lg">×</button>
</div>
@endif

<!-- CARD DA EMPRESA -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#8E251F] rounded-2xl shadow-lg p-6">
        <p class="text-xs uppercase tracking-widest text-gray-500">
            Empresa selecionada
        </p>

        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">
            {{ $empresa->emp_nome }}
        </h3>
    </div>
</div>

<!-- FORM -->
<div id="cadastroForm" class="hidden mb-10">
    <form id="formCadastro" action="{{ route('usuarios.store') }}" method="POST" onsubmit="bloquearSubmit(event, this)">
        @csrf

        <div class="bg-white rounded-2xl shadow-md p-8">
            <h3 class="text-xl font-bold mb-6 text-gray-700">Cadastrar Usuário</h3>

            <input type="hidden" name="id_emp_id" value="{{ $empresa->id_empresa }}">
            <input type="hidden" name="id_filial_id" value="">

            <div class="flex flex-col gap-4">

                <div>
                    <label class="text-sm font-medium text-gray-600">Nome</label>
                    <input type="text" name="name"
                        placeholder="Digite o nome do usuário"
                        required
                        class="w-full border rounded-lg px-4 py-2 mt-1">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Email</label>
                    <input type="email" name="email"
                        placeholder="exemplo@email.com"
                        required
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

<!-- LISTA -->
<div class="bg-white rounded-2xl shadow-md p-6">
    <h3 class="text-xl font-bold mb-6 text-gray-700">Lista de Usuários</h3>

    <table class="w-full text-left">
        <thead>
            <tr class="border-b text-gray-600 text-sm">
                <th class="py-3 px-4">Nome</th>
                <th class="py-3 px-4">Email</th>
                <th class="py-3 px-4">Função</th>
                <th class="py-3 px-4">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($users as $user)
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3 px-4">{{ $user->name }}</td>
                <td class="py-3 px-4">{{ $user->email }}</td>
                <td class="py-3 px-4">{{ $user->role }}</td>

                <td class="py-3 px-4 flex gap-2">
                    <a href="{{ route('usuarios.empresa.edit', Crypt::encrypt($user->id)) }}"
                        class="px-4 py-2 bg-[#8E251F] text-white rounded-lg">
                        Editar
                    </a>

                    <form action="{{ route('usuarios.destroy', Crypt::encrypt($user->id)) }}" method="POST"
                        onsubmit="return confirm('Deseja excluir este usuário?');">
                        @csrf
                        @method('DELETE')

                        <button class="px-4 py-2 bg-red-600 text-white rounded-lg">
                            Excluir
                        </button>
                    </form>
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="4" class="text-center text-gray-500 py-6">
                    Nenhum usuário cadastrado
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    function bloquearSubmit(event, form) {

        if (!form.checkValidity()) {
            return; // deixa validação normal do HTML
        }

        const btn = form.querySelector('button[type="submit"]');

        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Salvando...';
        }
    }

    function toggleCadastro() {
        const form = document.getElementById('cadastroForm');
        form.classList.toggle('hidden');
    }

    function fecharCadastro() {
        document.getElementById('cadastroForm').classList.add('hidden');
    }

    function fecharAlerta() {
        const alerta = document.getElementById('alertErro');
        if (alerta) alerta.style.display = 'none';
    }
</script>

@endsection