@extends('layouts.dashboard')

@section('title', 'Editar Usuário')

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
        <a href="{{ route('usuarios.index', ['filial' => Crypt::encrypt($filial->id_filial)]) }}"class="hover:text-[#8E251F] transition">
            Usuários da Filial
        </a>

        </a>
        <li>/</li>
        <li class="font-semibold text-gray-700">Editar Usuário</li>
    </ol>
</nav>
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

<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md p-8">

    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Editar Usuário – {{ $user->name }}
    </h2>

    <form action="{{ route('usuarios.update', Crypt::encrypt($user->id)) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="id_filial_id" value="{{ $user->id_filial_id }}">
        <input type="hidden" name="id_emp_id" value="{{ $user->id_emp_id }}">
        <!-- Nome + Email -->
        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Nome</label>
                <input type="text"
                    name="name"
                    required
                    value="{{ old('name', $user->name) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>

            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Email</label>
                <input type="email"
                    name="email"
                    required
                    value="{{ old('email', $user->email) }}"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">
            </div>
        </div>

        <!-- Senha + Função -->
        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Senha</label>
                <input type="password"
                    name="password"
                    placeholder="Nova senha (opcional)"
                    pattern=".{8,}"
                    title="A senha deve ter no mínimo 8 caracteres"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">

                <small class="text-gray-500 text-sm">
                    Preencha apenas se quiser alterar a senha
                </small>
                @error('password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600">Função</label>
                <select name="role"
                    class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring-2 focus:ring-[#8E251F]">

                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>
                        Administrador
                    </option>

                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>
                        Usuário
                    </option>
                </select>
            </div>
        </div>

        <!-- BOTÕES -->
        <div class="flex justify-end gap-4 mt-6">
            <a href="{{ route('usuarios.index', Crypt::encrypt($user->id_filial_id)) }}"
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
    function fecharAlerta() {
        const alerta = document.getElementById('alertErro');
        if (alerta) {
            alerta.style.display = 'none';
        }
    }
</script>
@endsection