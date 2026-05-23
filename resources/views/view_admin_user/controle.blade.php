@extends('layouts.dashboard')

@section('title', 'Controle')

@section('content')

<nav class="mb-6 text-sm text-gray-500">
    <ol class="flex items-center gap-2">
        <li class="font-semibold text-gray-700">
            Controle
        </li>
    </ol>
</nav>

<div class="bg-white shadow-lg rounded-2xl p-6">

    <h3 class="text-2xl font-extrabold text-gray-800 mb-6">
        Controle do Sistema
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

        <a href="{{ route('filiais') }}"
            style="background-color: #0F4C81; color: white;"
            class="px-4 py-3 rounded-xl shadow text-center font-semibold">
            Filiais
        </a>

        <a href="{{ route('usuarios.indexEmpresa') }}"
            style="background-color: #15803d; color: white;"
            class="px-4 py-3 rounded-xl shadow text-center font-semibold">
            Usuários
        </a>

        <a href="{{ route('empresa') }}"
            style="background-color: #ca8a04; color: white;"
            class="px-4 py-3 rounded-xl shadow text-center font-semibold">
            Empresa
        </a>

    </div>

</div>

@endsection