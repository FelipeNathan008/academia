@extends('layouts.dashboard')

@section('title', 'Editar Usuário')

@section('content')
@php
use Illuminate\Support\Facades\Crypt;
@endphp

<!-- TOPO -->
<div class="flex items-center justify-between mb-10">
    <h2 class="text-3xl font-extrabold text-gray-800">
        Editar Usuário
    </h2>

    <a href="{{ route('usuarios.empresa') }}"
        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">
        ← Voltar
    </a>
</div>

@if ($errors->any())
<div class="mb-6 p-4 bg-red-100 border-l-4 border-red-600 text-red-800 rounded-xl">
    <ul>
        @foreach ($errors->all() as $error)
        <li>• {{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- CARD -->
<div class="mb-8">
    <div class="bg-white border-l-8 border-[#8E251F] rounded-2xl shadow-lg p-6">
        <p class="text-xs text-gray-500 uppercase">Empresa</p>

        <h3 class="text-2xl font-bold text-gray-800 mt-1">
            {{ $user->empresa->emp_nome ?? '-' }}
        </h3>
    </div>
</div>

<!-- FORM -->
<form action="{{route('usuarios.empresa.update', Crypt::encrypt($user->id))}}" method="POST">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-2xl shadow-md p-8">

        <input type="hidden" name="id_emp_id" value="{{ $user->id_emp_id }}">
        <input type="hidden" name="id_filial_id" value="">

        <div class="grid md:grid-cols-2 gap-6">

            <div>
                <label class="text-sm text-gray-600">Nome</label>
                <input type="text" name="name" value="{{ $user->name }}" required
                    class="w-full border rounded-lg px-4 py-2 mt-1">
            </div>

            <div>
                <label class="text-sm text-gray-600">Email</label>
                <input type="email" name="email" value="{{ $user->email }}" required
                    class="w-full border rounded-lg px-4 py-2 mt-1">
            </div>

            <div>
                <label class="text-sm text-gray-600">Nova Senha</label>
                <input type="password" name="password"
                    placeholder="Deixe em branco para manter"
                    class="w-full border rounded-lg px-4 py-2 mt-1">
            </div>

            <div>
                <label class="text-sm text-gray-600">Função</label>
                <select name="role" class="w-full border rounded-lg px-4 py-2 mt-1">
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>
                        Administrador
                    </option>

                    <option value="professor" {{ $user->role == 'professor' ? 'selected' : '' }}>
                        Professor
                    </option>

                    <option value="aluno" {{ $user->role == 'aluno' ? 'selected' : '' }}>
                        Aluno
                    </option>
                </select>
            </div>

        </div>

        <div class="flex justify-end gap-4 mt-8">
            <a href="{{ route('usuarios.empresa') }}"
                class="px-4 py-2 border rounded-lg">
                Cancelar
            </a>

            <button type="submit"
                class="px-5 py-2 bg-[#8E251F] text-white rounded-lg">
                Atualizar
            </button>
        </div>

    </div>
</form>

@endsection